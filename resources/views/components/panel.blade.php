@props(['title' => null, 'flush' => false])

<div {{ $attributes->merge(['class' => 'panel']) }}>
    @if ($title || isset($actions))
        <div class="panel-header">
            <h2>{{ $title }}</h2>
            @isset($actions)
                <div class="d-flex gap-2 flex-wrap">{{ $actions }}</div>
            @endisset
        </div>
    @endif
    <div class="panel-body {{ $flush ? 'panel-body-flush' : '' }}">
        {{ $slot }}
    </div>
</div>
