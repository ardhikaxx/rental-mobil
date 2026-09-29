@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'options' => [],
    'hint' => null,
    'required' => false,
    'placeholder' => null,
])

@php
    $isPassword = $type === 'password';
    $old = $isPassword ? null : old($name, $value);
    $error = $errors->first($name);

    if ($type === 'datetime-local' && is_string($old) && $old !== '') {
        $old = str_replace(' ', 'T', $old);
    }

    if ($type === 'date' && $old instanceof \Illuminate\Support\Carbon) {
        $old = $old->toDateString();
    }
@endphp

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    <label class="form-label" for="{{ $name }}">
        {{ $label }}
        @if ($required)<span class="text-danger">*</span>@endif
    </label>

    @if ($type === 'select')
        <select name="{{ $name }}"
                id="{{ $name }}"
                class="form-select @if ($error) is-invalid @endif"
                @if ($required) required @endif>
            @if ($placeholder !== null)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $old === (string) $optionValue)>{{ $optionLabel }}</option>
            @endforeach
        </select>
    @elseif ($type === 'textarea')
        <textarea name="{{ $name }}"
                  id="{{ $name }}"
                  rows="3"
                  class="form-control @if ($error) is-invalid @endif"
                  @if ($placeholder) placeholder="{{ $placeholder }}" @endif>{{ $old }}</textarea>
    @elseif ($isPassword)
        <div class="input-group @if ($error) has-validation @endif">
            <input type="password"
                   name="{{ $name }}"
                   id="{{ $name }}"
                   class="form-control @if ($error) is-invalid @endif"
                   @if ($required) required @endif
                   @if ($placeholder) placeholder="{{ $placeholder }}" @endif>

            <button type="button"
                    class="btn password-toggle"
                    data-password-toggle="{{ $name }}"
                    aria-label="Tampilkan password"
                    aria-pressed="false">
                <i class="fa-regular fa-eye" aria-hidden="true"></i>
            </button>

            @if ($error)
                <div class="invalid-feedback">{{ $error }}</div>
            @endif
        </div>
    @else
        <input type="{{ $type }}"
               name="{{ $name }}"
               id="{{ $name }}"
               value="{{ $old }}"
               class="form-control @if ($error) is-invalid @endif"
               @if ($required) required @endif
               @if ($placeholder) placeholder="{{ $placeholder }}" @endif>
    @endif

    @if ($hint)
        <div class="form-hint">{!! $hint !!}</div>
    @endif

    @if ($error && ! $isPassword)
        <div class="invalid-feedback">{{ $error }}</div>
    @endif
</div>
