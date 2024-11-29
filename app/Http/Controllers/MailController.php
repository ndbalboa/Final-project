<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MailController extends Controller
{
    public function getEmployees()
    {
        return response()->json(Employee::select('id', 'firstName', 'lastName')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'to' => 'required|string|max:255',  // Validate the full name as a string
            'from' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:Very High,High,Normal,Low,Very Low',
            'status' => 'required|in:undelivered,delivered',
            'date_received' => 'nullable|date',
        ]);
    
        // Extract first and last names from the 'to' field to store them in the database
        $toFullName = $validated['to'];
    
        // Assuming you want to store just the full name as 'to' in your mail record
        Mail::create([
            'to' => $toFullName,  // Save the full name of the recipient
            'from' => $validated['from'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'date_received' => $validated['date_received'],
        ]);
    
        return response()->json(['message' => 'Mail recorded successfully!'], 201);
    }

    public function index(Request $request)
    {
        $query = Mail::query();

        if ($request->has('startDate') && $request->startDate) {
            $query->whereDate('date_received', '>=', $request->startDate);
        }

        if ($request->has('endDate') && $request->endDate) {
            $query->whereDate('date_received', '<=', $request->endDate);
        }

        if ($request->has('priority') && $request->priority) {
            $query->whereIn('priority', $request->priority);
        }

        if ($request->has('status') && $request->status) {
            $query->whereIn('status', $request->status);
        }

        $mails = $query->paginate($request->get('rows', 10));

        return response()->json($mails);
    }

    public function update(Request $request, $id)
    {
        $mail = Mail::findOrFail($id);
        $mail->update($request->all());

        return response()->json(['message' => 'Mail updated successfully']);
    }

    public function destroy($id)
    {
        Mail::findOrFail($id)->delete();

        return response()->json(['message' => 'Mail deleted successfully']);
    }
}