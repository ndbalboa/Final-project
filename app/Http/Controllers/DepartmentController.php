<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

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

    public function getDocumentTypeCounts(Request $request)
{
    // Get the authenticated user's department
    $department = auth()->user()->department;

    if (!$department) {
        return response()->json(['error' => 'User does not belong to a department'], 403);
    }

    // Fetch employee names from the specified department
    $employeeNames = Employee::where('department', $department)
        ->pluck(\DB::raw('CONCAT(firstName, " ", lastName)'))
        ->toArray();

    // Fetch documents associated with these employee names
    $documents = Document::where(function ($query) use ($employeeNames) {
        foreach ($employeeNames as $name) {
            $query->orWhereJsonContains('employee_names', $name);
        }
    })->get();

    // Count the documents per document type
    $documentTypeCounts = $documents->groupBy('document_type_id')
        ->map(function ($group) {
            return $group->count();
        });

    // Fetch the document type names for the counts
    $documentTypes = DocumentType::whereIn('id', $documentTypeCounts->keys())->pluck('document_type', 'id');

    // Prepare response data with document type names
    $result = $documentTypeCounts->mapWithKeys(function ($count, $typeId) use ($documentTypes) {
        return [$documentTypes[$typeId] => $count];
    });

    return response()->json(['documentTypeCounts' => $result]);
}
public function getDepartmentAccounts()
{
    try {
        // Get all users where role is 'department'
        $users = User::where('role', 'department')->get();
        return response()->json($users, 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error fetching department accounts', 'error' => $e->getMessage()], 500);
    }
}

// Method to fetch all department accounts
    public function fetchDepartments(): JsonResponse
    {
        $users = User::where('role', 'department')->get();
        return response()->json($users);
    }

    // Method to show details of a single department account
    public function showDepartment(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'department') {
            return response()->json(['error' => 'User not found or not a department account'], 404);
        }
        
        return response()->json($user);
    }

    // Method to delete a department account
    public function deleteAccount($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Check if the current user is trying to delete their own account
            if ($user->id === auth()->id()) {
                return response()->json(['message' => 'You cannot delete your own account'], 400);
            }

            $user->delete();
            return response()->json(['message' => 'Account deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the account', 'error' => $e->getMessage()], 500);
        }
    }



    
}
