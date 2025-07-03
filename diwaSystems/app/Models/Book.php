<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'created_by',
    ];

    /**
     * Get the teacher who created this book
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get students who have this book assigned
     */
    public function assignedStudents()
    {
        return $this->belongsToMany(User::class, 'assigned_books', 'book_id', 'student_id')
                    ->withPivot('assigned_by', 'created_at')
                    ->withTimestamps();
    }

    /**
     * Get all assignments for this book
     */
    public function assignments()
    {
        return $this->hasMany(AssignedBook::class, 'book_id');
    }
}
