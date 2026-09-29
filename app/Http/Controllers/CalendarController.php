<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        $vehicles = Vehicle::query()
            ->active()
            ->orderBy('brand')
            ->orderBy('model')
            ->get(['id', 'code', 'brand', 'model', 'license_plate']);

        $statuses = [
            TransactionStatus::AwaitingPayment->value => TransactionStatus::AwaitingPayment->label(),
            TransactionStatus::Booked->value => TransactionStatus::Booked->label(),
            TransactionStatus::ReadyForHandover->value => TransactionStatus::ReadyForHandover->label(),
            TransactionStatus::Rented->value => TransactionStatus::Rented->label(),
            TransactionStatus::Completed->value => TransactionStatus::Completed->label(),
        ];

        return view('calendar.index', [
            'vehicles' => $vehicles,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Real booking events for FullCalendar, derived from actual transactions.
     */
    public function events(Request $request): JsonResponse
    {
        $query = Transaction::query()
            ->with(['customer:id,name,phone', 'vehicle:id,code,brand,model,license_plate', 'driver:id,name'])
            ->whereIn('status', [
                TransactionStatus::AwaitingPayment->value,
                TransactionStatus::Booked->value,
                TransactionStatus::ReadyForHandover->value,
                TransactionStatus::Rented->value,
                TransactionStatus::Completed->value,
            ]);

        if ($request->filled('start')) {
            try {
                $startDate = Carbon::parse($request->query('start'))->startOfDay()->toDateTimeString();
                $query->where('end_at', '>=', $startDate);
            } catch (\Throwable) {
                $query->where('end_at', '>=', $request->query('start'));
            }
        }

        if ($request->filled('end')) {
            try {
                $endDate = Carbon::parse($request->query('end'))->endOfDay()->toDateTimeString();
                $query->where('start_at', '<=', $endDate);
            } catch (\Throwable) {
                $query->where('start_at', '<=', $request->query('end'));
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->query('vehicle_id'));
        }

        $events = $query->orderBy('start_at')->get()->map(function (Transaction $transaction) {
            $colors = [
                TransactionStatus::AwaitingPayment->value => '#d97706',
                TransactionStatus::Booked->value => '#2563eb',
                TransactionStatus::ReadyForHandover->value => '#0369a1',
                TransactionStatus::Rented->value => '#0f766e',
                TransactionStatus::Completed->value => '#16a34a',
            ];

            return [
                'id' => $transaction->id,
                'title' => sprintf(
                    '%s — %s (%s)',
                    $transaction->transaction_number,
                    $transaction->customer?->name ?? '-',
                    $transaction->vehicle?->license_plate ?? '-',
                ),
                'start' => $transaction->start_at->toIso8601String(),
                'end' => $transaction->end_at->toIso8601String(),
                'color' => $colors[$transaction->status->value] ?? '#2563eb',
                'url' => route('transactions.show', $transaction),
                'extendedProps' => [
                    'transaction_number' => $transaction->transaction_number,
                    'status' => $transaction->status->value,
                    'status_label' => $transaction->status->label(),
                    'customer_name' => $transaction->customer?->name ?? '-',
                    'customer_phone' => $transaction->customer?->phone ?? '-',
                    'vehicle' => $transaction->vehicle ? "{$transaction->vehicle->brand} {$transaction->vehicle->model} ({$transaction->vehicle->license_plate})" : '-',
                    'driver' => $transaction->driver?->name ?? 'Lepas Kunci (Tanpa Supir)',
                    'total' => 'Rp '.number_format($transaction->total, 0, ',', '.'),
                    'start_formatted' => $transaction->start_at->translatedFormat('d M Y, H:i'),
                    'end_formatted' => $transaction->end_at->translatedFormat('d M Y, H:i'),
                ],
            ];
        })->values();

        // FullCalendar expects a JSON array of events directly
        return response()->json($events);
    }
}
