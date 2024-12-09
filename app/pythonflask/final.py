from flask import Flask, request, jsonify
import os
import pytesseract
import openai
import numpy as np
import cv2
from pdf2image import convert_from_path
from PIL import Image
from docx import Document
import re

# Set up your OpenAI API key
openai.api_key = "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"

# Initialize Flask app
app = Flask(__name__)
UPLOAD_FOLDER = 'uploads'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

def clean_extracted_text(text):
    # Replace newline and carriage return characters with a space
    text = text.replace("\n", " ").replace("\r", " ")
    
    # Reduce multiple spaces to a single space
    text = re.sub(r'\s+', ' ', text)
    
    # Remove invalid characters but preserve special Latin characters (e.g., Ñ, Á, É)
    text = re.sub(r'[^\w\s.,;:!?@#$%^&*()_+=\-/<>|~`"\'ÑñÁÉÍÓÚáéíóúÜüÇç]', '', text, flags=re.UNICODE)
    
    # Common OCR character corrections
    corrections = {
        "0": "O", "1": "I", "|": "I", "l": "I", "rn": "m"
    }
    for wrong, correct in corrections.items():
        text = text.replace(wrong, correct)
    
    # Remove footer patterns or irrelevant text
    footer_patterns = [
        r'Page \d+ of \d+',  # Matches "Page X of Y"
        r'^\s*\d+\s*$',      # Matches standalone numbers (e.g., page numbers)
        r'Confidential\s*$', # Matches "Confidential" text
        r'Footer:.*$'        # Matches "Footer: ..." text
    ]
    for pattern in footer_patterns:
        text = re.sub(pattern, '', text, flags=re.IGNORECASE)
    
    return text.strip()

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

# Extract text from a scanned PDF with OCR
def extract_text_from_scanned_pdf(pdf_path):
    pages = convert_from_path(pdf_path, 150)
    text = ''
    for page_number, page in enumerate(pages, start=1):
        preprocessed_image = preprocess_image(page)
        # Experiment with Tesseract's PSM and OEM options
        custom_config = r'--oem 3 --psm 3'
        text += pytesseract.image_to_string(preprocessed_image, config=custom_config)
    return text

# Extract text from DOCX files
def extract_text_from_docx(docx_path):
    document = Document(docx_path)
    text = '\n'.join([para.text for para in document.paragraphs])
    return text

# Perform OCR on image files
def extract_text_from_image(image_path):
    image = Image.open(image_path)
    preprocessed_image = preprocess_image(image)
    # Experiment with Tesseract's PSM and OEM options
    custom_config = r'--oem 3 --psm 6'
    text = pytesseract.image_to_string(preprocessed_image, config=custom_config)
    return text

# Classify document type using keywords
def classify_document_type(extracted_text):
    text_lower = extracted_text.lower()
    if "ched memorandum order" in text_lower or "commission on higher education" in text_lower:
        return "CHED Circular"
    if "commission on audit" in text_lower:
        return "COA Circular"
    if "national budget circular" in text_lower:
        return "Budget Circular"
    if "notice of meeting" in text_lower or "notice of meetings" in text_lower:
        return "Notice of Meeting"
    elif "travel order" in text_lower:
        return "Travel Order"
    elif "special order" in text_lower or "special order no." in text_lower:
        return "Special Order"
    elif "office order" in text_lower:
        return "Office Order"
    else:
        return "Unknown"

# Extract specific document fields using GPT-4
def extract_document_fields(cleaned_text):
    prompt = f"""
    Extract the following fields from the text:
    document_no (only numbers)
    series_no (series year)
    date_issued (date issued)
    from_date (start of the event, inclusive date)
    to_date (finish of the event, inclusive date)
    subject (purpose, Topic or focus)
    description (main body, Detailed explanation or summary provided)
    venue (place where the event to be held)
    destination (categorize into 'Regional', 'National', or 'International' based on Region VIII)
    employee_names: [] (return as a list of strings with each name starts with Firstname then Lastname then start with an uppercase letter followed by lowercase letters, remove middle initials on names, remove titles like Ms, Mr, Dr, etc., don't include the sender )

    If a field is missing, return "None" for that field.

    Text:
    {cleaned_text}
    """
    response = openai.ChatCompletion.create(
        model="gpt-4",
        messages=[{"role": "system", "content": "You are an assistant that extracts structured information from text."},
                  {"role": "user", "content": prompt}],
        temperature=0.1,
        max_tokens=4000
    )
    fields = {}
    response_text = response['choices'][0]['message']['content'].strip()
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
        raise ValueError("Unsupported file type.")
    cleaned_text = clean_extracted_text(extracted_text)
    document_fields = extract_document_fields(cleaned_text)
    document_type = classify_document_type(cleaned_text)
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
