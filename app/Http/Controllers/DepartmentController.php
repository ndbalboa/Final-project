<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function getDepartmentDocuments(Request $request)
    {
        // Assuming the user's department is stored in the `department` field
        $department = auth()->user()->department;
    
        if (!$department) {
            return response()->json(['error' => 'User does not belong to a department'], 403);
        }
    
        // Fetch employee names from the specified department
        $employeeNames = \App\Models\Employee::where('department', $department)
            ->pluck(\DB::raw('CONCAT(firstName, " ", lastName)'))
            ->toArray();
    
        // Fetch documents associated with these employee names
        $documents = \App\Models\Document::where(function ($query) use ($employeeNames) {
            foreach ($employeeNames as $name) {
                $query->orWhereJsonContains('employee_names', $name);
            }
        })->get();
    
        return response()->json($documents);
    }
    
    public function indexs()
    {
        $documentTypes = DocumentType::pluck('document_type'); // Fetch only document type names
        return response()->json($documentTypes);
    }
    public function getDepartmentDocumentTypes(Request $request)
    {
        // Get the authenticated user's department
        $department = auth()->user()->department;
    
        if (!$department) {
            return response()->json(['error' => 'User does not belong to a department'], 403);
        }
    
        // Validate and get the document type from the request
        $documentType = $request->query('document_type');
    
        // Fetch employee names from the specified department
        $employeeNames = Employee::where('department', $department)
            ->pluck(\DB::raw('CONCAT(firstName, " ", lastName)'))
            ->toArray();
    
        // Fetch documents associated with these employee names and filter by document type
        $documents = Document::where(function ($query) use ($employeeNames) {
            foreach ($employeeNames as $name) {
                $query->orWhereJsonContains('employee_names', $name);
            }
        })
        ->when($documentType, function ($query) use ($documentType) {
            // Ensure the query filters by document_type_id
            $query->where('document_type_id', $documentType);
        })
        ->get();
    
        return response()->json($documents);
    }
    
    
    
}
