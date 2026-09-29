@props(['kind' => 'transaction', 'value' => null])

@php
    $raw = is_object($value) ? $value->value : $value;

    $enum = match ($kind) {
        'vehicle' => \App\Enums\VehicleStatus::tryFrom((string) $raw),
        'transaction' => \App\Enums\TransactionStatus::tryFrom((string) $raw),
        'maintenance' => \App\Enums\MaintenanceStatus::tryFrom((string) $raw),
        'role' => \App\Enums\UserRole::tryFrom((string) $raw),
        'inspection' => \App\Enums\InspectionType::tryFrom((string) $raw),
        'condition' => \App\Enums\ConditionLevel::tryFrom((string) $raw),
        'fuel' => \App\Enums\FuelLevel::tryFrom((string) $raw),
        'payment_method' => \App\Enums\PaymentMethod::tryFrom((string) $raw),
        'payment_type' => \App\Enums\PaymentType::tryFrom((string) $raw),
        'booking_source' => \App\Enums\BookingSource::tryFrom((string) $raw),
        default => null,
    };

    $label = $enum?->label() ?? (string) $raw;
    $class = $enum !== null && method_exists($enum, 'badgeClass') ? $enum->badgeClass() : 'badge-secondary';
    $icon = $enum instanceof \App\Enums\InspectionType ? $enum->icon() : null;
@endphp

<span {{ $attributes->merge(['class' => 'badge '.$class]) }}>
    @if ($icon)<i class="fa-solid {{ $icon }}"></i>@endif
    {{ $label }}
</span>
