@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="auth-wrap">
        <div class="auth-card">
            <span class="auth-icon"><i class="fa-solid fa-car-side"></i></span>
            <h1>{{ config('app.name') }}</h1>
            <p class="auth-sub">Sistem operasional rental mobil. Masuk menggunakan akun Anda.</p>

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf

                <x-input
                    name="username"
                    label="Username"
                    value="{{ old('username') }}"
                    required
                    placeholder="username akun Anda"
                    autofocus />

                <x-input
                    name="password"
                    label="Password"
                    type="password"
                    required
                    placeholder="password akun Anda" />

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                    <label class="form-check-label" for="remember" style="font-size:.86rem">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk
                </button>
            </form>

            <p class="text-muted-2 text-center mt-4 mb-0" style="font-size:.78rem">
                <i class="fa-solid fa-shield-halved me-1"></i>
                Akses terbatas untuk karyawan {{ config('app.name') }}.
            </p>
        </div>
    </div>
@endsection
