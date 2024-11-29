<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Document;
use App\Models\User;
use App\Models\DocumentEmployeeName; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees with user relationship.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $employees = Employee::with('user')->paginate(10); // Paginate 10 employees per page
        return response()->json($employees);
    }

    /**
     * Store a newly created employee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Store Request Data: ', $request->all());

        $validator = Validator::make($request->all(), $this->getValidationRules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = $this->getValidatedData($request);

        // Handle profile image upload
        if ($request->hasFile('profileImage')) {
            $data['profileImage'] = $this->storeProfileImage($request);
        }

        $employee = Employee::create($data);

        return response()->json(['message' => 'Employee added successfully', 'employee' => $employee], 201);
    }

    /**
     * Display the specified employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $employee = Employee::with('user')->find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        return response()->json(['employee' => $employee]);
    }

    /**
     * Update the specified employee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->update($request->all()); // Update employee with new data
    
            // Handle profile image upload if any
            if ($request->hasFile('profileImage')) {
                $imagePath = $request->file('profileImage')->store('profile_images', 'public');
                $employee->profile_image = $imagePath;
                $employee->save();
            }
    
            return response()->json(['employee' => $employee], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }    
    /**
     * Register unregistered employee and associate with document.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerUnregisteredEmployee(Request $request)
    {
        // Validate the request for unregistered employee information
        $validatedData = $request->validate([
            'document_id' => 'required|exists:documents,id',
            'employee_name' => 'required|string',
            'gender' => 'nullable|in:male,female',
            'civil_status' => 'nullable|string',
            'email' => 'nullable|string|email|unique:employees,emailAddress',
            'religion' => 'nullable|string|max:255',
        ]);

        // Extract the name and split into first name and last name
        $names = explode(' ', $validatedData['employee_name']);
        $firstName = $names[0] ?? null;
        $lastName = end($names);

        // Check if the employee already exists
        $existingEmployee = Employee::where('firstName', $firstName)
            ->where('lastName', $lastName)
            ->first();

        if ($existingEmployee) {
            return response()->json(['error' => 'Employee already exists.'], 400);
        }

        // Add the new employee to the employees table
        $newEmployee = Employee::create([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'gender' => $validatedData['gender'],
            'civilStatus' => $validatedData['civil_status'],
            'emailAddress' => $validatedData['email'],
            'religion' => $validatedData['religion'],
        ]);

        // Remove the unregistered employee from the document_employee_names table
        DocumentEmployeeName::where('document_id', $validatedData['document_id'])
            ->where('employee_name', $validatedData['employee_name'])
            ->delete();

        // Associate the new employee with the document
        $document = Document::find($validatedData['document_id']);
        if ($document) {
            $document->employees()->attach($newEmployee->id);
        }

        return response()->json([
            'message' => 'Employee registered and associated with document successfully.',
            'employee' => $newEmployee,
        ]);
    }

    /**
     * Get validation rules for employee creation and updating.
     *
     * @param  int|null $employeeId
     * @return array
     */
    protected function getValidationRules($employeeId = null)
    {
        return [
            'lastName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'sex' => 'required|string|max:10',
            'civilStatus' => 'required|string|max:20',
            'dateOfBirth' => 'required|date',
            'religion' => 'nullable|string|max:255',
            'emailAddress' => 'required|email|max:255|unique:employees,emailAddress,' . ($employeeId ?? 'NULL'),
            'phoneNumber' => 'required|string|max:15|unique:employees,phoneNumber,' . ($employeeId ?? 'NULL'),
            'gsisId' => 'nullable|string|max:255|unique:employees,gsisId,' . ($employeeId ?? 'NULL'),
            'pagibigId' => 'nullable|string|max:255|unique:employees,pagibigId,' . ($employeeId ?? 'NULL'),
            'philhealthId' => 'nullable|string|max:255|unique:employees,philhealthId,' . ($employeeId ?? 'NULL'),
            'sssNo' => 'nullable|string|max:255|unique:employees,sssNo,' . ($employeeId ?? 'NULL'),
            'agencyEmploymentNo' => 'nullable|string|max:255|unique:employees,agencyEmploymentNo,' . ($employeeId ?? 'NULL'),
            'taxId' => 'nullable|string|max:255|unique:employees,taxId,' . ($employeeId ?? 'NULL'),
            'academicRank' => 'required|string|max:50',
            'universityPosition' => 'required|string|max:50',
            'profileImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'permanent_street' => 'nullable|string|max:255',
            'permanent_barangay' => 'nullable|string|max:255',
            'permanent_city' => 'nullable|string|max:255',
            'permanent_province' => 'nullable|string|max:255',
            'permanent_country' => 'nullable|string|max:255',
            'permanent_zipcode' => 'nullable|string|max:20',
            'residential_street' => 'nullable|string|max:255',
            'residential_barangay' => 'nullable|string|max:255',
            'residential_city' => 'nullable|string|max:255',
            'residential_province' => 'nullable|string|max:255',
            'residential_country' => 'nullable|string|max:255',
            'residential_zipcode' => 'nullable|string|max:20',
        ];
    }
