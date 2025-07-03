<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .book-card:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-floating label {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">📚 Book Manager</a>

            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, {{ auth()->user()->username }} ({{ ucfirst(auth()->user()->role) }})
                </span>

                <!-- Teacher-specific navigation -->
                @if(auth()->user()->isTeacher())
                    <a class="nav-link me-2" href="{{ route('books.create') }}">Create Book</a>
                    <a class="nav-link me-2" href="{{ route('books.assign') }}">Assign Books</a>
                @endif

                <!-- Logout form -->
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm" onclick="return confirm('Are you sure you want to logout?')">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Flash Messages -->
        <div id="flash-messages">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Auto-hide flash messages after 4 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 4000);

            // Add fade-in animation to main content
            $('.container').addClass('fade-in');

            // Smooth form validation feedback
            $('input, textarea, select').on('blur', function() {
                if ($(this).is(':invalid') && $(this).val() !== '') {
                    $(this).addClass('is-invalid');
                } else if ($(this).is(':valid') && $(this).val() !== '') {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // Character counter for textareas
            $('textarea').each(function() {
                var maxLength = $(this).attr('maxlength');
                if (maxLength) {
                    var counter = $('<small class="text-muted float-end">0/' + maxLength + '</small>');
                    $(this).after(counter);

                    $(this).on('input', function() {
                        var length = $(this).val().length;
                        counter.text(length + '/' + maxLength);

                        if (length > maxLength * 0.9) {
                            counter.removeClass('text-muted').addClass('text-warning');
                        } else {
                            counter.removeClass('text-warning').addClass('text-muted');
                        }
                    });
                }
            });

            // Smooth scroll to top button (appears when scrolling down)
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    if (!$('#scroll-top').length) {
                        $('body').append('<button id="scroll-top" class="btn btn-primary position-fixed" style="bottom: 20px; right: 20px; z-index: 1000; border-radius: 50%; width: 50px; height: 50px;">↑</button>');
                    }
                    $('#scroll-top').fadeIn();
                } else {
                    $('#scroll-top').fadeOut();
                }
            });

            // Scroll to top functionality
            $(document).on('click', '#scroll-top', function() {
                $('html, body').animate({scrollTop: 0}, 500);
            });
        });
    </script>
</body>
</html>
