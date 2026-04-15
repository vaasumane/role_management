<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        base_url = '<?php echo config('app.url') ?>';
    </script>
</head>

<body>

    <!-- Navbar -->
    @if(session('user') && session('user')->id)
    @include('layout.partials.navbar')
    @endif
    <div class="container-fluid mt-4">
        @yield('content')
    </div>


    @stack('scripts')
</body>

</html>