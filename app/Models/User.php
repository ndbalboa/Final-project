<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'lastName',
        'firstName',
        'email',
        'password',
        'role',
        'employee_id',
        'status',
        'department'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    //added function can be deleted
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
    public function document()
    {
        $fullName = $this->firstName . ' ' . $this->lastName;
        return Document::whereJsonContains('employee_names', $fullName)->get();
    }
    public function documents($type = null)
    {
        return Document::forUser($this, $type);
    }
    public function userDocuments()
    {
        $user = Auth::user();
        return $this->documents()->where('employee_id', $user->employee_id); // Limit documents to the logged-in user
    }
}
