<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicRank extends Model
{
    protected $fillable = ['rank'];

    // Optionally, if you want to customize the table name
    protected $table = 'academic_ranks';
    
}
