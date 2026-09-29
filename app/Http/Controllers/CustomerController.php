<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->search($request->query('search'))
            ->withCount('transactions')
            ->withSum('transactions as total_spent', 'total')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('customers.index', [
            'customers' => $customers,
            'search' => $request->query('search'),
        ]);
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create($request->validated());

        AuditLogger::log('create', 'customers', "Pelanggan {$customer->name} ({$customer->id_number}) ditambahkan.", $customer);

        return redirect()->route('customers.show', $customer)->with('success', "Pelanggan {$customer->name} berhasil ditambahkan.");
    }

    public function show(Request $request, Customer $customer): View
    {
        $transactions = $customer->transactions()
            ->with('vehicle:id,code,brand,model,license_plate')
            ->withSum('payments as paid_amount', 'amount')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'totalTransactions' => $customer->transactions()->count(),
            'activeTransactions' => $customer->transactions()->whereIn('status', TransactionStatus::blocking())->count(),
            'totalValue' => (int) $customer->transactions()->sum('total'),
            'lastTransaction' => $customer->transactions()->latest('start_at')->first(),
        ];

        return view('customers.show', [
            'customer' => $customer,
            'transactions' => $transactions,
            'stats' => $stats,
        ]);
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', ['customer' => $customer]);
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        AuditLogger::log('update', 'customers', "Data pelanggan {$customer->name} diubah.", $customer);

        return redirect()->route('customers.show', $customer)->with('success', "Data pelanggan {$customer->name} berhasil diperbarui.");
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->transactions()->exists()) {
            throw ValidationException::withMessages([
                'customer' => 'Pelanggan memiliki riwayat transaksi dan tidak dapat dihapus. Riwayat rental harus tetap terjaga.',
            ]);
        }

        $name = $customer->name;
        $customer->delete();

        AuditLogger::log('delete', 'customers', "Pelanggan {$name} dihapus.", $customer);

        return redirect()->route('customers.index')->with('success', "Pelanggan {$name} berhasil dihapus.");
    }
}
