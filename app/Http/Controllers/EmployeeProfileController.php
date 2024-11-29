<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeProfileController extends Controller
{
    public function show()
    {
        // Get the authenticated user's employee profile
        $employee = Auth::user()->employee;
        return response()->json($employee);
    }

    public function update(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female,Other',
            'civilStatus' => 'required|string|max:50',
            'dateOfBirth' => 'required|date',
            'religion' => 'nullable|string|max:100',
            'emailAddress' => 'required|email|unique:employees,emailAddress,' . Auth::user()->employee->id,
            'phoneNumber' => 'required|unique:employees,phoneNumber,' . Auth::user()->employee->id,
            'gsisId' => 'nullable|string|unique:employees,gsisId,' . Auth::user()->employee->id,
            'pagibigId' => 'nullable|string|unique:employees,pagibigId,' . Auth::user()->employee->id,
            'philhealthId' => 'nullable|string|unique:employees,philhealthId,' . Auth::user()->employee->id,
            'sssNo' => 'nullable|string|unique:employees,sssNo,' . Auth::user()->employee->id,
            'agencyEmploymentNo' => 'nullable|string|unique:employees,agencyEmploymentNo,' . Auth::user()->employee->id,
            'taxId' => 'nullable|string|unique:employees,taxId,' . Auth::user()->employee->id,
            'academicRank' => 'required|string|max:100',
            'universityPosition' => 'required|string|max:100',

            // Permanent Address
            'permanent_street' => 'nullable|string|max:255',
            'permanent_barangay' => 'nullable|string|max:255',
            'permanent_city' => 'nullable|string|max:255',
            'permanent_province' => 'nullable|string|max:255',
            'permanent_country' => 'nullable|string|max:255',
            'permanent_zipcode' => 'nullable|string|max:20',

            // Residential Address
            'residential_street' => 'nullable|string|max:255',
            'residential_barangay' => 'nullable|string|max:255',
            'residential_city' => 'nullable|string|max:255',
            'residential_province' => 'nullable|string|max:255',
            'residential_country' => 'nullable|string|max:255',
            'residential_zipcode' => 'nullable|string|max:20',

            'profileImage' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $employee = $user->employee;

        // Update the employee's information excluding the profile image
        $employee->update($request->except('profileImage'));

        // Handle profile image upload if a new one is provided
        if ($request->hasFile('profileImage')) {
            if ($employee->profileImage) {
                Storage::delete('public/profile_images/' . $employee->profileImage);
            }

            $imagePath = $request->file('profileImage')->store('public/profile_images');
            $employee->profileImage = basename($imagePath);
        }

        $employee->save();

        // Return a success response with a message
        return response()->json([
            'message' => 'Profile updated successfully',
            'employee' => $employee
        ]);
    }
}
