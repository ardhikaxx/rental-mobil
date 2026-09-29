@extends('layouts.app')

@section('title', 'Panduan Operasional Sistem')

@section('content')
    {{-- Header halaman panduan --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Panduan Penggunaan Sistem Rental Mobil</h1>
            <p class="text-muted-2 mb-0 small">
                Panduan operasional resmi sesuai hak akses peran Anda 
                (<span class="badge {{ $currentRole === 'super_admin' ? 'guide-badge-sa' : ($currentRole === 'admin' ? 'guide-badge-admin' : 'guide-badge-staff') }}">
                    {{ $currentUser->role->label() }}
                </span>).
                Pilih menu pada <strong>Daftar Isi</strong> untuk berpindah bagian panduan.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2" onclick="window.print()">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Panduan</span>
            </button>
        </div>
    </div>

    <div class="row g-4">
        {{-- Daftar isi (sticky) --}}
        <div class="col-lg-3">
            <div class="card guide-toc border-0 shadow-sm sticky-top" style="top: 75px; z-index: 10;">
                <div class="card-header bg-white py-3 border-bottom">
                    <span class="fw-bold small text-uppercase tracking-wider">
                        <i class="fa-solid fa-list-ul text-primary me-2"></i>Daftar Isi
                    </span>
                </div>
                <div class="card-body p-2" style="max-height: calc(100vh - 160px); overflow-y: auto;">
                    <ul class="nav flex-column guide-toc-list small" id="guideToc">
                        {{-- Umum --}}
                        <li class="guide-toc-divider" data-roles="super_admin admin staff">Penggunaan Umum</li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#umum">Tentang Panduan</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#login">Login &amp; Keamanan Akun</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#dashboard">Memahami Dashboard</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#siklus-rental">Siklus Rental Mobil</a>
                        </li>

                        {{-- Petugas Lapangan / Staff Garasi --}}
                        <li class="guide-toc-divider" data-roles="super_admin admin staff">Operasional Lapangan (Staff)</li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#staff-ringkasan">Wewenang Petugas Garasi</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#staff-pemeriksaan">Checklist Fisik Mobil</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#staff-serah-terima">Serah Terima (Handover)</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#staff-pengembalian">Pengembalian &amp; Denda</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#staff-pembersihan">Pembersihan Armada</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#staff-perawatan">Perawatan &amp; Servis</a>
                        </li>

                        {{-- Admin Operasional --}}
                        <li class="guide-toc-divider" data-roles="super_admin admin">Administrasi Rental (Admin)</li>
                        <li class="guide-toc-item" data-roles="super_admin admin">
                            <a class="guide-toc-link" href="#admin-ringkasan">Wewenang Admin Operasional</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin">
                            <a class="guide-toc-link" href="#admin-transaksi">Transaksi &amp; Approval</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin">
                            <a class="guide-toc-link" href="#admin-pelanggan">Verifikasi KTP &amp; SIM A</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin">
                            <a class="guide-toc-link" href="#admin-supir">Manajemen Supir / Driver</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin">
                            <a class="guide-toc-link" href="#admin-pembayaran">Pembayaran, DP &amp; Deposit</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin">
                            <a class="guide-toc-link" href="#admin-kalender">Kalender Booking Visual</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin">
                            <a class="guide-toc-link" href="#admin-cetak">Cetak SPK &amp; Invoice</a>
                        </li>

                        {{-- Super Admin --}}
                        @if($currentRole === 'super_admin')
                            <li class="guide-toc-divider" data-roles="super_admin">Super Admin</li>
                            <li class="guide-toc-item" data-roles="super_admin">
                                <a class="guide-toc-link" href="#sa-ringkasan">Wewenang Super Admin</a>
                            </li>
                            <li class="guide-toc-item" data-roles="super_admin">
                                <a class="guide-toc-link" href="#sa-pengguna">Manajemen Akun Pengguna</a>
                            </li>
                            <li class="guide-toc-item" data-roles="super_admin">
                                <a class="guide-toc-link" href="#sa-armada">Master Armada Kendaraan</a>
                            </li>
                            <li class="guide-toc-item" data-roles="super_admin">
                                <a class="guide-toc-link" href="#sa-pengaturan">Pengaturan Bisnis &amp; Denda</a>
                            </li>
                            <li class="guide-toc-item" data-roles="super_admin">
                                <a class="guide-toc-link" href="#sa-laporan">Laporan Omzet &amp; Ekspor</a>
                            </li>
                            <li class="guide-toc-item" data-roles="super_admin">
                                <a class="guide-toc-link" href="#sa-audit-log">Rekam Jejak Audit Log</a>
                            </li>
                        @endif

                        {{-- Lampiran & SOP --}}
                        <li class="guide-toc-divider" data-roles="super_admin admin staff">Lampiran &amp; SOP</li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#matriks-role">Matriks Hak Akses (RBAC)</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#sop-denda">SOP Denda &amp; Kerusakan</a>
                        </li>
                        <li class="guide-toc-item" data-roles="super_admin admin staff">
                            <a class="guide-toc-link" href="#faq">Tanya Jawab (FAQ)</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Isi Panduan: SATU section tampil pada satu waktu, berpindah via Daftar Isi --}}
        <div class="col-lg-9">
            @include('guide.partials.umum')
            @include('guide.partials.staff')
            @include('guide.partials.admin')
            @if($currentRole === 'super_admin')
                @include('guide.partials.superadmin')
            @endif
            @include('guide.partials.lampiran')

            {{-- Navigasi Sebelumnya / Berikutnya --}}
            <div id="guideSectionNav" class="d-flex justify-content-between align-items-center gap-2 mt-4 mb-5 pt-3 border-top"></div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .guide-toc-link {
        display: block;
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        color: var(--text, #1f2937);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.15s ease;
    }
    .guide-toc-link:hover {
        background: #f1f5f9;
        color: var(--accent, #2563eb);
    }
    .guide-toc-link.active {
        background: var(--accent, #2563eb);
        color: #ffffff;
        font-weight: 600;
    }
    .guide-toc-divider {
        color: #94a3b8;
        font-size: 0.68rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 700;
        padding: 0.9rem 0.65rem 0.3rem;
    }
    .guide-section {
        scroll-margin-top: 5rem;
    }
    .guide-section.role-hidden, 
    .guide-section.section-hidden,
    .guide-toc-item.role-hidden, 
    .guide-toc-divider.role-hidden {
        display: none !important;
    }
    .guide-badge-both {
        background: #16a34a;
        color: #ffffff;
    }
    .guide-badge-staff {
        background: #0284c7;
        color: #ffffff;
    }
    .guide-badge-admin {
        background: #d97706;
        color: #ffffff;
    }
    .guide-badge-sa {
        background: #7c3aed;
        color: #ffffff;
    }
    .text-purple {
        color: #7c3aed !important;
    }
    .guide-step {
        counter-reset: step;
        list-style: none;
        padding-left: 0;
    }
    .guide-step > li {
        position: relative;
        padding-left: 2.4rem;
        margin-bottom: 0.75rem;
        counter-increment: step;
        line-height: 1.55;
    }
    .guide-step > li::before {
        content: counter(step);
        position: absolute;
        left: 0;
        top: 0.1rem;
        width: 1.6rem;
        height: 1.6rem;
        border-radius: 50%;
        background: var(--accent, #2563eb);
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .guide-table th {
        background: #f8fafc;
        color: #1e293b;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
    }
    .guide-table td {
        font-size: 0.88rem;
        vertical-align: middle;
    }
    .guide-tip {
        border-left: 4px solid #d97706;
        background: #fffbeb;
        color: #92400e;
    }
    .guide-warn {
        border-left: 4px solid #dc2626;
        background: #fef2f2;
        color: #991b1b;
    }
    .guide-info {
        border-left: 4px solid #2563eb;
        background: #eff6ff;
        color: #1e40af;
    }
    .guide-ok {
        border-left: 4px solid #16a34a;
        background: #f0fdf4;
        color: #166534;
    }
    .guide-field-name {
        font-weight: 600;
        color: #1e293b;
    }
    .guide-a {
        color: var(--accent, #2563eb);
        text-decoration: none;
        font-weight: 600;
    }
    .guide-a:hover {
        text-decoration: underline;
    }
    .guide-menu-path {
        font-size: 0.82rem;
        color: #64748b;
        background: #f8fafc;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        display: inline-block;
    }
    @media print {
        .sidebar, .topbar, .btn-outline-secondary, #guideSectionNav, .guide-toc {
            display: none !important;
        }
        .main {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .guide-section.section-hidden {
            display: block !important;
        }
        .guide-section.role-hidden, 
        .guide-toc-item.role-hidden, 
        .guide-toc-divider.role-hidden {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            break-inside: avoid;
            margin-bottom: 1.5rem !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const MY_ROLE = @json($currentRole);
    const sections = Array.from(document.querySelectorAll('.guide-section'));
    const tocItems = Array.from(document.querySelectorAll('.guide-toc-item'));
    const tocDividers = Array.from(document.querySelectorAll('.guide-toc-divider'));
    const links = Array.from(document.querySelectorAll('.guide-toc-link'));
    const navBox = document.getElementById('guideSectionNav');

    function inRole(el) {
        return (el.dataset.roles || '').trim().split(/\s+/).includes(MY_ROLE);
    }

    // 1) Sembunyikan panduan yang bukan milik role yang sedang login
    sections.forEach(s => {
        if (!inRole(s)) {
            s.classList.add('role-hidden');
        }
    });
    tocItems.forEach(t => {
        if (!inRole(t)) {
            t.classList.add('role-hidden');
        }
    });

    // Sembunyikan divider jika tidak ada sub-item yang terlihat di bawahnya
    tocDividers.forEach(d => {
        if (!inRole(d)) {
            d.classList.add('role-hidden');
            return;
        }
        let visible = false;
        let n = d.nextElementSibling;
        while (n && !n.classList.contains('guide-toc-divider')) {
            if (n.classList.contains('guide-toc-item') && !n.classList.contains('role-hidden')) {
                visible = true;
                break;
            }
            n = n.nextElementSibling;
        }
        d.classList.toggle('role-hidden', !visible);
    });

    // Urutan section yang aktif untuk role ini
    const order = tocItems
        .filter(t => !t.classList.contains('role-hidden'))
        .map(t => t.querySelector('.guide-toc-link')?.getAttribute('href')?.slice(1))
        .filter(id => id && document.getElementById(id));

    function labelOf(id) {
        const a = links.find(l => l.getAttribute('href') === '#' + id);
        return a ? a.textContent.trim() : id;
    }

    function renderNav(currentId) {
        if (!navBox) return;
        const i = order.indexOf(currentId);
        const prev = i > 0 ? order[i - 1] : null;
        const next = i >= 0 && i < order.length - 1 ? order[i + 1] : null;
        navBox.innerHTML =
            (prev
                ? '<button type="button" class="btn btn-outline-secondary btn-sm guide-nav-btn" data-target="' + prev + '"><i class="fa-solid fa-arrow-left me-1"></i> Sebelumnya: ' + labelOf(prev) + '</button>'
                : '<span></span>') +
            '<span class="small text-muted fw-semibold">' + (i + 1) + ' / ' + order.length + ' bagian</span>' +
            (next
                ? '<button type="button" class="btn btn-primary btn-sm guide-nav-btn" data-target="' + next + '">Berikutnya: ' + labelOf(next) + ' <i class="fa-solid fa-arrow-right ms-1"></i></button>'
                : '<span></span>');
    }

    function showSection(id, scroll) {
        const target = document.getElementById(id);
        if (!target || target.classList.contains('role-hidden')) return;

        sections.forEach(s => s.classList.toggle('section-hidden', s !== target));
        links.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + id));
        renderNav(id);

        if (scroll) {
            const main = document.querySelector('main.content') || document.body;
            const top = main.getBoundingClientRect().top + window.scrollY - 70;
            window.scrollTo({ top: Math.max(top, 0), behavior: 'smooth' });
        }
    }

    // Klik Daftar Isi = beralih section
    links.forEach(a => a.addEventListener('click', function (e) {
        e.preventDefault();
        const id = this.getAttribute('href').slice(1);
        history.pushState(null, '', '#' + id);
        showSection(id, true);
    }));

    // Navigasi Sebelumnya / Berikutnya
    navBox?.addEventListener('click', function (e) {
        const btn = e.target.closest('.guide-nav-btn');
        if (btn && btn.dataset.target) {
            history.pushState(null, '', '#' + btn.dataset.target);
            showSection(btn.dataset.target, true);
        }
    });

    // Handle back / forward tombol browser
    window.addEventListener('popstate', function () {
        const fromHash = location.hash.slice(1);
        if (fromHash && order.includes(fromHash)) {
            showSection(fromHash, false);
        }
    });

    // Section awal saat pertama kali dibuka
    const fromHash = location.hash.slice(1);
    const initial = fromHash && order.includes(fromHash) ? fromHash : order[0];
    if (initial) {
        showSection(initial, false);
    }
})();
</script>
@endpush
