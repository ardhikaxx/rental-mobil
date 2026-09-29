<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Services\ImageUploadService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageService
    ) {}

    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->search($request->query('search'))
            ->when($request->filled('status'), fn ($q) => $q->where('verification_status', $request->query('status')))
            ->withCount('transactions')
            ->withSum('transactions as total_spent', 'total')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('customers.index', [
            'customers' => $customers,
            'search' => $request->query('search'),
            'statusFilter' => $request->query('status'),
        ]);
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('ktp_photo')) {
            $data['ktp_photo'] = $this->imageService->upload($request->file('ktp_photo'), 'customers/ktp', 'ktp_');
        }

        if ($request->hasFile('sim_photo')) {
            $data['sim_photo'] = $this->imageService->upload($request->file('sim_photo'), 'customers/sim', 'sim_');
        }

        // Jika dokumen KTP/SIM diunggah, otomatis pending verifikasi; jika admin mengisi langsung diverifikasi
        $data['verification_status'] = 'verified';
        $data['verified_at'] = now();
        $data['verified_by'] = $request->user()->id;

        $customer = Customer::create($data);

        AuditLogger::log('create', 'customers', "Pelanggan {$customer->name} ({$customer->id_number}) ditambahkan.", $customer);

        return redirect()->route('customers.show', $customer)->with('success', "Pelanggan {$customer->name} berhasil ditambahkan.");
    }

    public function show(Request $request, Customer $customer): View
    {
        $customer->load('verifiedBy:id,name');

        $transactions = $customer->transactions()
            ->with(['vehicle:id,code,brand,model,license_plate', 'driver:id,code,name'])
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
        $data = $request->validated();

        if ($request->boolean('delete_ktp_photo')) {
            if ($customer->ktp_photo) {
                $this->imageService->delete($customer->ktp_photo, 'customers/ktp');
            }
            $data['ktp_photo'] = null;
        } elseif ($request->hasFile('ktp_photo')) {
            if ($customer->ktp_photo) {
                $this->imageService->delete($customer->ktp_photo, 'customers/ktp');
            }
            $data['ktp_photo'] = $this->imageService->upload($request->file('ktp_photo'), 'customers/ktp', 'ktp_');
        }

        if ($request->boolean('delete_sim_photo')) {
            if ($customer->sim_photo) {
                $this->imageService->delete($customer->sim_photo, 'customers/sim');
            }
            $data['sim_photo'] = null;
        } elseif ($request->hasFile('sim_photo')) {
            if ($customer->sim_photo) {
                $this->imageService->delete($customer->sim_photo, 'customers/sim');
            }
            $data['sim_photo'] = $this->imageService->upload($request->file('sim_photo'), 'customers/sim', 'sim_');
        }

        $customer->update($data);

        AuditLogger::log('update', 'customers', "Data pelanggan {$customer->name} diubah.", $customer);

        return redirect()->route('customers.show', $customer)->with('success', "Data pelanggan {$customer->name} berhasil diperbarui.");
    }

    public function verify(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
            'rejection_reason' => null,
        ]);

        AuditLogger::log('update', 'customers', "Dokumen identitas pelanggan {$customer->name} diverifikasi.", $customer);

        return back()->with('success', "Identitas pelanggan {$customer->name} berhasil diverifikasi.");
    }

    public function reject(Request $request, Customer $customer): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:255'],
        ], [], [
            'rejection_reason' => 'alasan penolakan',
        ]);

        $customer->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
            'verified_at' => null,
            'verified_by' => $request->user()->id,
        ]);

        AuditLogger::log('update', 'customers', "Verifikasi pelanggan {$customer->name} ditolak. Alasan: {$request->input('rejection_reason')}", $customer);

        return back()->with('warning', "Verifikasi pelanggan {$customer->name} telah ditandai ditolak.");
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->transactions()->exists()) {
            throw ValidationException::withMessages([
                'customer' => 'Pelanggan memiliki riwayat transaksi dan tidak dapat dihapus. Riwayat rental harus tetap terjaga.',
            ]);
        }

        $name = $customer->name;

        if ($customer->ktp_photo) {
            $this->imageService->delete($customer->ktp_photo, 'customers/ktp');
        }
        if ($customer->sim_photo) {
            $this->imageService->delete($customer->sim_photo, 'customers/sim');
        }

        $customer->delete();

        AuditLogger::log('delete', 'customers', "Pelanggan {$name} dihapus.", $customer);

        return redirect()->route('customers.index')->with('success', "Pelanggan {$name} berhasil dihapus.");
    }
}
