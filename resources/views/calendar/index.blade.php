@extends('layouts.app')

@section('title', 'Kalender Booking')

@section('content')
    <x-page-header
        title="Kalender Booking"
        subtitle="Jadwal rental, serah terima, dan pengembalian berdasarkan data transaksi nyata armada Jaya Trans." />

    <!-- Filter Bar -->
    <div class="card border mb-3 shadow-sm">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-4 col-sm-6">
                    <label for="filterStatus" class="form-label small fw-semibold text-muted-2 mb-1">
                        <i class="fa-solid fa-filter me-1"></i>Filter Status Transaksi
                    </label>
                    <select id="filterStatus" class="form-select form-select-sm">
                        <option value="">Semua Status Operasional</option>
                        @foreach ($statuses as $val => $lbl)
                            <option value="{{ $val }}">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 col-sm-6">
                    <label for="filterVehicle" class="form-label small fw-semibold text-muted-2 mb-1">
                        <i class="fa-solid fa-car me-1"></i>Filter Kendaraan Armada
                    </label>
                    <select id="filterVehicle" class="form-select form-select-sm">
                        <option value="">Semua Unit Kendaraan ({{ $vehicles->count() }} unit)</option>
                        @foreach ($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->code }} — {{ $v->brand }} {{ $v->model }} ({{ $v->license_plate }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-12 d-flex align-items-end gap-2 mt-auto">
                    <button type="button" id="btnResetFilter" class="btn btn-outline-secondary btn-sm flex-grow-1">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter
                    </button>
                    <button type="button" id="btnRefreshCalendar" class="btn btn-light border btn-sm" title="Segarkan Kalender">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="mb-3 d-flex gap-3 flex-wrap align-items-center bg-white p-2 rounded border" style="font-size:.84rem">
        <span class="text-muted fw-semibold me-1"><i class="fa-solid fa-palette me-1"></i>Status:</span>
        <span class="d-inline-flex align-items-center"><span class="legend-dot" style="background:#d97706"></span>Menunggu Pembayaran</span>
        <span class="d-inline-flex align-items-center"><span class="legend-dot" style="background:#2563eb"></span>Disetujui</span>
        <span class="d-inline-flex align-items-center"><span class="legend-dot" style="background:#0369a1"></span>Siap Diserahkan</span>
        <span class="d-inline-flex align-items-center"><span class="legend-dot" style="background:#0f766e"></span>Sedang Disewa</span>
        <span class="d-inline-flex align-items-center"><span class="legend-dot" style="background:#16a34a"></span>Selesai</span>
    </div>

    <!-- Calendar Container -->
    <div class="calendar-wrap shadow-sm bg-white">
        <div id="bookingCalendar"></div>
    </div>

    <!-- Event Detail Modal -->
    <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header pb-2">
                    <h5 class="modal-title fs-6 fw-bold" id="eventDetailModalLabel">
                        <i class="fa-solid fa-calendar-check text-primary me-2"></i>Rincian Jadwal Booking
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <div>
                            <div class="text-muted small">Nomor Transaksi</div>
                            <div class="fw-bold fs-6 text-primary" id="modalTrxNumber">-</div>
                        </div>
                        <div>
                            <span class="badge" id="modalTrxStatus">-</span>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="p-2 rounded bg-light border">
                                <div class="text-muted small"><i class="fa-solid fa-user me-1"></i>Penyewa</div>
                                <div class="fw-semibold text-truncate" id="modalCustomer">-</div>
                                <div class="small text-muted" id="modalCustomerPhone">-</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded bg-light border">
                                <div class="text-muted small"><i class="fa-solid fa-car me-1"></i>Armada Mobil</div>
                                <div class="fw-semibold text-truncate" id="modalVehicle">-</div>
                                <div class="small text-muted" id="modalDriver">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="p-2 rounded bg-light border">
                            <div class="text-muted small mb-1"><i class="fa-solid fa-clock me-1"></i>Periode Sewa Rental</div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="badge bg-white text-dark border">Mulai</span>
                                    <span class="fw-semibold ms-1" id="modalStart">-</span>
                                </div>
                                <i class="fa-solid fa-arrow-right text-muted mx-2"></i>
                                <div>
                                    <span class="badge bg-white text-dark border">Selesai</span>
                                    <span class="fw-semibold ms-1" id="modalEnd">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-2 rounded bg-primary-subtle text-primary border border-primary-subtle">
                        <span class="small fw-semibold">Total Biaya Rental</span>
                        <span class="fw-bold fs-6" id="modalTotal">Rp 0</span>
                    </div>
                </div>
                <div class="modal-footer pt-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <a href="#" id="modalTrxLink" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-up-right-from-square me-1"></i>Buka Transaksi Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .fc {
            --fc-border-color: #e2e8f0;
            --fc-button-text-color: #334155;
            --fc-button-bg-color: #f8fafc;
            --fc-button-border-color: #cbd5e1;
            --fc-button-hover-bg-color: #f1f5f9;
            --fc-button-hover-border-color: #94a3b8;
            --fc-button-active-bg-color: #0d6efd;
            --fc-button-active-border-color: #0d6efd;
            --fc-event-border-color: transparent;
            font-family: inherit;
        }
        .fc .fc-toolbar-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
        }
        .fc .fc-button {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            font-weight: 500;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            color: #fff !important;
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }
        .fc .fc-event {
            border-radius: 4px;
            padding: 2px 5px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.1s ease, box-shadow 0.1s ease;
        }
        .fc .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .fc .fc-daygrid-day-number {
            font-weight: 600;
            color: #475569;
            padding: 4px 8px;
        }
        .fc .fc-col-header-cell-cushion {
            font-weight: 700;
            color: #334155;
            padding: 6px 0;
            text-transform: capitalize;
        }
        .fc-day-today {
            background: #eff6ff !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Local FullCalendar JS with CDN fallback -->
    <script src="{{ asset('js/fullcalendar.min.js') }}"></script>
    <script>
        if (typeof FullCalendar === 'undefined') {
            document.write('<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"><\/script>');
        }
    </script>
    <script src="{{ asset('js/fullcalendar.id.js') }}"></script>
    <script>
        if (typeof FullCalendar !== 'undefined' && !FullCalendar.globalLocales) {
            document.write('<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/id.global.min.js"><\/script>');
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('bookingCalendar');
            if (!calendarEl || typeof FullCalendar === 'undefined') return;

            const filterStatus = document.getElementById('filterStatus');
            const filterVehicle = document.getElementById('filterVehicle');
            const btnReset = document.getElementById('btnResetFilter');
            const btnRefresh = document.getElementById('btnRefreshCalendar');

            // Modal elements
            const modalEl = document.getElementById('eventDetailModal');
            const bsModal = modalEl && typeof bootstrap !== 'undefined' ? new bootstrap.Modal(modalEl) : null;
            const modalTrxNumber = document.getElementById('modalTrxNumber');
            const modalTrxStatus = document.getElementById('modalTrxStatus');
            const modalCustomer = document.getElementById('modalCustomer');
            const modalCustomerPhone = document.getElementById('modalCustomerPhone');
            const modalVehicle = document.getElementById('modalVehicle');
            const modalDriver = document.getElementById('modalDriver');
            const modalStart = document.getElementById('modalStart');
            const modalEnd = document.getElementById('modalEnd');
            const modalTotal = document.getElementById('modalTotal');
            const modalTrxLink = document.getElementById('modalTrxLink');

            const statusColors = {
                'awaiting_payment': { bg: '#d97706', badge: 'bg-warning text-dark' },
                'booked': { bg: '#2563eb', badge: 'bg-primary' },
                'ready_for_handover': { bg: '#0369a1', badge: 'bg-info text-white' },
                'rented': { bg: '#0f766e', badge: 'bg-teal text-white' },
                'completed': { bg: '#16a34a', badge: 'bg-success' },
            };

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
                initialDate: '{{ now()->format('Y-m-d') }}',
                locale: 'id',
                height: 'auto',
                firstDay: 1, // Senin
                navLinks: true,
                nowIndicator: true,
                displayEventEnd: true,
                dayMaxEvents: 4,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth',
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    list: 'Daftar'
                },
                events: function (info, successCallback, failureCallback) {
                    const statusVal = filterStatus ? filterStatus.value : '';
                    const vehicleVal = filterVehicle ? filterVehicle.value : '';

                    const params = new URLSearchParams({
                        start: info.startStr,
                        end: info.endStr,
                    });
                    if (statusVal) params.append('status', statusVal);
                    if (vehicleVal) params.append('vehicle_id', vehicleVal);

                    fetch(@json(route('calendar.events')) + '?' + params.toString())
                        .then(function (res) {
                            if (!res.ok) throw new Error('HTTP ' + res.status);
                            return res.json();
                        })
                        .then(function (data) {
                            // Support both direct array and { events: [...] } object
                            const events = Array.isArray(data) ? data : (data.events || []);
                            successCallback(events);
                        })
                        .catch(function (err) {
                            console.error('Gagal mengambil data jadwal kalender:', err);
                            failureCallback(err);
                        });
                },
                eventClick: function (info) {
                    info.jsEvent.preventDefault();

                    const event = info.event;
                    const props = event.extendedProps || {};

                    if (bsModal) {
                        modalTrxNumber.textContent = props.transaction_number || event.title;
                        modalTrxStatus.textContent = props.status_label || '-';
                        modalTrxStatus.style.backgroundColor = event.backgroundColor || '#2563eb';
                        modalTrxStatus.style.color = '#fff';

                        modalCustomer.textContent = props.customer_name || '-';
                        modalCustomerPhone.textContent = props.customer_phone ? 'Telp: ' + props.customer_phone : '-';
                        modalVehicle.textContent = props.vehicle || '-';
                        modalDriver.textContent = props.driver || '-';
                        modalStart.textContent = props.start_formatted || event.startStr;
                        modalEnd.textContent = props.end_formatted || (event.endStr || '-');
                        modalTotal.textContent = props.total || '-';
                        modalTrxLink.href = event.url || '#';

                        bsModal.show();
                    } else if (event.url) {
                        window.location.href = event.url;
                    }
                }
            });

            calendar.render();

            // Filter event listeners
            if (filterStatus) {
                filterStatus.addEventListener('change', function () {
                    calendar.refetchEvents();
                });
            }

            if (filterVehicle) {
                filterVehicle.addEventListener('change', function () {
                    calendar.refetchEvents();
                });
            }

            if (btnReset) {
                btnReset.addEventListener('click', function () {
                    if (filterStatus) filterStatus.value = '';
                    if (filterVehicle) filterVehicle.value = '';
                    calendar.refetchEvents();
                });
            }

            if (btnRefresh) {
                btnRefresh.addEventListener('click', function () {
                    calendar.refetchEvents();
                });
            }
        });
    </script>
@endpush
