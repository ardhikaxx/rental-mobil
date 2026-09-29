@props(['items' => []])

@if (count($items) > 0)
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3" style="font-size:.82rem">
            @foreach ($items as $index => $item)
                @if ($index === count($items) - 1 || empty($item['url']))
                    <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
