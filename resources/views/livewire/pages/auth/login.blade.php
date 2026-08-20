<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-4xl bg-white border border-slate-200/90 rounded-3xl shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[560px]">
        <!-- Left Hero / Branding Panel -->
        <div class="lg:col-span-5 bg-gradient-to-br from-[#174668] via-[#154162] to-[#0f3450] text-white p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10 space-y-6">
                <!-- Mascot & Brand Logo -->
                <div class="flex items-center gap-3">
                    <div class="text-4xl select-none">🌸</div>
                    <div>
                        <div class="text-2xl font-black tracking-tight text-white leading-none">Sekar-Mu</div>
                        <div class="text-[10px] font-bold tracking-widest text-pink-300 uppercase mt-1">BUNGA SEKAR 🌸</div>
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
                            Evaluasi Diri Standar WHO-CIOMS (155 Butir Instrumen)
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

            <div class="relative z-10 pt-6 mt-6 border-t border-white/10 text-[11px] text-teal-200/60">
                &copy; {{ date('Y') }} KEPK UMY • Seluruh Hak Cipta Dilindungi
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="lg:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto space-y-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Masuk ke Sistem</h1>
                    <p class="text-xs text-slate-500 mt-1">Masukkan alamat email dan kata sandi untuk mengakses layanan.</p>
                </div>

                <!-- Session Status Alert -->
                <x-auth-session-status class="text-xs text-teal-800 bg-teal-50 p-3.5 rounded-xl border border-teal-200" :status="session('status')" />

                <!-- Login Form -->
                <form wire:submit="login" class="space-y-4" x-data="{ showPassword: false }">
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
                                class="w-full rounded-xl border-slate-300 text-slate-900 placeholder-slate-400 text-xs px-3.5 py-2.5 ps-10 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/10 transition shadow-2xs"
                            />
                            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        @error('form.email')
                            <span class="text-[11px] text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-700">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-teal-700 hover:underline" wire:navigate>
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input
                                wire:model="form.password"
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full rounded-xl border-slate-300 text-slate-900 placeholder-slate-400 text-xs px-3.5 py-2.5 ps-10 pe-10 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/10 transition shadow-2xs"
                            />
                            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-600 focus:outline-none"
                                tabindex="-1"
                            >
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('form.password')
                            <span class="text-[11px] text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember" class="inline-flex items-center cursor-pointer">
                            <input
                                wire:model="form.remember"
                                id="remember"
                                type="checkbox"
                                class="rounded border-slate-300 text-teal-700 shadow-2xs focus:ring-teal-600 w-4 h-4"
                                name="remember"
                            />
                            <span class="ms-2 text-xs text-slate-600 font-medium">Ingat sesi masuk saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl font-bold text-xs text-white bg-teal-700 hover:bg-teal-800 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 shadow-2xs transition duration-150"
                        >
                            <span wire:loading.remove wire:target="login">Masuk ke Portal &rarr;</span>
                            <span wire:loading wire:target="login" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
