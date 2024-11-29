<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mail;
use App\Models\Employee;
use App\Models\Document;
use App\Models\Log;
use Carbon\Carbon;

class LogController extends Controller
{
    public function getRecentActivities()
    {
        $today = Carbon::today(); // Use Carbon for date manipulation
    
        // Fetch recent activities with user information
        $activities = Log::with('user') // Include user relationship
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Count today's login actions
        $loginCountToday = $activities->filter(function ($log) use ($today) {
            return strpos($log->action, 'login') !== false && 
                Carbon::parse($log->created_at)->isToday();
        })->count();
    
        return response()->json([
            'activities' => $activities,
            'login_count_today' => $loginCountToday,
        ]);
    }
}
