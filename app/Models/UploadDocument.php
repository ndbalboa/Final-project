<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadDocument extends Model
{
    use HasFactory;

    protected $table = 'uploaddocuments'; // Explicitly define the table name

    protected $fillable = ['document_type_id', 'uploaded_file', 'field_data'];

    // Cast the `field_data` attribute to an array
    protected $casts = [
        'field_data' => 'array',
    ];

    // Relationship with DocumentType
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}

