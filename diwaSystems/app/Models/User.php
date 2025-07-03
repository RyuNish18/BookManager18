<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is a teacher
     */
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is a student
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }

    /**
     * Books created by this user (for teachers)
     */
    public function createdBooks()
    {
        return $this->hasMany(Book::class, 'created_by');
    }

    /**
     * Books assigned to this user (for students)
     */
    public function assignedBooks()
    {
        return $this->belongsToMany(Book::class, 'assigned_books', 'student_id', 'book_id')
                    ->withPivot('assigned_by', 'created_at')
                    ->withTimestamps();
    }

    /**
     * Assignments made by this user (for teachers)
     */
    public function madeAssignments()
    {
        return $this->hasMany(AssignedBook::class, 'assigned_by');
    }
}
