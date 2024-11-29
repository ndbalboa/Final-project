<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\GeneratedReport;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function indexs(Request $request)
    {
        // Create a base query for documents, join with the document_types table
        $query = Document::join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->where('document_types.document_type', 'Travel Order'); // Filter by document type name (e.g., Travel Order)
    
        // Filter by date range if provided
        if ($request->startDate && $request->endDate) {
            $query->whereBetween('date_issued', [
                Carbon::parse($request->startDate),
                Carbon::parse($request->endDate)
            ]);
        }
    
        // Filter by employee if provided
        if ($request->employee) {
            $employee = Employee::find($request->employee);
            if ($employee) {
                $query->whereJsonContains('employee_names', $employee->full_name);
            }
        }
    
        // Fetch the filtered documents
        $travelOrders = $query->select('documents.*')->get(); // Select only the document fields
    
        return response()->json(['travelOrders' => $travelOrders]);
    }
    
    public function getEmployees()
    {
        $employees = Employee::all(['id', 'firstName', 'lastName']);
        return response()->json([
            'employees' => $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->full_name,
                ];
            })
        ]);
    }
    //working
    public function generatePDFReport(Request $request)
    {
        // Validate the date range input and document type
        $request->validate([
            'upload_from_date' => 'required|date',
            'upload_to_date' => 'required|date',
            'document_type' => 'required|string|in:Travel Order,Office Order,Special Order', // Allow specific document types
        ]);
    
        // Fetch documents based on date range and document type (joined with document_types)
        $documents = Document::join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->where('document_types.document_type', $request->document_type) // Filter by document type name
            ->whereBetween('documents.created_at', [
                $request->upload_from_date,
                $request->upload_to_date,
            ])
            ->get();
    
        // Generate the PDF file
        $documentType = $request->document_type; // Pass this to the view
        $generatedDate = now()->format('Y-m-d');
        $description = $documentType . ' Report'; // Dynamic description based on document type
        $pdf = PDF::loadView('pdf.document_report', compact('documents', 'generatedDate', 'description', 'documentType'));
    
        // Set the file name and path for saving the PDF
        $fileName = strtolower(str_replace(' ', '_', $documentType)) . '_report_' . $generatedDate . '.pdf';
        $filePath = 'pdf_reports/' . $fileName;
    
        // Save the generated PDF to the storage folder
        $pdf->save(storage_path('app/public/' . $filePath));
    
        // Save the report details in the generated_reports table
        GeneratedReport::create([
            'fileName' => $fileName,
            'filePath' => $filePath,
            'description' => $documentType . ' report generated from ' . $request->upload_from_date . ' to ' . $request->upload_to_date,
        ]);
    
        // Return the response with the generated PDF file path or a success message
        return response()->json([
            'message' => $documentType . ' report generated and saved successfully!',
            'file_path' => asset('storage/' . $filePath),
        ]);
    }
    

    
//working 
public function getDocumentsByCreationDateRange(Request $request)
{
    // Validate the date range parameters (upload_from_date and upload_to_date)
    $request->validate([
        'upload_from_date' => 'required|date',
        'upload_to_date' => 'required|date',
        'document_type' => 'nullable|string|in:Travel Order,Office Order,Special Order', // Add validation for document_type
    ]);

    // Start building the query to fetch documents
    $query = Document::join('document_types', 'documents.document_type_id', '=', 'document_types.id')
        ->whereBetween('documents.created_at', [
            $request->upload_from_date,
            $request->upload_to_date,
        ]);

    // If document_type is provided, filter by document_type_id
    if ($request->has('document_type')) {
        $documentType = $request->document_type;
        $query->where('document_types.document_type', $documentType);
    }

    // Execute the query and fetch the documents
    $documents = $query->select('documents.*')->get();

    // Return the documents as JSON
    return response()->json(['documents' => $documents]);
}

    //working
    public function employeename()
    {
        // Retrieve employees with full name (adjusting for column names in camel case)
        $employees = Employee::select('id', 'firstName', 'lastName')
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->firstName . ' ' . $employee->lastName,
                ];
            });

        return response()->json(['employees' => $employees]);
    }
    public function index()
    {
        $reports = GeneratedReport::all(); // You can add pagination or filters if needed
        return response()->json($reports);
    }

    public function download($reportId)
    {
        try {
            // Retrieve the report from the database
            $report = Report::findOrFail($reportId);

            // Check if the file exists in the storage
            if (Storage::exists('public/pdf_reports/' . $report->file_path)) {
                return response()->download(storage_path('app/public/pdf_reports/' . $report->file_path));
            } else {
                return response()->json(['error' => 'File not found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error downloading the report.'], 500);
        }
    }
    //working
    public function destroy($id)
    {
        // Find the report by ID
        $report = GeneratedReport::find($id);

        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        // Try to delete the file if it exists
        if ($report->filePath && Storage::exists($report->filePath)) {
            try {
                // Forcefully delete the file
                Storage::delete($report->filePath);
                Log::info("File for report with ID {$id} deleted successfully.");
            } catch (\Exception $e) {
                Log::error("Error deleting file for report with ID {$id}: " . $e->getMessage());
                return response()->json(['message' => 'Error deleting file'], 500);
            }
        } else {
            Log::warning("No file found for report with ID {$id} or file path is invalid.");
        }

        // Force delete the report record from the database (bypassing soft deletes)
        try {
            $report->forceDelete(); // This will permanently delete the report from the database
            return response()->json(['message' => 'Report deleted successfully']);
        } catch (\Exception $e) {
            Log::error("Error force deleting report with ID {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Error deleting report record'], 500);
        }
    }
}
