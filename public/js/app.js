/**
 * Rental Mobil Nusantara — application scripts.
 * All feedback (success, error, validation, confirmations) uses SweetAlert2.
 */
document.addEventListener('DOMContentLoaded', function () {
    initSidebar();
    initFlash();
    initConfirmations();
    initLateFeePreview();
    initPasswordToggles();
});

/* ------------------------------- Sidebar -------------------------------- */

function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const toggles = document.querySelectorAll('[data-sidebar-toggle]');

    if (!sidebar) return;

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            if (backdrop) backdrop.classList.toggle('show');
        });
    });

    if (backdrop) {
        backdrop.addEventListener('click', function () {
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
        });
    }
}

/* --------------------------- Flash messages ----------------------------- */

function initFlash() {
    const flash = window.SWAL_FLASH || {};
    const errors = window.SWAL_ERRORS || [];

    if (errors.length > 0) {
        const list = document.createElement('ul');
        list.style.margin = '0';
        list.style.paddingLeft = '1.1rem';
        list.style.textAlign = 'left';
        errors.forEach(function (message) {
            const item = document.createElement('li');
            item.textContent = message;
            item.style.marginBottom = '.25rem';
            list.appendChild(item);
        });

        Swal.fire({
            icon: 'error',
            title: 'Validasi gagal',
            html: list.outerHTML,
            confirmButtonColor: '#2563eb',
        });
        return;
    }

    if (flash.success) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: flash.success,
            confirmButtonColor: '#2563eb',
            timer: 3500,
            showConfirmButton: true,
        });
        return;
    }

    if (flash.error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: flash.error,
            confirmButtonColor: '#2563eb',
        });
    }
}

/* -------------------------- Confirmations ------------------------------- */

function initConfirmations() {
    document.querySelectorAll('[data-confirm]').forEach(function (element) {
        const handler = function (event) {
            const form = element.closest('form');

            if (element.dataset.confirmed === '1') return;

            event.preventDefault();
            event.stopPropagation();

            Swal.fire({
                icon: element.dataset.confirmIcon || 'warning',
                title: element.dataset.confirmTitle || 'Konfirmasi',
                text: element.dataset.confirm,
                showCancelButton: true,
                confirmButtonText: element.dataset.confirmButton || 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
            }).then(function (result) {
                if (!result.isConfirmed) return;

                if (element.tagName === 'A' && element.href) {
                    window.location.href = element.href;
                    return;
                }

                if (element.tagName === 'BUTTON' && form) {
                    // Re-trigger the click: the handler short-circuits on the flag
                    // and the browser submits including the button's name/value.
                    element.dataset.confirmed = '1';
                    element.click();
                    return;
                }

                if (form) {
                    form.dataset.confirmed = '1';
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        };

        if (element.tagName === 'FORM') {
            element.addEventListener('submit', function (event) {
                if (element.dataset.confirmed === '1') return;
                handler(event);
            });
        } else {
            element.addEventListener('click', handler);
        }
    });
}

/* --------------------- Password visibility toggle ----------------------- */

function initPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.passwordToggle);
            if (!input) return;

            const revealed = input.type === 'password';
            input.type = revealed ? 'text' : 'password';

            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !revealed);
                icon.classList.toggle('fa-eye-slash', revealed);
            }

            button.setAttribute('aria-label', revealed ? 'Sembunyikan password' : 'Tampilkan password');
            button.setAttribute('aria-pressed', revealed ? 'true' : 'false');

            input.focus({ preventScroll: true });
        });
    });
}

/* --------------------- Late fee preview (return form) ------------------- */

function initLateFeePreview() {
    const form = document.querySelector('[data-late-fee-form]');
    if (!form) return;

    const dueAt = form.dataset.dueAt;
    const grace = parseInt(form.dataset.grace || '0', 10);
    const mode = form.dataset.mode || 'per_hour';
    const rate = parseInt(form.dataset.rate || '0', 10);
    const input = form.querySelector('[name="actual_return_at"]');
    const box = document.getElementById('lateFeePreview');
    if (!input || !box) return;

    function recalculate() {
        if (!input.value) {
            box.innerHTML = '';
            return;
        }

        const due = new Date(dueAt.replace(' ', 'T'));
        const actual = new Date(input.value.replace(' ', 'T'));

        if (isNaN(due.getTime()) || isNaN(actual.getTime()) || actual <= due) {
            box.innerHTML = '<span class="badge badge-success">Kembali tepat waktu</span>';
            return;
        }

        let minutes = Math.round((actual - due) / 60000) - grace;

        if (minutes <= 0) {
            box.innerHTML = '<span class="badge badge-success">Dalam masa tenggang</span>';
            return;
        }

        const units = mode === 'per_day' ? Math.ceil(minutes / 1440) : Math.ceil(minutes / 60);
        const fee = units * rate;
        const label = minutes < 60
            ? minutes + ' menit terlambat'
            : (minutes < 1440 ? Math.ceil(minutes / 60) + ' jam terlambat' : Math.ceil(minutes / 1440) + ' hari terlambat');

        box.innerHTML =
            '<span class="badge badge-warning">' + label + '</span> ' +
            '<span class="text-muted-2">denda <strong>Rp ' + fee.toLocaleString('id-ID') + '</strong></span>';
    }

    input.addEventListener('change', recalculate);
    input.addEventListener('input', recalculate);
    recalculate();
}
