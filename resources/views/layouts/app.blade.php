<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>

    {{-- Bootstrap via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome via CDN --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    {{-- Custom styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="app-shell">
    @include('partials.sidebar')

    <div class="main">
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="sidebar-toggle" data-sidebar-toggle aria-label="Buka menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="page-title">@yield('title', 'Dashboard')</h1>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-md-inline text-muted-2" style="font-size:.83rem">
                    <i class="fa-regular fa-clock me-1"></i>{{ now()->translatedFormat('d M Y') }}
                </span>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm d-flex align-items-center gap-2 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <span class="avatar-circle" style="width:26px;height:26px;font-size:.72rem">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text small text-muted-2">{{ auth()->user()->role->label() }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" data-confirm="Keluar dari sistem sekarang?">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="content">
            @yield('content')
        </main>
    </div>
</div>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

{{-- SweetAlert2 via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Bootstrap JS via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.SWAL_FLASH = {
        success: @json(session('success')),
        error: @json(session('error')),
    };
    window.SWAL_ERRORS = @json($errors->all());
</script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
