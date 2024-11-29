from flask import Flask, request, jsonify
import os
import joblib
from pdf2image import convert_from_path
from PIL import Image
import pytesseract
import openai
import numpy as np
import cv2
from docx import Document

# Set up your OpenAI API key
openai.api_key = ""

# Initialize Flask app
app = Flask(__name__)
UPLOAD_FOLDER = 'uploads'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

# Function to clean extracted text using GPT-4
def clean_extracted_text(text):
    prompt = f"""
    Please clean up the following text by removing headers and footers, correcting OCR errors, 
    and maintaining readability. Ensure that all names, dates, document numbers, place or settings and other identifiable information remain intact.
    
    Text:
    {text}
    """
    
    response = openai.ChatCompletion.create(
        model="gpt-4",
        messages=[{"role": "system", "content": "You are a helpful assistant for text cleanup."},
                  {"role": "user", "content": prompt}],
        temperature=0.1,
        max_tokens=4000
    )
    return response['choices'][0]['message']['content'].strip()

# Image preprocessing for OCR enhancement
def preprocess_image(image):
    gray = np.array(image.convert('L'))
    blurred = cv2.GaussianBlur(gray, (3, 3), 0)
    adaptive_thresh = cv2.adaptiveThreshold(
        blurred, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY, 11, 2
    )
    inverted = cv2.bitwise_not(adaptive_thresh)
    denoised = cv2.fastNlMeansDenoising(inverted, None, 30, 7, 21)
    edges = cv2.Canny(denoised, 100, 200)
    denoised[edges == 255] = 0
    return Image.fromarray(denoised)

# Extract text from specific regions in an image
def extract_text_from_column(image, column_box):
    column_image = image.crop(column_box)
    custom_config = r'--psm 3 --oem 3'
    text = pytesseract.image_to_string(column_image, config=custom_config)
    return text

