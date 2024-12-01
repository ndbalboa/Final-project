<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicRank;

class AcademicRankController extends Controller
{
    // Method to create a new academic rank
    public function store(Request $request)
    {
        $request->validate([
            'rank' => 'required|string|unique:academic_ranks,rank|max:255',
        ]);

        try {
            $academicRank = AcademicRank::create([
                'rank' => $request->rank,
            ]);

            return response()->json([
                'message' => 'Academic rank created successfully.',
                'academic_rank' => $academicRank,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create academic rank. Please try again.',
            ], 500);
        }
    }

    // Method to fetch all academic ranks
    public function index()
    {
        $academicRanks = AcademicRank::all();
        return response()->json(['academicRanks' => $academicRanks]);
    }

    // Method to update an academic rank
    public function update(Request $request, $id)
    {
        $academicRank = AcademicRank::findOrFail($id);

        $request->validate([
            'rank' => 'required|string|unique:academic_ranks,rank,' . $id . '|max:255',
        ]);

        try {
            $academicRank->update([
                'rank' => $request->rank,
            ]);

            return response()->json([
                'message' => 'Academic rank updated successfully.',
                'academic_rank' => $academicRank,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update academic rank. Please try again.',
            ], 500);
        }
    }

    // Method to delete an academic rank
    public function destroy($id)
    {
        try {
            $academicRank = AcademicRank::findOrFail($id);
            $academicRank->delete();

            return response()->json([
                'message' => 'Academic rank deleted successfully.',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete academic rank. Please try again.',
            ], 500);
        }
    }
}
