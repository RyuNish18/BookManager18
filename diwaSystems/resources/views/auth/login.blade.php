@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">📚 Book Manager - Login</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="/login">
                    @csrf

                    <!-- Username Field -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text"
                               class="form-control @error('username') is-invalid @enderror"
                               id="username"
                               name="username"
                               value="{{ old('username') }}"
                               required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <!-- Demo Credentials -->
                <div class="mt-4">
                    <h6>Demo Credentials:</h6>
                    <small class="text-muted">
                        <strong>Teacher:</strong> username: teacher1, password: password123<br>
                        <strong>Student:</strong> username: student1, password: password123
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
