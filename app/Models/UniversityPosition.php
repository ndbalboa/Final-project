<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
    ];

    // Relationship with the Employee model
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
