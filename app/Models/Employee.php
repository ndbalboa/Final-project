<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes

class Employee extends Model
{
    use HasFactory, SoftDeletes; // Use SoftDeletes trait

    protected $fillable = [
        'lastName',
        'firstName',
        'middleName',
        'sex',
        'civilStatus',
        'dateOfBirth',
        'religion',
        'emailAddress',
        'phoneNumber',
        'gsisId',
        'pagibigId',
        'philhealthId',
        'sssNo',
        'agencyEmploymentNo',
        'taxId',
        'academicRank',
        'universityPosition',
        'profileImage',
        // Permanent Address
        'permanent_street',
        'permanent_barangay',
        'permanent_city',
        'permanent_province',
        'permanent_country',
        'permanent_zipcode',
        // Residential Address
        'residential_street',
        'residential_barangay',
        'residential_city',
        'residential_province',
        'residential_country',
        'residential_zipcode',

        'department',
    ];

    // Specify that deleted_at should be managed by soft deletes
    protected $dates = ['deleted_at']; 

    public function getNameAttribute()
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    // Relationship with documents, considering that employee_names is stored as JSON
    public function documents()
    {
        // Modify the query if needed, based on how you relate documents and employees
        return $this->hasMany(Document::class, 'employee_names', 'firstName', 'lastName'); // Change 'firstName' to a suitable column if necessary
    }
    public function mails()
    {
        return $this->hasMany(Mail::class, 'to', 'id');
    }
}
