@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>Teacher Dashboard</h2>
        <p class="text-muted">Manage your books and assignments</p>

        <!-- Quick Actions -->
        <div class="mb-4">
            <a href="{{ route('books.create') }}" class="btn btn-primary me-2">
                ➕ Create New Book
            </a>
            <a href="{{ route('books.assign') }}" class="btn btn-success">
                📋 Assign Books to Students
            </a>
        </div>

        <!-- Books List -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Books ({{ $books->count() }})</h5>
            </div>
            <div class="card-body">
                @if($books->count() > 0)
                    <div class="row" id="books-container">
                        @foreach($books as $book)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 book-card">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $book->title }}</h6>
                                        <p class="card-text">{{ Str::limit($book->description, 100) }}</p>
                                        <small class="text-muted">
                                            Created: {{ $book->created_at->format('M d, Y') }}
                                        </small>
                                        <br>
                                        <small class="text-info">
                                            Assigned to {{ $book->assignedStudents->count() }} student(s)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">You haven't created any books yet.</p>
                        <a href="{{ route('books.create') }}" class="btn btn-primary">Create Your First Book</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Animate book cards on load
    $('.book-card').each(function(index) {
        $(this).delay(index * 100).fadeIn(500);
    });

    // Initially hide cards for animation
    $('.book-card').hide();
});
</script>
@endsection
