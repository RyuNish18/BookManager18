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
                    <form method="POST" action="{{ route('books.assign.store') }}" id="assign-form">
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
                                    <option value="{{ $book->id }}"
                                            data-description="{{ $book->description }}"
                                            {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                        {{ $book->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <div class="invalid-feedback">{{ $errors->first('book_id') }}</div>
                            @enderror
                        </div>

                        <!-- Book Preview -->
                        <div id="book-preview" class="mb-3" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">📖 Book Preview:</h6>
                                    <p class="card-text" id="book-description"></p>
                                </div>
                            </div>
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

                        <!-- Assignment Preview -->
                        <div id="assignment-preview" class="mb-3" style="display: none;">
                            <div class="alert alert-info">
                                <strong>Ready to assign:</strong> "<span id="preview-book"></span>" to "<span id="preview-student"></span>"
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                            <button type="submit" class="btn btn-success" id="assign-btn">
                                Assign Book
                            </button>
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

<script>
$(document).ready(function() {
    // Show book preview when book is selected
    $('#book_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var description = selectedOption.data('description');

        if (description && $(this).val() !== '') {
            $('#book-description').text(description);
            $('#book-preview').slideDown(300);
        } else {
            $('#book-preview').slideUp(300);
        }

        updateAssignmentPreview();
    });

    // Update assignment preview when student is selected
    $('#student_id').on('change', function() {
        updateAssignmentPreview();
    });

    // Update assignment preview
    function updateAssignmentPreview() {
        var bookText = $('#book_id option:selected').text();
        var studentText = $('#student_id option:selected').text();
        var bookValue = $('#book_id').val();
        var studentValue = $('#student_id').val();

        if (bookValue && studentValue && bookText !== 'Choose a book...' && studentText !== 'Choose a student...') {
            $('#preview-book').text(bookText);
            $('#preview-student').text(studentText);
            $('#assignment-preview').slideDown(300);
        } else {
            $('#assignment-preview').slideUp(300);
        }
    }

    // Form submission with loading state (but don't prevent submission)
    $('#assign-form').on('submit', function(e) {
        var bookValue = $('#book_id').val();
        var studentValue = $('#student_id').val();

        // Only prevent if no selections made
        if (!bookValue || !studentValue) {
            e.preventDefault();
            alert('Please select both a book and a student.');
            return false;
        }

        // Show loading state but allow form to submit
        var submitBtn = $('#assign-btn');
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>Assigning...');

        // Allow the form to submit normally
        return true;
    });
});
</script>
@endsection
