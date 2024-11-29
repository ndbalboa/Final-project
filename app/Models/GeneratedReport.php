<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Document;
use App\Models\Employee;

class GeneratedReport extends Model
{
    use HasFactory;

    protected $table = 'generated_reports';  // Define table name if it doesn't follow the convention

    protected $fillable = [
        'fileName',
        'filePath',
        'description',
    ];

    public $timestamps = true;  

    // Assuming filePath is the path to the report file stored in storage
    public function getFilePathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
