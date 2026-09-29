@php($menu = \App\Support\Navigation::for(auth()->user()))

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <span class="brand-icon"><i class="fa-solid fa-car-side"></i></span>
        <span>{{ config('app.name') }}</span>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-label">Menu Utama</div>
        @foreach ($menu as $item)
            <a href="{{ route($item['route']) }}"
               class="menu-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                <i class="fa-solid {{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="sidebar-user">
        <span class="avatar">{{ substr(auth()->user()->name, 0, 1) }}</span>
        <div class="user-meta">
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">{{ auth()->user()->role->label() }}</div>
        </div>
    </div>

    <div class="sidebar-footer">
        <span class="sidebar-footer-name">{{ config('app.author.name') }}</span>
        <span class="sidebar-footer-copyright">© {{ now()->year }} {{ config('app.author.name') }}</span>
    </div>
</aside>
