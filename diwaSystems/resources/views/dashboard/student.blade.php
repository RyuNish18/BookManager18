@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>Student Dashboard</h2>
        <p class="text-muted">Your assigned books</p>

        <!-- Assigned Books -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Books Assigned to You ({{ $assignedBooks->count() }})</h5>
            </div>
            <div class="card-body">
                @if($assignedBooks->count() > 0)
                    <div class="row">
                        @foreach($assignedBooks as $book)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">📖 {{ $book->title }}</h6>
                                        <p class="card-text">{{ $book->description }}</p>
                                        <small class="text-muted">
                                            Assigned: {{ $book->pivot->created_at->format('M d, Y') }}
                                        </small>
                                        <br>
                                        <small class="text-info">
                                            By: {{ $book->creator->username }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">No books have been assigned to you yet.</p>
                        <p class="text-muted">Check back later or contact your teacher.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
