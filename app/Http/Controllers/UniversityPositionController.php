<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UniversityPosition;
use App\Http\Controllers\Controller;

class UniversityPositionController extends Controller
{
    // Method to create a new university position
    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required|string|unique:university_positions,position|max:255',
        ]);

        try {
            $universityPosition = UniversityPosition::create([
                'position' => $request->position,
            ]);

            return response()->json([
                'message' => 'University position created successfully.',
                'university_position' => $universityPosition,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create university position. Please try again.',
            ], 500);
        }
    }

    // Method to fetch all university positions
    public function index()
    {
        $universityPositions = UniversityPosition::all();
        return response()->json(['universityPositions' => $universityPositions]);
    }

    // Method to update a university position
    public function update(Request $request, $id)
    {
        $universityPosition = UniversityPosition::findOrFail($id);

        $request->validate([
            'position' => 'required|string|unique:university_positions,position,' . $id . '|max:255',
        ]);

        try {
            $universityPosition->update([
                'position' => $request->position,
            ]);

            return response()->json([
                'message' => 'University position updated successfully.',
                'university_position' => $universityPosition,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update university position. Please try again.',
            ], 500);
        }
    }

    // Method to delete a university position
    public function destroy($id)
    {
        try {
            $universityPosition = UniversityPosition::findOrFail($id);
            $universityPosition->delete();

            return response()->json([
                'message' => 'University position deleted successfully.',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete university position. Please try again.',
            ], 500);
        }
    }
}
