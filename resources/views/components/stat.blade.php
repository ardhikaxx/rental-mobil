@props(['icon' => 'fa-circle', 'label', 'value', 'sub' => null])

<div {{ $attributes->merge(['class' => 'stat']) }}>
    <span class="stat-icon"><i class="fa-solid {{ $icon }}"></i></span>
    <div>
        <div class="stat-value">{{ $value }}</div>
        <div class="stat-label">{{ $label }}</div>
        @if ($sub)
            <div class="stat-label">{!! $sub !!}</div>
        @endif
    </div>
</div>
