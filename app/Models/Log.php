<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',    // ID of the user who performed the action
        'action',     // Action performed (e.g., 'Created Mail', 'Updated Mail', 'Deleted Mail')
        'details',    // Additional details (e.g., mail details)
        'user_full_name'
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}