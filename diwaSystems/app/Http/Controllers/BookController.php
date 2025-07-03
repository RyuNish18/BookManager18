<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Show form to create a new book (teachers only)
     */
    public function create()
    {
        // Check if user is logged in and is a teacher
        if (!Auth::check() || !Auth::user()->isTeacher()) {
            return redirect('/dashboard')->with('error', 'Access denied. Teachers only.');
        }

        return view('books.create');
    }

    /**
     * Store a new book (teachers only)
     */
    public function store(Request $request)
    {
        // Check if user is logged in and is a teacher
        if (!Auth::check() || !Auth::user()->isTeacher()) {
            return redirect('/dashboard')->with('error', 'Access denied. Teachers only.');
        }

        // Validate the form data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Create the book
        Book::create([
            'title' => $request->title,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return redirect('/dashboard')->with('success', 'Book created successfully!');
    }

    /**
     * Show form to assign books to students (teachers only)
     */
    public function showAssignForm()
    {
        // Check if user is logged in and is a teacher
        if (!Auth::check() || !Auth::user()->isTeacher()) {
            return redirect('/dashboard')->with('error', 'Access denied. Teachers only.');
        }

        // Get teacher's books and all students
        $books = Auth::user()->createdBooks()->get();
        $students = User::where('role', 'student')->get();

        return view('books.assign', compact('books', 'students'));
    }

    /**
     * Assign a book to a student (teachers only)
     */
    public function assign(Request $request)
    {
        // Check if user is logged in and is a teacher
        if (!Auth::check() || !Auth::user()->isTeacher()) {
            return redirect('/dashboard')->with('error', 'Access denied. Teachers only.');
        }

        // Validate the form data
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|exists:users,id',
        ]);

        // Check if the book belongs to this teacher
        $book = Book::where('id', $request->book_id)
                   ->where('created_by', Auth::id())
                   ->first();

        if (!$book) {
            return back()->with('error', 'You can only assign books you created.');
        }

        // Check if student role is correct
        $student = User::where('id', $request->student_id)
                      ->where('role', 'student')
                      ->first();

        if (!$student) {
            return back()->with('error', 'Invalid student selected.');
        }

        // Try to assign the book (will fail if already assigned due to unique constraint)
        try {
            $book->assignedStudents()->attach($request->student_id, [
                'assigned_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', "Book '{$book->title}' assigned to {$student->username} successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'This book is already assigned to this student.');
        }
    }
}
