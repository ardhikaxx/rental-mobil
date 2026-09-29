<?php

namespace Database\Seeders;

use App\Enums\BookingSource;
use App\Enums\Completeness;
use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\InspectionType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Enums\TireCondition;
use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Models\Customer;
use App\Models\Inspection;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\NumberGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    private User $admin;

    private User $staff;

    private User $owner;

    private NumberGenerator $numbers;

    public function run(): void
    {
        $this->admin = User::where('username', 'adminoper')->firstOrFail();
        $this->staff = User::where('username', 'stafgarasi')->firstOrFail();
        $this->owner = User::where('username', 'superadmin')->firstOrFail();
        $this->numbers = app(NumberGenerator::class);

        DB::transaction(function () {
            $this->seedCompletedRental();
            $this->seedCompletedLateRental();
            $this->seedActiveRentals();
            $this->seedUpcomingBookings();
            $this->seedDraftAndCancelled();
            $this->seedRecentlyReturned();
            $this->seedOlderCompletedRental();
        });
    }

    private function seedCompletedRental(): void
    {
        $vehicle = Vehicle::where('code', 'VT-001')->firstOrFail();
        $customer = Customer::where('id_number', '3271050101800001')->firstOrFail();

        $start = now()->subDays(13)->setTime(9, 0);
        $end = now()->subDays(10)->setTime(17, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;

        $tx = $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::Completed, BookingSource::WalkIn, 'Pelanggan lama, sewa liburan keluarga.');

        $this->pay($tx, (int) round($subtotal / 2), PaymentType::DownPayment, PaymentMethod::Cash, now()->subDays(15)->toDateString(), 'DP saat pemesanan.');
        $this->pay($tx, $subtotal - (int) round($subtotal / 2), PaymentType::Final, PaymentMethod::Transfer, now()->subDays(10)->toDateString(), 'Pelunasan saat pengambilan.');

        $this->handover($tx, $vehicle, $start, (int) $vehicle->odometer - 320, FuelLevel::Full, 'Baret halus bumper depan (kerusakan lama).');
        $this->returnVehicle($tx, $vehicle, $end, (int) $vehicle->odometer - 95, FuelLevel::Half, VehicleStatus::Available, null);

        $tx->update(['status' => TransactionStatus::Completed]);
    }

    private function seedCompletedLateRental(): void
    {
        $vehicle = Vehicle::where('code', 'VT-004')->firstOrFail();
        $customer = Customer::where('id_number', '3271050202850002')->firstOrFail();

        $start = now()->subDays(20)->setTime(9, 0);
        $end = now()->subDays(17)->setTime(9, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;
        $lateFee = 200000; // 6 jam terlambat - 60 menit tenggang = 4 jam × Rp 50.000

        $tx = $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::Completed, BookingSource::WhatsApp, 'Pemesanan via WhatsApp.', lateMinutes: 240, lateFee: $lateFee);

        $this->pay($tx, 300000, PaymentType::DownPayment, PaymentMethod::Cash, now()->subDays(22)->toDateString(), 'DP awal.');
        $this->pay($tx, 600000, PaymentType::Installment, PaymentMethod::Transfer, now()->subDays(18)->toDateString(), 'Cicilan.');
        $this->pay($tx, $lateFee, PaymentType::LateFee, PaymentMethod::Cash, now()->subDays(17)->toDateString(), 'Pembayaran denda keterlambatan.');

        $this->handover($tx, $vehicle, $start, (int) $vehicle->odometer - 600, FuelLevel::ThreeQuarters, null);
        $this->returnVehicle($tx, $vehicle, $end->copy()->addHours(6), (int) $vehicle->odometer - 120, FuelLevel::Quarter, VehicleStatus::Available, null);

        $tx->update(['status' => TransactionStatus::Completed]);
    }

    private function seedActiveRentals(): void
    {
        // Sedang berjalan, jadwal kembali besok.
        $vehicle = Vehicle::where('code', 'VT-003')->firstOrFail();
        $customer = Customer::where('id_number', '3271050303790003')->firstOrFail();

        $start = now()->subDays(2)->setTime(9, 0);
        $end = now()->addDay()->setTime(17, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;

        $tx = $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::Rented, BookingSource::WalkIn, 'Sewa untuk perjalanan dinas.');

        $this->pay($tx, 500000, PaymentType::DownPayment, PaymentMethod::Cash, now()->subDays(3)->toDateString(), 'DP.');
        $this->pay($tx, 300000, PaymentType::Installment, PaymentMethod::QRIS, now()->toDateString(), 'Cicilan pelanggan.');

        $this->handover($tx, $vehicle, $start, (int) $vehicle->odometer - 410, FuelLevel::Full, 'Tidak ada kerusakan baru.');
        $this->returnVehicle($tx, $vehicle, null, null, null, null, null);
        $tx->update(['status' => TransactionStatus::Rented]);

        // Terlambat: lewat tenggat kemarin, belum dikembalikan.
        $vehicle = Vehicle::where('code', 'VT-005')->firstOrFail();
        $customer = Customer::where('id_number', '3271050404900004')->firstOrFail();

        $start = now()->subDays(5)->setTime(9, 0);
        $end = now()->subDay()->setTime(9, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;

        $tx = $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::Rented, BookingSource::Phone, 'Pelanggan meminta perpanjangan, menunggu konfirmasi.');

        $this->pay($tx, 400000, PaymentType::DownPayment, PaymentMethod::Transfer, now()->subDays(6)->toDateString(), 'DP.');
        $this->handover($tx, $vehicle, $start, (int) $vehicle->odometer - 720, FuelLevel::ThreeQuarters, 'Spion kanan goyang (kerusakan lama).');
        $tx->update(['status' => TransactionStatus::Rented]);
    }

    private function seedUpcomingBookings(): void
    {
        // Booking disetujui, kendaraan dibooking.
        $vehicle = Vehicle::where('code', 'VT-006')->firstOrFail();
        $customer = Customer::where('id_number', '3271050505750005')->firstOrFail();

        $start = now()->addDays(2)->setTime(9, 0);
        $end = now()->addDays(5)->setTime(9, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;

        $tx = $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::Booked, BookingSource::WhatsApp, 'Konfirmasi booking via WhatsApp.');
        $this->pay($tx, 400000, PaymentType::DownPayment, PaymentMethod::QRIS, now()->toDateString(), 'DP 30%.');
        $vehicle->update(['status' => VehicleStatus::Booked]);

        // Siap diserahkan besok pagi, sudah diperiksa staf.
        $vehicle = Vehicle::where('code', 'VT-007')->firstOrFail();
        $customer = Customer::where('id_number', '3271050606950006')->firstOrFail();

        $start = now()->addDay()->setTime(9, 0);
        $end = now()->addDays(4)->setTime(9, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;

        $tx = $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::ReadyForHandover, BookingSource::Referral, 'Pelanggan direkomendasikan pelanggan lama.');
        $this->pay($tx, (int) round($subtotal / 2), PaymentType::DownPayment, PaymentMethod::Cash, now()->subDay()->toDateString(), 'DP 50%.');
        $this->pay($tx, $subtotal - (int) round($subtotal / 2), PaymentType::Final, PaymentMethod::Transfer, now()->toDateString(), 'Pelunasan sebelum serah terima.');
        $vehicle->update(['status' => VehicleStatus::Ready]);
    }

    private function seedDraftAndCancelled(): void
    {
        // Menunggu pembayaran DP.
        $vehicle = Vehicle::where('code', 'VT-008')->firstOrFail();
        $customer = Customer::where('id_number', '3271050707700007')->firstOrFail();

        $start = now()->addDays(6)->setTime(9, 0);
        $end = now()->addDays(8)->setTime(9, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;

        $tx = $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::AwaitingPayment, BookingSource::WalkIn, 'Menunggu DP.');
        $this->pay($tx, 100000, PaymentType::DownPayment, PaymentMethod::Cash, now()->toDateString(), 'DP sebagian.');

        // Draft.
        $vehicle = Vehicle::where('code', 'VT-009')->firstOrFail();
        $customer = Customer::where('id_number', '3271050808880008')->firstOrFail();

        $start = now()->addDays(9)->setTime(9, 0);
        $end = now()->addDays(11)->setTime(9, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;

        $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::Draft, BookingSource::Phone, 'Data belum lengkap, disimpan sebagai draft.');

        // Dibatalkan.
        $vehicle = Vehicle::where('code', 'VT-010')->firstOrFail();
        $customer = Customer::where('id_number', '3271050101800001')->firstOrFail();

        $start = now()->subDays(6)->setTime(9, 0);
        $end = now()->subDays(4)->setTime(9, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;

        $tx = $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::Cancelled, BookingSource::WhatsApp, 'Dibatalkan pelanggan.');
        $tx->logs()->create([
            'user_id' => $this->admin->id,
            'action' => 'status_change',
            'from_status' => TransactionStatus::AwaitingPayment->value,
            'to_status' => TransactionStatus::Cancelled->value,
            'description' => "Transaksi dibatalkan oleh {$this->admin->name}. Alasan: pelanggan berubah rencana.",
        ]);
    }

    private function seedRecentlyReturned(): void
    {
        $vehicle = Vehicle::where('code', 'VT-011')->firstOrFail();
        $customer = Customer::where('id_number', '3271050202850002')->firstOrFail();

        $start = now()->subDays(7)->setTime(9, 0);
        $end = now()->subDay()->setTime(9, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;

        $tx = $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::Completed, BookingSource::WalkIn, 'Sewa mingguan.');

        $this->pay($tx, 500000, PaymentType::DownPayment, PaymentMethod::Cash, now()->subDays(8)->toDateString(), 'DP.');
        $this->pay($tx, $subtotal - 500000, PaymentType::Final, PaymentMethod::Transfer, now()->subDay()->toDateString(), 'Pelunasan.');

        $this->handover($tx, $vehicle, $start, (int) $vehicle->odometer - 850, FuelLevel::Full, null);
        $this->returnVehicle($tx, $vehicle, $start->copy()->addDays($days)->subHours(6), (int) $vehicle->odometer, FuelLevel::Quarter, VehicleStatus::Cleaning, 'Kabin kotor setelah perjalanan, perlu pembersihan menyeluruh.');

        $tx->update(['status' => TransactionStatus::Completed]);
    }

    private function seedOlderCompletedRental(): void
    {
        $vehicle = Vehicle::where('code', 'VT-002')->firstOrFail();
        $customer = Customer::where('id_number', '3271050303790003')->firstOrFail();

        $start = now()->subDays(25)->setTime(9, 0);
        $end = now()->subDays(22)->setTime(9, 0);
        $days = $this->days($start, $end);
        $subtotal = $days * (int) $vehicle->daily_rate;

        $tx = $this->createTransaction($vehicle, $customer, $start, $end, $days, $subtotal, TransactionStatus::Completed, BookingSource::Other, 'Corporate rental.');

        $this->pay($tx, 500000, PaymentType::DownPayment, PaymentMethod::Transfer, now()->subDays(26)->toDateString(), 'DP.');
        $this->pay($tx, $subtotal - 500000, PaymentType::Final, PaymentMethod::Transfer, now()->subDays(22)->toDateString(), 'Pelunasan.');

        $this->handover($tx, $vehicle, $start, (int) $vehicle->odometer - 900, FuelLevel::Full, null);
        $this->returnVehicle($tx, $vehicle, $end, (int) $vehicle->odometer - 450, FuelLevel::ThreeQuarters, VehicleStatus::Available, null);

        $tx->update(['status' => TransactionStatus::Completed]);
    }

    /**
     * @return array<string, mixed>
     */
    private function createTransaction(
        Vehicle $vehicle,
        Customer $customer,
        Carbon $start,
        Carbon $end,
        int $days,
        int $subtotal,
        TransactionStatus $status,
        BookingSource $source,
        ?string $notes = null,
        int $lateMinutes = 0,
        int $lateFee = 0,
    ): Transaction {
        $transaction = Transaction::create([
            'transaction_number' => $this->numbers->transactionNumber(),
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'created_by' => $this->admin->id,
            'booking_source' => $source,
            'start_at' => $start,
            'end_at' => $end,
            'daily_rate' => $vehicle->daily_rate,
            'rental_days' => $days,
            'subtotal' => $subtotal,
            'discount' => 0,
            'total' => $subtotal,
            'late_minutes' => $lateMinutes,
            'late_fee' => $lateFee,
            'status' => $status,
            'notes' => $notes,
        ]);

        $transaction->logs()->create([
            'user_id' => $this->admin->id,
            'action' => 'created',
            'to_status' => $status->value,
            'description' => "Transaksi dibuat oleh {$this->admin->name} (seed data).",
        ]);

        return $transaction;
    }

    private function pay(Transaction $transaction, int $amount, PaymentType $type, PaymentMethod $method, string $paidAt, ?string $notes): void
    {
        Payment::create([
            'payment_number' => $this->numbers->paymentNumber(),
            'transaction_id' => $transaction->id,
            'amount' => $amount,
            'method' => $method,
            'type' => $type,
            'paid_at' => $paidAt,
            'notes' => $notes,
            'recorded_by' => $this->admin->id,
        ]);

        $transaction->logs()->create([
            'user_id' => $this->admin->id,
            'action' => 'payment',
            'description' => sprintf('%s sebesar %s dicatat oleh %s.', $type->label(), rupiah($amount), $this->admin->name),
        ]);
    }

    private function handover(Transaction $transaction, Vehicle $vehicle, Carbon $when, int $odometer, FuelLevel $fuel, ?string $existingDamage): void
    {
        Inspection::create([
            'vehicle_id' => $vehicle->id,
            'transaction_id' => $transaction->id,
            'type' => InspectionType::Handover,
            'inspected_at' => $when,
            'inspected_by' => $this->staff->id,
            'odometer' => $odometer,
            'fuel_level' => $fuel,
            'exterior_condition' => ConditionLevel::Good,
            'interior_condition' => ConditionLevel::Good,
            'tire_condition' => TireCondition::Good,
            'completeness' => Completeness::Complete,
            'existing_damage' => $existingDamage,
            'notes' => 'Pemeriksaan kondisi awal (seed data).',
        ]);

        $transaction->update([
            'handover_at' => $when,
            'handed_over_by' => $this->staff->id,
            'status' => TransactionStatus::Rented,
        ]);

        $vehicle->update([
            'status' => VehicleStatus::Rented,
            'odometer' => max((int) $vehicle->odometer, $odometer),
            'fuel_level' => $fuel,
        ]);

        $transaction->logs()->create([
            'user_id' => $this->staff->id,
            'action' => 'handover',
            'from_status' => TransactionStatus::ReadyForHandover->value,
            'to_status' => TransactionStatus::Rented->value,
            'description' => "Kendaraan diserahkan kepada pelanggan oleh {$this->staff->name}.",
        ]);
    }

    private function returnVehicle(
        Transaction $transaction,
        Vehicle $vehicle,
        ?Carbon $when,
        ?int $odometer,
        ?FuelLevel $fuel,
        ?VehicleStatus $nextStatus,
        ?string $notes,
    ): void {
        if ($when === null) {
            return;
        }

        Inspection::create([
            'vehicle_id' => $vehicle->id,
            'transaction_id' => $transaction->id,
            'type' => InspectionType::Return,
            'inspected_at' => $when,
            'inspected_by' => $this->staff->id,
            'odometer' => $odometer,
            'fuel_level' => $fuel,
            'exterior_condition' => ConditionLevel::Good,
            'interior_condition' => ConditionLevel::Minor,
            'tire_condition' => TireCondition::Good,
            'completeness' => Completeness::Complete,
            'new_damage' => null,
            'notes' => $notes,
            'vehicle_status_after' => $nextStatus?->value,
        ]);

        $transaction->update([
            'actual_return_at' => $when,
            'returned_by' => $this->staff->id,
        ]);

        $vehicle->update([
            'status' => $nextStatus ?? $vehicle->status,
            'odometer' => $odometer !== null ? max((int) $vehicle->odometer, $odometer) : $vehicle->odometer,
            'fuel_level' => $fuel ?? $vehicle->fuel_level,
        ]);

        $transaction->logs()->create([
            'user_id' => $this->staff->id,
            'action' => 'return',
            'description' => "Kendaraan dikembalikan dan diperiksa oleh {$this->staff->name}.",
        ]);
    }

    private function days(Carbon $start, Carbon $end): int
    {
        return (int) max(1, ceil($start->diffInHours($end) / 24));
    }
}
