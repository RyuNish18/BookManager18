<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\AssignedBook;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or find users using firstOrCreate to avoid duplicates
        $teacher = User::firstOrCreate(
            ['username' => 'teacher1'],
            [
                'password' => 'password123',
                'role' => 'teacher',
            ]
        );

        $student1 = User::firstOrCreate(
            ['username' => 'student1'],
            [
                'password' => 'password123',
                'role' => 'student',
            ]
        );

        $student2 = User::firstOrCreate(
            ['username' => 'student2'],
            [
                'password' => 'password123',
                'role' => 'student',
            ]
        );

        // Create books only if they don't exist
        $book1 = Book::firstOrCreate(
            [
                'title' => 'Introduction to Laravel',
                'created_by' => $teacher->id
            ],
            [
                'description' => 'A comprehensive guide to getting started with Laravel framework.',
            ]
        );

        $book2 = Book::firstOrCreate(
            [
                'title' => 'PHP Best Practices',
                'created_by' => $teacher->id
            ],
            [
                'description' => 'Learn the best practices for writing clean and maintainable PHP code.',
            ]
        );

        $book3 = Book::firstOrCreate(
            [
                'title' => 'Database Design Fundamentals',
                'created_by' => $teacher->id
            ],
            [
                'description' => 'Understanding the principles of good database design and normalization.',
            ]
        );

        // Assign books safely (won't create duplicates due to unique constraint)
        $assignments = [
            ['book_id' => $book1->id, 'student_id' => $student1->id, 'assigned_by' => $teacher->id],
            ['book_id' => $book2->id, 'student_id' => $student1->id, 'assigned_by' => $teacher->id],
            ['book_id' => $book1->id, 'student_id' => $student2->id, 'assigned_by' => $teacher->id],
            ['book_id' => $book3->id, 'student_id' => $student2->id, 'assigned_by' => $teacher->id],
        ];

        foreach ($assignments as $assignment) {
            AssignedBook::firstOrCreate($assignment);
        }
    }
}