//true function for saving on add new employee
    /**
     * Get validated data from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getValidatedData(Request $request)
    {
        return $request->only([
            'lastName',
            'firstName',
            'middleName',
            'sex',
            'civilStatus',
            'dateOfBirth',
            'religion',
            'emailAddress',
            'phoneNumber',
            'gsisId',
            'pagibigId',
            'philhealthId',
            'sssNo',
            'agencyEmploymentNo',
            'taxId',
            'academicRank',
            'universityPosition',
            'permanent_street',
            'permanent_barangay',
            'permanent_city',
            'permanent_province',
            'permanent_country',
            'permanent_zipcode',
            'residential_street',
            'residential_barangay',
            'residential_city',
            'residential_province',
            'residential_country',
            'residential_zipcode',
            'department'
        ]);
    }

    /**
     * Handle profile image storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function storeProfileImage(Request $request)
    {
        return $request->file('profileImage')->store('profile_images', 'public');
    }

    /**
     * Fetch authenticated user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchUserProfile()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    /**
     * Update the authenticated user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserProfile(Request $request)
    {
        try {
            $validatedData = $request->validate($this->getValidationRules());

            $user = Auth::user();
            $user->update($validatedData);

            return response()->json($user);

        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to update profile'], 500);
        }
    }

    /**
     * Upload a profile image for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'profileImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profileImage')) {
            $file = $request->file('profileImage');
            $path = $file->store('profile_images', 'public');
            
            $user = Auth::user();
            $user->profileImage = $path;
            $user->save();

            return response()->json(['profileImage' => $path]);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Deactivate the employee
        $employee->is_active = false;
        $employee->save();

        // Also deactivate the associated user account
        if ($employee->user) {
            $employee->user->is_active = false;
            $employee->user->save();
        }

        return response()->json(['message' => 'Employee and associated user account have been deactivated.']);
    }

        public function deactivateEmployee($employeeId)
    {
        // Find the employee by ID
        $employee = Employee::findOrFail($employeeId);
        
        // Soft delete the employee
        $employee->delete();

        // Find the associated user by employee_id
        $user = User::where('employee_id', $employeeId)->first();

        if ($user) {
            // Permanently delete the associated user account
            $user->forceDelete();
        }

        return response()->json([
            'message' => 'Employee has been deactivated and associated user account has been permanently deleted.'
        ]);
    }

    public function getDeactivatedEmployees()
{
    try {
        $deactivatedEmployees = Employee::onlyTrashed()->with('user')->get();
        return response()->json($deactivatedEmployees, 200);
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error("Error fetching deactivated employees: " . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch deactivated employees'], 500);
    }
}
public function restore($id)
{
    // Find the employee with soft-deleted status
    $employee = Employee::withTrashed()->find($id);
    
    if ($employee) {
        // Restore the employee record
        $employee->restore();
        
        // Restore the user account associated with the employee
        $user = User::where('employee_id', $id)->first();
        
        if ($user) {
            $user->status = 'active'; // Set user account status to active
            $user->save(); // Save the changes
        }

        return response()->json(['message' => 'Employee and user account restored successfully.']);
    }

    return response()->json(['message' => 'Employee not found.'], 404);
}
    public function forceDelete($id)
    {
        $employee = Employee::withTrashed()->find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $employee->forceDelete();

        return response()->json(['message' => 'Employee permanently deleted successfully']);
    }

    public function forceDeleteEmployee($id)
    {
        try {
            $employee = Employee::withTrashed()->findOrFail($id);
            $employee->forceDelete();
            return response()->json(['message' => 'Employee permanently deleted.']);
        } catch (\Exception $e) {
            \Log::error('Error permanently deleting employee: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to permanently delete employee.'], 500);
        }
    }
    public function getDocuments($employeeId)
    {
        $documents = Document::where('employee_id', $employeeId)->get();
        return response()->json($documents);
    }

     // Fetch details of a specific employee
     public function view($id)
     {
         $employee = Employee::findOrFail($id);
         return response()->json(['employee' => $employee]);
     }
 
     // Fetch all documents associated with the employee
     public function documents($id)
    {
        $employee = Employee::findOrFail($id);
        
        // Normalize the employee's full name to lowercase
        $employeeName = strtolower($employee->firstName . ' ' . $employee->lastName);

        // Fetch documents where the employee name is stored in the employee_names JSON field
        $documents = Document::where(function ($query) use ($employeeName) {
            // Use JSON_CONTAINS with a case-insensitive comparison
            $query->whereJsonContains('employee_names', $employeeName)
                ->orWhere(function ($query) use ($employeeName) {
                    // Add a fallback to handle possible capital letters
                    $query->whereRaw('LOWER(employee_names) LIKE ?', "%{$employeeName}%");
                });
        })->get();

        return response()->json(['documents' => $documents]);
    }

    public function getEmployeeDocuments()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Combine first name and last name as they are stored in employee_names
        $fullName = $user->firstName . ' ' . $user->lastName;

        // Query documents where the employee_names field contains the full name
        $documents = Document::whereJsonContains('employee_names', $fullName)->get();

        return response()->json($documents);
    }


}
