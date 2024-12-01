<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
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
    
    public function store(Request $request)
    {
        $request->validate([
            'department' => 'required|string|unique:departments,department|max:255',
        ]);

        try {
            $department = Department::create([
                'department' => $request->department,
            ]);

            return response()->json([
                'message' => 'Department created successfully.',
                'department' => $department,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create department. Please try again.',
            ], 500);
        }
    }

    // Method to fetch all departments
    public function index()
    {
        $departments = Department::all();
        return response()->json(['departments' => $departments]);
    }

    // Method to update an existing department
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'department' => 'required|string|unique:departments,department,' . $id . '|max:255',
        ]);

        try {
            $department->update([
                'department' => $request->department,
            ]);

            return response()->json([
                'message' => 'Department updated successfully.',
                'department' => $department,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update department. Please try again.',
            ], 500);
        }
    }

    // Method to delete a department
    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();

            return response()->json([
                'message' => 'Department deleted successfully.',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete department. Please try again.',
            ], 500);
        }
    }
    
}
