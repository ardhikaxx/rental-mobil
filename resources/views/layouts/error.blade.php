<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Terjadi Kesalahan') — {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="error-wrap">
    <div>
        <div class="error-code">@yield('code', '404')</div>
        <h1 class="h4 fw-semibold mt-3">@yield('heading', 'Halaman tidak ditemukan')</h1>
        <p class="text-muted-2 mx-auto" style="max-width:420px">@yield('message', 'Halaman yang Anda minta tidak tersedia.')</p>
        <div class="mt-4">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fa-solid fa-gauge-high me-1"></i> Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Ke Halaman Login
                </a>
            @endauth
        </div>
    </div>
</div>
</body>
</html>
