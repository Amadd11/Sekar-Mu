<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public string $selectedRole = 'ketua_kepk';

    public function selectRole(string $role): void
    {
        $this->selectedRole = $role;
    }

    public function login(): void
    {
        $this->validate([
            'selectedRole' => ['required', 'string', 'in:admin,ketua_kepk,asessor,anggota'],
        ]);

        $this->form->authenticate();

        $user = auth()->user();

        // Verify user role access
        $hasAccess = true;
        if ($user && $user->roles()->count() > 0) {
            $hasAccess = match($this->selectedRole) {
                'admin' => $user->isAdmin(),
                'ketua_kepk' => $user->isKetuaKepk(),
                'asessor' => $user->isAsessor(),
                'anggota' => $user->isAnggota(),
                default => true,
            };
        }

        if (! $hasAccess) {
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            $roleLabels = [
                'admin' => 'Administrator',
                'ketua_kepk' => 'Ketua KEPK',
                'asessor' => 'Asesor Penilai',
                'anggota' => 'Anggota KEPK',
            ];
            $roleLabel = $roleLabels[$this->selectedRole] ?? 'peran yang dipilih';

            $this->addError('form.email', "Akun Anda tidak memiliki hak akses sebagai {$roleLabel}. Silakan pilih peran yang sesuai dengan akun Anda.");
            return;
        }

        session(['active_role' => $this->selectedRole]);
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-4xl bg-white border border-slate-200 rounded-3xl shadow-lg overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[540px]">
        
        <!-- Left Hero / Branding Panel -->
        <div class="lg:col-span-5 bg-[#174668] text-white p-8 sm:p-10 flex flex-col justify-between">
            <div class="space-y-6">
                <!-- Mascot & Brand Logo -->
                <div class="flex items-center gap-3">
                    <div class="text-3xl select-none">🌸</div>
                    <div>
                        <div class="text-2xl font-black tracking-tight text-white leading-none font-display">SekarMU</div>
                        <div class="text-[10px] font-bold tracking-widest text-teal-200 uppercase mt-1">SISTEM KEPK</div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-white leading-snug">
                        Sistem Evaluasi dan Akreditasi KEPK
                    </h2>
                    <p class="text-xs text-teal-100/80 mt-1 leading-relaxed">
                        Komite Etik Penelitian Kesehatan Universitas Muhammadiyah Yogyakarta
                    </p>
                </div>

                <!-- Feature Highlights -->
                <div class="space-y-3.5 pt-4 border-t border-white/10 text-xs">
                    <div class="flex items-start gap-2.5">
                        <div class="w-5 h-5 rounded-md bg-teal-500/20 text-teal-300 flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">
                            ✓
                        </div>
                        <span class="text-teal-100/90 leading-tight">
                            Evaluasi Diri Standar KNEPK & WHO-CIOMS (164 Butir Instrumen)
                        </span>
                    </div>

                    <div class="flex items-start gap-2.5">
                        <div class="w-5 h-5 rounded-md bg-teal-500/20 text-teal-300 flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">
                            ✓
                        </div>
                        <span class="text-teal-100/90 leading-tight">
                            Manajemen Terpadu Surat Pengajuan & List Protokol Riset
                        </span>
                    </div>

                    <div class="flex items-start gap-2.5">
                        <div class="w-5 h-5 rounded-md bg-teal-500/20 text-teal-300 flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">
                            ✓
                        </div>
                        <span class="text-teal-100/90 leading-tight">
                            Portal Penilaian Etik Independen & Rekomendasi Keputusan
                        </span>
                    </div>
                </div>
            </div>

            <div class="pt-6 mt-6 border-t border-white/10 text-[11px] text-teal-200/70">
                &copy; {{ date('Y') }} KEPK UMY • Seluruh Hak Cipta Dilindungi
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto space-y-5">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Masuk ke Portal</h1>
                    <p class="text-xs text-slate-500 mt-1">Pilih peran akses Anda, lalu masukkan email dan kata sandi.</p>
                </div>

                <!-- Session Status Alert -->
                <x-auth-session-status class="text-xs text-teal-800 bg-teal-50 p-3.5 rounded-xl border border-teal-200" :status="session('status')" />

                <!-- Login Form -->
                <form wire:submit="login" class="space-y-4">
                    <!-- Step 1: Role Selection Cards -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">
                            Pilih Peran Akses <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2.5">
                            @foreach([
                                'ketua_kepk' => [
                                     'title' => 'Ketua KEPK',
                                     'desc' => 'Ketua Komisi Etik',
                                     'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />',
                                 ],
                                 'asessor' => [
                                     'title' => 'Asesor Penilai',
                                     'desc' => 'Tim Penilai Etik',
                                     'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                 ],
                                 'anggota' => [
                                     'title' => 'Anggota KEPK',
                                     'desc' => 'Anggota Komisi Etik',
                                     'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.999-3.199a5.96 5.96 0 01-.94 3.199m0 0A5.982 5.982 0 013 18.72" />',
                                 ],
                                 'admin' => [
                                     'title' => 'Administrator',
                                     'desc' => 'Pengelola Sistem',
                                     'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
                                 ],
                            ] as $roleKey => $roleData)
                                @php
                                    $isSelected = $selectedRole === $roleKey;
                                @endphp
                                <button
                                    type="button"
                                    wire:click="selectRole('{{ $roleKey }}')"
                                    class="p-2.5 rounded-xl border text-left transition-colors relative cursor-pointer flex items-center gap-2.5 {{ $isSelected ? 'bg-[#174668] text-white border-[#174668]' : 'bg-slate-50 border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-100' }}"
                                >
                                    <svg class="w-5 h-5 shrink-0 {{ $isSelected ? 'text-teal-300' : 'text-slate-500' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        {!! $roleData['svg'] !!}
                                    </svg>
                                    <div class="overflow-hidden min-w-0 flex-1">
                                        <div class="text-xs font-bold truncate leading-tight {{ $isSelected ? 'text-white' : 'text-slate-900' }}">
                                            {{ $roleData['title'] }}
                                        </div>
                                        <div class="text-[10px] truncate mt-0.5 {{ $isSelected ? 'text-teal-200' : 'text-slate-500' }}">
                                            {{ $roleData['desc'] }}
                                        </div>
                                    </div>
                                    @if($isSelected)
                                        <span class="w-4 h-4 rounded-full bg-white text-[#174668] flex items-center justify-center text-[10px] font-black shrink-0">✓</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 2: Email & Password Fields -->
                    <div class="pt-2 space-y-3.5">
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    wire:model="form.email"
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="nama@institusi.ac.id"
                                    style="padding-left: 2.75rem !important; padding-right: 1rem !important;"
                                    class="w-full h-11 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 text-xs focus:border-[#174668] focus:ring-2 focus:ring-[#174668]/15 focus:outline-none transition shadow-2xs"
                                />
                                <div class="absolute inset-y-0 left-0 w-11 flex items-center justify-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <rect width="20" height="16" x="2" y="4" rx="2" />
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                    </svg>
                                </div>
                            </div>
                            @error('form.email')
                                <span class="text-[11px] text-red-600 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    wire:model="form.password"
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    style="padding-left: 2.75rem !important; padding-right: 1rem !important;"
                                    class="w-full h-11 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 text-xs focus:border-[#174668] focus:ring-2 focus:ring-[#174668]/15 focus:outline-none transition shadow-2xs"
                                />
                                <div class="absolute inset-y-0 left-0 w-11 flex items-center justify-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </div>
                            </div>
                            @error('form.password')
                                <span class="text-[11px] text-red-600 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="flex items-center justify-between pt-0.5">
                            <label for="remember" class="inline-flex items-center cursor-pointer select-none">
                                <input
                                    wire:model="form.remember"
                                    id="remember"
                                    type="checkbox"
                                    class="rounded border-slate-300 text-[#174668] shadow-2xs focus:ring-[#174668] w-4 h-4 cursor-pointer"
                                    name="remember"
                                />
                                <span class="ms-2 text-xs text-slate-600 font-medium">Ingat sesi masuk saya</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        @php
                            $roleBtnNames = [
                                'admin' => 'Masuk sebagai Administrator',
                                'ketua_kepk' => 'Masuk sebagai Ketua KEPK',
                                'asessor' => 'Masuk sebagai Asesor Penilai',
                                'anggota' => 'Masuk sebagai Anggota KEPK',
                            ];
                        @endphp
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full h-11 flex items-center justify-center px-6 rounded-xl font-bold text-xs sm:text-sm text-white bg-[#174668] hover:bg-[#133a57] active:scale-[0.99] transition shadow-md shadow-[#174668]/20 focus:outline-none focus:ring-2 focus:ring-[#174668] focus:ring-offset-2 disabled:opacity-75 disabled:cursor-not-allowed cursor-pointer tracking-wide"
                        >
                            <span wire:loading.remove wire:target="login">
                                {{ $roleBtnNames[$selectedRole] ?? 'Masuk ke Portal' }} &rarr;
                            </span>
                            <span wire:loading wire:target="login" style="display: none;">
                                Memverifikasi Akun & Hak Akses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
