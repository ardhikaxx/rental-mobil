<?php

namespace App\Http\Controllers;

use App\Enums\MaintenanceStatus;
use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Models\Maintenance;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return match (true) {
            $user->isSuperAdmin() => $this->superAdminDashboard(),
            $user->isAdmin() => $this->adminDashboard(),
            default => $this->staffDashboard(),
        };
    }

    private function superAdminDashboard(): View
    {
        $vehicleCounts = Vehicle::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalVehicles = (int) Vehicle::count();
        $outstanding = $this->totalOutstanding();

        $topRented = Transaction::query()
            ->selectRaw('vehicle_id, COUNT(*) as total_rentals, SUM(rental_days) as total_days, SUM(total) as revenue')
            ->whereIn('status', [TransactionStatus::Booked->value, TransactionStatus::ReadyForHandover->value, TransactionStatus::Rented->value, TransactionStatus::Completed->value])
            ->groupBy('vehicle_id')
            ->orderByDesc('total_rentals')
            ->limit(5)
            ->with('vehicle')
            ->get();

        $frequentMaintenance = Maintenance::query()
            ->selectRaw('vehicle_id, COUNT(*) as total, SUM(cost) as total_cost')
            ->groupBy('vehicle_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('vehicle')
            ->get();

        $incomeChart = collect(range(5, 0))->map(function (int $monthsAgo) {
            $start = now()->subMonths($monthsAgo)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            return [
                'label' => $start->format('M Y'),
                'total' => (int) Payment::whereBetween('paid_at', [$start->toDateString(), $end->toDateString()])->sum('amount'),
            ];
        })->values();

        $recentTransactions = Transaction::query()
            ->with(['customer', 'vehicle'])
            ->latest()
            ->limit(6)
            ->get();

        return view('dashboard.super-admin', [
            'totalVehicles' => $totalVehicles,
            'availableVehicles' => (int) ($vehicleCounts[VehicleStatus::Available->value] ?? 0),
            'rentedVehicles' => (int) ($vehicleCounts[VehicleStatus::Rented->value] ?? 0),
            'maintenanceVehicles' => (int) ($vehicleCounts[VehicleStatus::Maintenance->value] ?? 0),
            'activeTransactions' => Transaction::whereIn('status', TransactionStatus::blocking())->count(),
            'completedTransactions' => Transaction::where('status', TransactionStatus::Completed->value)->count(),
            'todayIncome' => (int) Payment::whereDate('paid_at', today())->sum('amount'),
            'monthIncome' => (int) Payment::whereBetween('paid_at', [now()->startOfMonth()->toDateString(), now()->toDateString()])->sum('amount'),
            'outstanding' => $outstanding,
            'topRented' => $topRented,
            'frequentMaintenance' => $frequentMaintenance,
            'incomeChart' => $incomeChart,
            'recentTransactions' => $recentTransactions,
        ]);
    }

    private function adminDashboard(): View
    {
        $today = now()->toDateString();

        $startsToday = Transaction::query()
            ->with(['customer', 'vehicle'])
            ->whereIn('status', [TransactionStatus::Booked->value, TransactionStatus::ReadyForHandover->value])
            ->whereDate('start_at', $today)
            ->orderBy('start_at')
            ->get();

        $pendingHandover = Transaction::query()
            ->with(['customer', 'vehicle'])
            ->whereIn('status', [TransactionStatus::Booked->value, TransactionStatus::ReadyForHandover->value])
            ->where('start_at', '<=', now()->endOfDay())
            ->orderBy('start_at')
            ->limit(8)
            ->get();

        $returnsToday = Transaction::query()
            ->with(['customer', 'vehicle'])
            ->where('status', TransactionStatus::Rented->value)
            ->whereDate('end_at', $today)
            ->orderBy('end_at')
            ->get();

        $overdue = Transaction::query()
            ->with(['customer', 'vehicle'])
            ->where('status', TransactionStatus::Rented->value)
            ->where('end_at', '<', now())
            ->orderBy('end_at')
            ->limit(8)
            ->get();

        $rentedCount = Transaction::where('status', TransactionStatus::Rented->value)->count();

        $unpaid = Transaction::query()
            ->with(['customer', 'vehicle'])
            ->whereIn('status', TransactionStatus::payable())
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(fn (Transaction $t) => $t->balance() > 0)
            ->take(8)
            ->values();

        $latestPayments = Payment::query()
            ->with(['transaction.customer', 'recorder'])
            ->latest()
            ->limit(6)
            ->get();

        $unpaidTotal = $unpaid->sum(fn (Transaction $t) => $t->balance());

        return view('dashboard.admin', [
            'startsToday' => $startsToday,
            'pendingHandover' => $pendingHandover,
            'returnsToday' => $returnsToday,
            'overdue' => $overdue,
            'rentedCount' => $rentedCount,
            'unpaid' => $unpaid,
            'unpaidTotal' => (int) $unpaidTotal,
            'latestPayments' => $latestPayments,
            'overdueFine' => (int) $overdue->sum('late_fee'),
        ]);
    }

    private function staffDashboard(): View
    {
        $today = now()->toDateString();

        $toPrepare = Transaction::query()
            ->with(['customer', 'vehicle'])
            ->whereIn('status', [TransactionStatus::Booked->value, TransactionStatus::ReadyForHandover->value])
            ->where('start_at', '<=', now()->addDay()->endOfDay())
            ->orderBy('start_at')
            ->limit(8)
            ->get();

        $handoverToday = Transaction::query()
            ->with(['customer', 'vehicle'])
            ->whereIn('status', [TransactionStatus::Booked->value, TransactionStatus::ReadyForHandover->value])
            ->whereDate('start_at', $today)
            ->orderBy('start_at')
            ->get();

        $returnsToday = Transaction::query()
            ->with(['customer', 'vehicle'])
            ->where('status', TransactionStatus::Rented->value)
            ->whereDate('end_at', $today)
            ->orderBy('end_at')
            ->get();

        $needsInspection = Transaction::query()
            ->with(['customer', 'vehicle'])
            ->where('status', TransactionStatus::ReadyForHandover->value)
            ->whereDoesntHave('handoverInspection')
            ->orderBy('start_at')
            ->limit(8)
            ->get();

        $vehicleCounts = Vehicle::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $rentedVehicles = Vehicle::where('status', VehicleStatus::Rented->value)->count();

        $activeMaintenance = Maintenance::query()
            ->with('vehicle')
            ->whereIn('status', MaintenanceStatus::active())
            ->orderBy('start_date')
            ->get();

        return view('dashboard.staff', [
            'toPrepare' => $toPrepare,
            'handoverToday' => $handoverToday,
            'returnsToday' => $returnsToday,
            'needsInspection' => $needsInspection,
            'cleaningCount' => (int) ($vehicleCounts[VehicleStatus::Cleaning->value] ?? 0),
            'readyCount' => (int) ($vehicleCounts[VehicleStatus::Ready->value] ?? 0),
            'availableCount' => (int) ($vehicleCounts[VehicleStatus::Available->value] ?? 0),
            'rentedVehicles' => $rentedVehicles,
            'activeMaintenance' => $activeMaintenance,
        ]);
    }

    /**
     * Total unpaid obligations across all non-cancelled transactions.
     */
    private function totalOutstanding(): int
    {
        $row = DB::selectOne(<<<'SQL'
            SELECT COALESCE(SUM(sub.total + sub.late_fee - COALESCE(p.paid, 0)), 0) AS outstanding
            FROM (
                SELECT t.id, t.total, t.late_fee
                FROM transactions t
                WHERE t.status != 'cancelled'
            ) sub
            LEFT JOIN (
                SELECT transaction_id, SUM(amount) AS paid
                FROM payments
                GROUP BY transaction_id
            ) p ON p.transaction_id = sub.id
            WHERE (sub.total + sub.late_fee - COALESCE(p.paid, 0)) > 0
            SQL);

        return (int) ($row->outstanding ?? 0);
    }
}
