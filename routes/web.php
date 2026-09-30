<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HandoverController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Uploads streaming route (no storage:link required)
| Serves files with caching, content-type and ETag, exactly like sepeda-listrik
|--------------------------------------------------------------------------
*/
Route::get('/uploads/{path}', function (string $path) {
    $cleanPath = str_replace(['..', '\\'], ['', '/'], $path);
    $fullPath = storage_path('uploads/'.$cleanPath);

    if (! File::exists($fullPath) || File::isDirectory($fullPath)) {
        abort(404);
    }

    $file = File::get($fullPath);
    $type = File::mimeType($fullPath);
    $lastModified = File::lastModified($fullPath);

    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Cache-Control', 'public, max-age=31536000, immutable')
        ->header('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified).' GMT')
        ->header('ETag', md5($file));
})->where('path', '.*')->name('uploads');

/*
|--------------------------------------------------------------------------
| Guest routes (custom authentication, no starter kit)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

/*
|--------------------------------------------------------------------------
| Authenticated, non-parameterized routes first (so /buat never collides
| with /{model} bindings that are registered afterwards)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super_admin,admin,staff'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', function (Request $request) {
        return redirect()->route('dashboard');
    })->name('home');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/kendaraan', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/supir', [DriverController::class, 'index'])->name('drivers.index');
    Route::get('/pelanggan', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');

    Route::get('/serah-terima', [HandoverController::class, 'index'])->name('handover.index');
    Route::get('/pengembalian', [ReturnController::class, 'index'])->name('return.index');

    Route::get('/pemeriksaan', [InspectionController::class, 'index'])->name('inspections.index');
    Route::get('/pemeriksaan/baru', [InspectionController::class, 'create'])->name('inspections.create');
    Route::post('/pemeriksaan', [InspectionController::class, 'store'])->name('inspections.store');

    Route::get('/perawatan', [MaintenanceController::class, 'index'])->name('maintenances.index');
    Route::get('/panduan', [GuideController::class, 'index'])->name('guide.index');
});

Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/supir/buat', [DriverController::class, 'create'])->name('drivers.create');
    Route::post('/supir', [DriverController::class, 'store'])->name('drivers.store');
    Route::get('/supir/{driver}/ubah', [DriverController::class, 'edit'])->name('drivers.edit');
    Route::put('/supir/{driver}', [DriverController::class, 'update'])->name('drivers.update');
    Route::delete('/supir/{driver}', [DriverController::class, 'destroy'])->name('drivers.destroy');

    Route::get('/pelanggan/buat', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/pelanggan', [CustomerController::class, 'store'])->name('customers.store');
    Route::post('/pelanggan/{customer}/verifikasi', [CustomerController::class, 'verify'])->name('customers.verify');
    Route::post('/pelanggan/{customer}/tolak', [CustomerController::class, 'reject'])->name('customers.reject');

    Route::get('/transaksi/buat', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transactions.store');
    Route::post('/transaksi/{transaction}/deposit/update', [TransactionController::class, 'updateDeposit'])->name('transactions.deposit.update');

    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/pembayaran/buat', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/pembayaran', [PaymentController::class, 'store'])->name('payments.store');

    Route::get('/kalender', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/kalender/events', [CalendarController::class, 'events'])->name('calendar.events');
});

Route::middleware(['auth', 'role:super_admin,staff'])->group(function () {
    Route::post('/kendaraan/{vehicle}/bersihkan', [VehicleController::class, 'markCleaning'])->name('vehicles.mark-cleaning');
    Route::post('/kendaraan/{vehicle}/siap-jalan', [VehicleController::class, 'markReady'])->name('vehicles.mark-ready');
    Route::post('/kendaraan/{vehicle}/tersedia', [VehicleController::class, 'markAvailable'])->name('vehicles.mark-available');

    Route::get('/perawatan/buat', [MaintenanceController::class, 'create'])->name('maintenances.create');
    Route::post('/perawatan', [MaintenanceController::class, 'store'])->name('maintenances.store');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/kendaraan/buat', [VehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/kendaraan', [VehicleController::class, 'store'])->name('vehicles.store');

    Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
    Route::get('/pengguna/buat', [UserController::class, 'create'])->name('users.create');
    Route::post('/pengguna', [UserController::class, 'store'])->name('users.store');

    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/ekspor', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::get('/pengaturan', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/pengaturan', [SettingController::class, 'update'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Parameterized routes (route model binding)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super_admin,admin,staff'])->group(function () {
    Route::get('/media/inspections/{photo}', [MediaController::class, 'inspectionPhoto'])->name('media.inspection-photo');
    Route::get('/media/customers/{customer}/ktp', [MediaController::class, 'customerKtp'])->name('media.customer-ktp');
    Route::get('/media/customers/{customer}/sim', [MediaController::class, 'customerSim'])->name('media.customer-sim');

    Route::get('/kendaraan/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

    Route::get('/pelanggan/{customer}', [CustomerController::class, 'show'])->name('customers.show');

    Route::get('/transaksi/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');
    Route::get('/transaksi/{transaction}/invoice/pdf', [TransactionController::class, 'invoicePdf'])->name('transactions.invoice.pdf');
    Route::get('/transaksi/{transaction}/spk', [TransactionController::class, 'spk'])->name('transactions.spk');
    Route::get('/transaksi/{transaction}/spk/pdf', [TransactionController::class, 'spkPdf'])->name('transactions.spk.pdf');
    Route::get('/transaksi/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

    Route::get('/serah-terima/{transaction}', [HandoverController::class, 'create'])->name('handover.create');
    Route::post('/serah-terima/{transaction}', [HandoverController::class, 'store'])->name('handover.store');

    Route::get('/pengembalian/{transaction}', [ReturnController::class, 'create'])->name('return.create');
    Route::post('/pengembalian/{transaction}', [ReturnController::class, 'store'])->name('return.store');

    Route::get('/pemeriksaan/{inspection}', [InspectionController::class, 'show'])->name('inspections.show');

    Route::get('/perawatan/{maintenance}', [MaintenanceController::class, 'show'])->name('maintenances.show');
});

Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/pelanggan/{customer}/ubah', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/pelanggan/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/pelanggan/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/transaksi/{transaction}/ubah', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transaksi/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::post('/transaksi/{transaction}/setujui', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transaksi/{transaction}/siap-diserahkan', [TransactionController::class, 'markReady'])->name('transactions.mark-ready');
    Route::post('/transaksi/{transaction}/batal', [TransactionController::class, 'cancel'])->name('transactions.cancel');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/kendaraan/{vehicle}/ubah', [VehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/kendaraan/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/kendaraan/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
    Route::post('/kendaraan/{vehicle}/status', [VehicleController::class, 'updateStatus'])->name('vehicles.status');

    Route::get('/perawatan/{maintenance}/ubah', [MaintenanceController::class, 'edit'])->name('maintenances.edit');
    Route::put('/perawatan/{maintenance}', [MaintenanceController::class, 'update'])->name('maintenances.update');
    Route::post('/perawatan/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('maintenances.status');

    Route::get('/pengguna/{user}/ubah', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/pengguna/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
});
