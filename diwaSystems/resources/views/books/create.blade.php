@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Create New Book</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('books.store') }}" id="book-form">
                    @csrf

                    <!-- Title Field -->
                    <div class="form-floating mb-3">
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               placeholder="Book Title"
                               value="{{ old('title') }}"
                               required>
                        <label for="title">Book Title *</label>
                        @error('title')
                            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div class="form-floating mb-3">
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  placeholder="Description"
                                  style="height: 120px"
                                  maxlength="500"
                                  required>{{ old('description') }}</textarea>
                        <label for="description">Description *</label>
                        @error('description')
                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                            Create Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Form submission with loading state
    $('#book-form').on('submit', function() {
        var submitBtn = $('#submit-btn');
        var spinner = submitBtn.find('.spinner-border');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>Creating...');
    });

    // Real-time title validation
    $('#title').on('input', function() {
        var title = $(this).val().trim();
        if (title.length >= 3) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else if (title.length > 0) {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });

    // Real-time description validation
    $('#description').on('input', function() {
        var description = $(this).val().trim();
        if (description.length >= 10) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else if (description.length > 0) {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });
});
</script>
@endsection
