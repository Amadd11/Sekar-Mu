<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ManajemenAkun extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $roleFilter = '';
    public int $perPage = 10;

    // Modal State
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingUserId = null;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $role = 'reviewer';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function bukaModalCreate(): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->editingUserId = null;
        $this->name = '';
        $this->email = '';
        $this->role = 'reviewer';
        $this->password = '';
        $this->password_confirmation = '';

        $this->showModal = true;
    }

    public function bukaModalEdit(int $userId): void
    {
        $this->resetValidation();
        $user = User::with('roles')->findOrFail($userId);

        $this->isEditing = true;
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? 'applicant';
        $this->password = '';
        $this->password_confirmation = '';

        $this->showModal = true;
    }

    public function tutupModal(): void
    {
        $this->showModal = false;
    }

    public function simpanUser(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                $this->isEditing ? Rule::unique('users')->ignore($this->editingUserId) : 'unique:users',
            ],
            'role' => ['required', 'exists:roles,name'],
        ];

        if (! $this->isEditing || ! empty($this->password)) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $validated = $this->validate($rules);

        if ($this->isEditing && $this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            $user->update($updateData);
            $user->syncRoles([$validated['role']]);

            session()->flash('status', "Akun pengguna '{$user->name}' berhasil diperbarui!");
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);

            $user->assignRole($validated['role']);

            session()->flash('status', "Akun pengguna baru '{$user->name}' berhasil dibuat!");
        }

        $this->showModal = false;
    }

    public function hapusUser(int $userId): void
    {
        if (auth()->id() === $userId) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!');

            return;
        }

        $user = User::findOrFail($userId);
        $name = $user->name;
        $user->delete();

        session()->flash('status', "Akun pengguna '{$name}' telah dihapus.");
    }

    public function render(): View
    {
        $query = User::query()->with('roles');

        if ($this->roleFilter) {
            $query->role($this->roleFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        $totalUsers = User::count();
        $totalReviewers = User::role('reviewer')->count();
        $totalApplicants = User::role(['applicant', 'ketua_kepk', 'anggota_kepk'])->count();
        $totalAdmins = User::role('admin')->count();

        $daftarRoles = Role::orderBy('name')->get();

        return view('livewire.admin.manajemen-akun', [
            'usersList' => $query->latest()->paginate($this->perPage),
            'daftarRoles' => $daftarRoles,
            'totalUsers' => $totalUsers,
            'totalReviewers' => $totalReviewers,
            'totalApplicants' => $totalApplicants,
            'totalAdmins' => $totalAdmins,
        ])->layout('layouts.app');
    }
}
