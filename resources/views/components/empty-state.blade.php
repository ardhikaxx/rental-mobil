@props(['icon' => 'fa-inbox', 'title' => 'Belum ada data', 'text' => ''])

<div class="empty-state">
    <i class="fa-solid {{ $icon }}"></i>
    <div class="empty-title">{{ $title }}</div>
    @if ($text)
        <div class="empty-text">{{ $text }}</div>
    @endif
    @isset($action)
        <div>{{ $action }}</div>
    @endisset
</div>
