@extends('layouts.app')

@section('title', 'Kalender Booking')

@section('content')
    <x-page-header
        title="Kalender Booking"
        subtitle="Jadwal rental, serah terima, dan pengembalian berdasarkan data transaksi nyata." />

    <div class="mb-3 d-flex gap-3 flex-wrap" style="font-size:.84rem">
        <span><span class="legend-dot" style="background:#d97706"></span>Menunggu Pembayaran</span>
        <span><span class="legend-dot" style="background:#2563eb"></span>Disetujui</span>
        <span><span class="legend-dot" style="background:#0369a1"></span>Siap Diserahkan</span>
        <span><span class="legend-dot" style="background:#0f766e"></span>Sedang Disewa</span>
        <span><span class="legend-dot" style="background:#16a34a"></span>Selesai</span>
    </div>

    <div class="calendar-wrap">
        <div id="bookingCalendar"></div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/id.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('bookingCalendar');
            if (!calendarEl || typeof FullCalendar === 'undefined') return;

            new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
                locale: 'id',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek',
                },
                buttonText: { today: 'Hari Ini', month: 'Bulan', week: 'Minggu' },
                events: @json(route('calendar.events')),
                displayEventEnd: true,
                navLinks: true,
                nowIndicator: true,
            }).render();
        });
    </script>
@endpush
