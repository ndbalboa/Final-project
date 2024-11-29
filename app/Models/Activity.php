<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'activities';

    // The attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'description',
        'created_at',
    ];

    // Optionally, you can add relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
