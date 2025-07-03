<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedBook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'book_id',
        'student_id',
        'assigned_by',
    ];

    /**
     * Get the book that was assigned
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the student who received the assignment
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the teacher who made the assignment
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
