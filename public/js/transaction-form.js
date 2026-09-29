/**
 * Live rental cost calculator used by transaction create/edit forms.
 * The server always re-validates these numbers — this is only a preview.
 */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('transactionCostForm');
    if (!form) return;

    const rates = JSON.parse(form.dataset.rates || '{}');
    const startInput = form.querySelector('[name="start_at"]');
    const endInput = form.querySelector('[name="end_at"]');
    const vehicleSelect = form.querySelector('[name="vehicle_id"]');
    const discountInput = form.querySelector('[name="discount"]');
    const dpInput = form.querySelector('[name="dp_amount"]');

    function formatRp(value) {
        return 'Rp ' + Number(value).toLocaleString('id-ID');
    }

    function recalculate() {
        const rate = vehicleSelect ? Number(rates[vehicleSelect.value] || 0) : Number(form.dataset.rate || 0);
        let days = 0;

        if (startInput && endInput && startInput.value && endInput.value) {
            const start = new Date(startInput.value.replace(' ', 'T'));
            const end = new Date(endInput.value.replace(' ', 'T'));
            if (!isNaN(start) && !isNaN(end) && end > start) {
                days = Math.max(1, Math.ceil((end - start) / 3600000 / 24));
            }
        }

        const subtotal = rate * days;
        const discount = discountInput ? Number(discountInput.value || 0) : Number(form.dataset.discount || 0);
        const total = Math.max(0, subtotal - discount);
        const dp = dpInput ? Number(dpInput.value || 0) : Number(form.dataset.dp || 0);
        const balance = Math.max(0, total - dp);

        const set = (id, text) => {
            const el = document.getElementById(id);
            if (el) el.textContent = text;
        };

        set('previewRate', formatRp(rate));
        set('previewDays', days > 0 ? days + ' hari' : '-');
        set('previewSubtotal', formatRp(subtotal));
        set('previewDiscount', '- ' + formatRp(discount));
        set('previewTotal', formatRp(total));
        set('previewDp', formatRp(dp));
        set('previewBalance', formatRp(balance));
    }

    [startInput, endInput, vehicleSelect, discountInput, dpInput].forEach(el => {
        if (el) {
            el.addEventListener('change', recalculate);
            el.addEventListener('input', recalculate);
        }
    });

    recalculate();
});
