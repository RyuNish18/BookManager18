<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard based on user role
     */
    public function index()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Show different dashboard based on role
        if ($user->isTeacher()) {
            return $this->teacherDashboard();
        } else {
            return $this->studentDashboard();
        }
    }

    /**
     * Teacher dashboard - show their created books
     */
    private function teacherDashboard()
    {
        $books = Auth::user()->createdBooks()->latest()->get();
        return view('dashboard.teacher', compact('books'));
    }

    /**
     * Student dashboard - show assigned books
     */
    private function studentDashboard()
    {
        $assignedBooks = Auth::user()->assignedBooks()->latest('pivot_created_at')->get();
        return view('dashboard.student', compact('assignedBooks'));
    }
}
