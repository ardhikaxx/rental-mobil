<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        return view('calendar.index');
    }

    /**
     * Real booking events for FullCalendar, derived from actual transactions.
     */
    public function events(Request $request): JsonResponse
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $query = Transaction::query()
            ->with(['customer:id,name', 'vehicle:id,code,brand,model,license_plate'])
            ->whereIn('status', [
                TransactionStatus::AwaitingPayment->value,
                TransactionStatus::Booked->value,
                TransactionStatus::ReadyForHandover->value,
                TransactionStatus::Rented->value,
                TransactionStatus::Completed->value,
            ]);

        if ($start !== null) {
            $query->where('end_at', '>=', $start);
        }

        if ($end !== null) {
            $query->where('start_at', '<=', $end);
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
            ];
        })->values();

        return response()->json(['events' => $events]);
    }
}
