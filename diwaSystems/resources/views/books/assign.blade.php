@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assign Books to Students</h5>
            </div>
            <div class="card-body">
                @if($books->count() > 0 && $students->count() > 0)
                    <form method="POST" action="{{ route('books.assign.store') }}">
                        @csrf

                        <!-- Book Selection -->
                        <div class="mb-3">
                            <label for="book_id" class="form-label">Select Book *</label>
                            <select class="form-select @error('book_id') is-invalid @enderror"
                                    id="book_id"
                                    name="book_id"
                                    required>
                                <option value="">Choose a book...</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                        {{ $book->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <div class="invalid-feedback">{{ $errors->first('book_id') }}</div>
                            @enderror
                        </div>

                        <!-- Student Selection -->
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Select Student *</label>
                            <select class="form-select @error('student_id') is-invalid @enderror"
                                    id="student_id"
                                    name="student_id"
                                    required>
                                <option value="">Choose a student...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->username }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $errors->first('student_id') }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                            <button type="submit" class="btn btn-success">Assign Book</button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-4">
                        @if($books->count() == 0)
                            <p class="text-muted">You need to create some books first before you can assign them.</p>
                            <a href="{{ route('books.create') }}" class="btn btn-primary">Create Your First Book</a>
                        @elseif($students->count() == 0)
                            <p class="text-muted">No students are available for assignment.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
