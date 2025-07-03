<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $bookData = [
            'title' => $request->title,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ];

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('book-covers', 'public');
            $bookData['cover_image'] = $imagePath;
        }

        // Create the book
        $book = Book::create($bookData);

        return redirect('/dashboard')->with('success', "Book '{$book->title}' created successfully!");
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
        $books = Auth::user()->createdBooks;
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
            if ($request->ajax()) {
                return response()->json(['message' => 'Access denied. Teachers only.'], 403);
            }
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
            $message = 'You can only assign books you created.';
            if ($request->ajax()) {
                return response()->json(['message' => $message], 403);
            }
            return back()->with('error', $message);
        }

        // Check if student role is correct
        $student = User::where('id', $request->student_id)
                      ->where('role', 'student')
                      ->first();

        if (!$student) {
            $message = 'Invalid student selected.';
            if ($request->ajax()) {
                return response()->json(['message' => $message], 400);
            }
            return back()->with('error', $message);
        }

        // Try to assign the book (will fail if already assigned due to unique constraint)
        try {
            $book->assignedStudents()->attach($request->student_id, [
                'assigned_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $message = "Book '{$book->title}' assigned to {$student->username} successfully!";

            if ($request->ajax()) {
                return response()->json([
                    'message' => $message,
                    'book' => $book->title,
                    'student' => $student->username
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            $message = 'This book is already assigned to this student.';
            if ($request->ajax()) {
                return response()->json(['message' => $message], 400);
            }
            return back()->with('error', $message);
        }
    }
}
