<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_no', 
        'series_no',
        'date_issued', 
        'from_date', 
        'to_date', 
        'subject', 
        'description',
        'venue',
        'destination',
        'document_type_id', 
        'file_path', 
        'employee_names',
        'student_names',
        'created_at'
    ];

    protected $casts = [
        'employee_names' => 'array',
        'student_names' => 'array',

    ];
    public function scopeWithinDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_issued', [$startDate, $endDate]);
    }

    public function documentEmployeeNames()
    {
        return $this->hasMany(DocumentEmployeeName::class);
    }
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'document_employee', 'document_id', 'employee_id');
    }
    public static function forUser($user, $type = null)
    {
        $fullName = $user->firstName . ' ' . $user->lastName;

        $query = self::whereJsonContains('employee_names', $fullName);
        
        if ($type) {
            $query->where('document_type', $type);
        }

        return $query->get();
    }
    public static function byType($type)
    {
        return self::where('document_type', $type)->get();
    }
    public function scopeForUser($query, $user)
    {
        return $query->whereJsonContains('employee_names', $user->employee_id); // Adjust based on how employee_names is structured
    }
    public function scopeFilterByEmployee($query, $employeeId)
    {
        if ($employeeId) {
            $employee = Employee::find($employeeId);
            if ($employee) {
                $fullName = "{$employee->lastName}, {$employee->firstName}";
                return $query->whereJsonContains('employee_names', $fullName);
            }
        }
        return $query;
    }
    public function documentType()
{
    return $this->belongsTo(DocumentType::class, 'document_type_id');
}
public function employee()
{
    return $this->belongsTo(Employee::class);
}



    
}

