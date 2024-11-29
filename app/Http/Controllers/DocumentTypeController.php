<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentType;
use App\Models\Document;

class DocumentTypeController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'document_type' => 'required|string|unique:document_types,document_type|max:255',
        ]);

        try {
            // Create the document type
            $documentType = DocumentType::create([
                'document_type' => $request->document_type,
            ]);

            // Return success response
            return response()->json([
                'message' => 'Document type created successfully.',
                'document_type' => $documentType,
            ], 201);
        } catch (\Exception $e) {
            // Return error response if something goes wrong
            return response()->json([
                'error' => 'Failed to create document type. Please try again.',
            ], 500);
        }
    }
    // Method to fetch all document types
public function index()
{
    $documentTypes = DocumentType::all();
    return response()->json(['documentTypes' => $documentTypes]);
}

// Method to update a document type
public function update(Request $request, $id)
{
    $documentType = DocumentType::findOrFail($id);

    $request->validate([
        'document_type' => 'required|string|unique:document_types,document_type,' . $id,
    ]);

    $documentType->update([
        'document_type' => $request->document_type,
    ]);

    return response()->json($documentType);
}

// Method to delete a document type
public function destroy($id)
{
    $documentType = DocumentType::findOrFail($id);
    $documentType->delete();

    return response()->json(null, 204); // No content on success
}

}
