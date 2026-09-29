<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') — {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="guest-shell">
    @yield('content')

    <footer class="app-credit">
        © {{ now()->year }}
        <a href="{{ config('app.author.url') }}" target="_blank" rel="noopener">{{ config('app.author.name') }}</a>
        &#64;{{ config('app.author.username') }}
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.SWAL_FLASH = {
        success: @json(session('success')),
        error: @json(session('error')),
    };
    window.SWAL_ERRORS = @json($errors->all());
</script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
