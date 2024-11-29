<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentType;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadDocumentController extends Controller
{
    public function getDocumentTypes()
    {
        try {
            $documentTypes = DocumentType::all(); // Fetch all document types
            return response()->json($documentTypes, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch document types'], 500);
        }
    }

    /**
     * Handle document upload and save details.
     */
    public function uploadDocument(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'documentTypeId' => 'required|exists:document_types,id',
            'documentNo' => 'nullable|string|max:255',
            'seriesNo' => 'nullable|string|max:255',
            'dateIssued' => 'nullable|date',
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date',
            'venue' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'employeeNames' => 'nullable|array',
            'employeeNames.*' => 'nullable|string|max:255',
            'studentNames' => 'nullable|array',
            'studentNames.*' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // Maximum file size 10 MB
        ]);
    
        try {
            // Handle file upload
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('documents', 'public'); // Store file in "storage/app/public/documents"
            } else {
                return response()->json(['error' => 'File upload failed'], 400);
            }
    
            // Save document details
            $document = new Document();
            $document->document_type_id = $validated['documentTypeId'];
            $document->document_no = $validated['documentNo'];
            $document->series_no = $validated['seriesNo'];
            $document->date_issued = $validated['dateIssued'];
            $document->from_date = $validated['fromDate'];
            $document->to_date = $validated['toDate'];
            $document->venue = $validated['venue'];
            $document->destination = $validated['destination'];
            $document->subject = $validated['subject'];
            $document->description = $validated['description'];
    
            // Assign arrays directly to model attributes (Laravel handles JSON encoding)
            $document->employee_names = $validated['employeeNames'] ?? null;
            $document->student_names = $validated['studentNames'] ?? null;
    
            $document->file_path = $filePath;
            $document->save();
    
            return response()->json(['message' => 'Document uploaded successfully!', 'document' => $document], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload document', 'details' => $e->getMessage()], 500);
        }
    }
    
    
}
