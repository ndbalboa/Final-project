<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentEmployeeName extends Model
{
    protected $fillable = ['document_id', 'employee_name'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
