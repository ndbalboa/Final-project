<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    // Specify the table if it's not the plural form of the model name
    protected $table = 'document_types';

    // Fillable fields for mass assignment
    protected $fillable = [
        'document_type',
    ];

    // Define the relationship to the Document model
    public function documents()
    {
        return $this->hasMany(Document::class, 'document_type_id');
    }
    public function scopeForUser(Builder $query, $user, $type = null)
    {
        return $query->whereJsonContains('employee_names', [['lastName' => $user->lastName, 'firstName' => $user->firstName]])
                     ->when($type, function ($q) use ($type) {
                         $q->whereHas('documentType', function ($query) use ($type) {
                             $query->where('document_type', $type);
                         });
                     });
    }

}