# Extract text from a scanned PDF with multiple columns
def extract_text_from_scanned_pdf(pdf_path):
    pages = convert_from_path(pdf_path, 150)
    text = ''
    for page_number, page in enumerate(pages, start=1):
        preprocessed_image = preprocess_image(page)
        header_box = (0, 0, preprocessed_image.width, int(preprocessed_image.height * 0.2))
        left_column_box = (0, int(preprocessed_image.height * 0.2), preprocessed_image.width // 2, preprocessed_image.height)
        right_column_box = (preprocessed_image.width // 2, int(preprocessed_image.height * 0.2), preprocessed_image.width, preprocessed_image.height)
        
        header_text = extract_text_from_column(preprocessed_image, header_box)
        left_text = extract_text_from_column(preprocessed_image, left_column_box)
        right_text = extract_text_from_column(preprocessed_image, right_column_box)
        
        combined_text = f"--- Page {page_number} ---\nHeader:\n{header_text}\n\nLeft Column:\n{left_text}\n\nRight Column:\n{right_text}"
        text += f"\n{combined_text}"
    
    cleaned_text = clean_extracted_text(text)
    return cleaned_text

# Extract text from DOCX files
def extract_text_from_docx(docx_path):
    document = Document(docx_path)
    text = '\n'.join([para.text for para in document.paragraphs])
    return text

# Perform OCR on image files
def extract_text_from_image(image_path):
    image = Image.open(image_path)
    preprocessed_image = preprocess_image(image)
    text = pytesseract.image_to_string(preprocessed_image)
    return text

# Extract specific document fields using GPT-4
def extract_document_fields(cleaned_text):
    prompt = f"""
    Extract the following fields from the text:
    document_no (only numbers)
    series_no (series year, year issued)
    date_issued
    from_date (start of the event, inclusive date)
    to_date (finish of the event, inclusive date)
    subject (purpose)
    description (main body)
    venue (place where the event to be held)
    destination (categorize into 'Regional', 'National', or 'International' based on Region VIII)
    employee_names: [] (return as a list of strings with only first and last names, remove middle initials, remove titles like Ms, Mr, Dr, etc. )

    If a field is missing, return "None" for that field.

    Text:
    {cleaned_text}
    """
    response = openai.ChatCompletion.create(
        model="gpt-4",
        messages=[
            {"role": "system", "content": "You are an assistant that extracts structured information from text."},
            {"role": "user", "content": prompt}
        ],
        temperature=0.1,
        max_tokens=4000
    )

    fields = {}
    response_text = response['choices'][0]['message']['content'].strip()

    # Parse GPT-4 response into a dictionary format
    for line in response_text.splitlines():
        if ": " in line:
            field_name, field_value = line.split(": ", 1)
            if field_name.strip().lower() == "employee_names":
                fields['employee_names'] = [
                    name.strip().strip('"') for name in field_value.strip("[]").split(",") if name.strip()
                ] if field_value != "None" else None
            else:
                fields[field_name.strip().lower()] = field_value.strip() if field_value != "None" else None

    return fields

# Identify document type using GPT-4 based on extracted text
def classify_document_type(extracted_text):
    # Convert text to uppercase to make keyword detection case-insensitive
    upper_text = extracted_text.upper()

    # Check for keywords indicating document type
    if "TRAVEL ORDER" in upper_text:
        return "Travel Order"
    elif "OFFICE ORDER" in upper_text:
        return "Office Order"
    elif "SPECIAL ORDER" in upper_text:
        return "Special Order"
    else:
        # Fallback to using GPT-4 if no keywords are found
        prompt = f"""
        Based on the following text, identify the type of document. The possible types are:
        - Travel Order
        - Office Order
        - Special Order

        If the text does not match any of these categories, respond with "Unknown".
        
        Text:
        {extracted_text}
        """

        response = openai.ChatCompletion.create(
            model="gpt-4",
            messages=[
                {"role": "system", "content": "You are an assistant that identifies document types based on content."},
                {"role": "user", "content": prompt}
            ],
            temperature=0.1,
            max_tokens=10  # Set a low token limit for a concise response
        )
        
        document_type = response['choices'][0]['message']['content'].strip()
        # Validate response to match expected types
        if document_type in ["Travel Order", "Office Order", "Special Order"]:
            return document_type

    prompt = f"""
    Identify the type of document from the following text. Possible types are:
    - travel order
    - office order
    - special order

    Text:
    {extracted_text}
    """
    
    response = openai.ChatCompletion.create(
        model="gpt-4",
        messages=[
            {"role": "system", "content": "You are an assistant that identifies document types based on content."},
            {"role": "user", "content": prompt}
        ],
        temperature=0.1,
        max_tokens=100
    )
    
    # Parse the response to get the document type
    document_type = response['choices'][0]['message']['content'].strip()
    return document_type

# Handle file input for various formats (PDF, DOCX, images)
def extract_text_from_file(file_path):
    file_extension = os.path.splitext(file_path)[1].lower()
    
    if file_extension == '.pdf':
        extracted_text = extract_text_from_scanned_pdf(file_path)
    elif file_extension == '.docx':
        extracted_text = extract_text_from_docx(file_path)
    elif file_extension in ['.jpg', '.jpeg', '.png', '.tiff', '.bmp']:
        extracted_text = extract_text_from_image(file_path)
    else:
        raise ValueError("Unsupported file type. Supported file types: PDF, DOCX, image files (JPG, PNG, TIFF, etc.)")
    
    cleaned_text = clean_extracted_text(extracted_text)
    document_fields = extract_document_fields(cleaned_text)
    document_type = classify_document_type(cleaned_text)  # Using GPT-4 for document type classification
    
    return cleaned_text, document_type, document_fields

# Flask route to handle file upload and extraction
@app.route('/api/admin/upload', methods=['POST'])
def extract_text():
    file = request.files.get('file')
    if file:
        file_path = os.path.join(UPLOAD_FOLDER, file.filename)
        file.save(file_path)
        
        try:
            cleaned_text, document_type, document_fields = extract_text_from_file(file_path)
            
            response = {
                'document_type': document_type,
                'extracted_fields': document_fields,
                'cleaned_text': cleaned_text
            }
            return jsonify(response), 200
        except Exception as e:
            return jsonify({"error": str(e)}), 400
    else:
        return jsonify({"error": "No file provided"}), 400

if __name__ == '__main__':
    app.run(debug=True)
