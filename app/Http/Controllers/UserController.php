<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage-users');

        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim((string) $request->query('search'));
                $query->where(function ($builder) use ($term) {
                    $builder->where('name', 'like', "%{$term}%")
                        ->orWhere('username', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->query('role')))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->query('status') === 'active'))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'roles' => UserRole::options(),
            'search' => $request->query('search'),
            'filterRole' => $request->query('role'),
            'filterStatus' => $request->query('status'),
            'activeCount' => User::where('is_active', true)->count(),
            'totalCount' => User::count(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('manage-users');

        return view('users.create', ['roles' => UserRole::options()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->authorize('manage-users');

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $user = User::create($data);

        AuditLogger::log('create', 'users', "Pengguna {$user->name} ({$user->username}) dengan role {$user->role->label()} dibuat.", $user);

        return redirect()->route('users.index')->with('success', "Pengguna {$user->name} berhasil dibuat.");
    }

    public function edit(User $user): View
    {
        $this->authorize('manage-users');

        return view('users.edit', [
            'user' => $user,
            'roles' => UserRole::options(),
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('manage-users');

        $data = $request->validated();

        if (! $request->has('is_active')) {
            unset($data['is_active']);
        } else {
            $data['is_active'] = $request->boolean('is_active');
        }

        if (($data['password'] ?? '') === '') {
            unset($data['password']);
        }

        $isSuperAdmin = $user->isSuperAdmin();
        $willDeactivate = array_key_exists('is_active', $data) && ! $data['is_active'];
        $willDemote = array_key_exists('role', $data) && $user->role->value !== $data['role'];

        if ($isSuperAdmin && ($willDeactivate || $willDemote)) {
            $this->ensureRemainingSuperAdmin($user, $willDeactivate ? 'menonaktifkan' : 'mengubah role');
        }

        $user->fill($data);
        $user->save();

        $description = "Data pengguna {$user->name} diubah.";
        if (($data['password'] ?? null) !== null) {
            $description = "Pengguna {$user->name} diubah dan password direset.";
        }

        AuditLogger::log('update', 'users', $description, $user);

        return redirect()->route('users.index')->with('success', "Pengguna {$user->name} berhasil diperbarui.");
    }

    public function toggle(Request $request, User $user): RedirectResponse
    {
        $this->authorize('manage-users');

        if ($user->id === $request->user()->id) {
            throw ValidationException::withMessages([
                'user' => 'Anda tidak dapat menonaktifkan akun yang sedang Anda gunakan sendiri.',
            ]);
        }

        if ($user->is_active) {
            $this->ensureRemainingSuperAdmin($user, 'menonaktifkan');
            $user->update(['is_active' => false]);

            AuditLogger::log('update', 'users', "Akun {$user->name} dinonaktifkan.", $user);

            return back()->with('success', "Akun {$user->name} dinonaktifkan.");
        }

        $user->update(['is_active' => true]);

        AuditLogger::log('update', 'users', "Akun {$user->name} diaktifkan kembali.", $user);

        return back()->with('success', "Akun {$user->name} diaktifkan kembali.");
    }

    /**
     * Guard against removing the last active Super Admin.
     */
    private function ensureRemainingSuperAdmin(User $user, string $action): void
    {
        $remaining = User::query()
            ->where('role', UserRole::SuperAdmin->value)
            ->where('is_active', true)
            ->where('id', '!=', $user->id)
            ->exists();

        if (! $remaining) {
            throw ValidationException::withMessages([
                'user' => "Tidak dapat {$action} akun Super Admin terakhir yang aktif. Tetapkan Super Admin lain terlebih dahulu.",
            ]);
        }
    }
}
