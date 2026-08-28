<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-slate-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-8 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        <svg class="w-4 h-4 me-1.5 inline-block text-slate-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @hasrole('anggota')
                        @if ($latestApp)
                            <x-nav-link :href="route('pengajuan.evaluasi-diri', $latestApp)" :active="request()->routeIs('pengajuan.evaluasi-diri')" wire:navigate>
                                <svg class="w-4 h-4 me-1.5 inline-block text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                {{ __('Evaluasi Diri') }}
                            </x-nav-link>

                            <x-nav-link :href="route('pengajuan.show', $latestApp)" :active="request()->routeIs('pengajuan.show')" wire:navigate>
                                <svg class="w-4 h-4 me-1.5 inline-block text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Hasil Akreditasi') }}
                            </x-nav-link>
                        @endif
                    @endhasrole

                    @hasanyrole('ketua_kepk|admin')
                        <x-nav-link :href="route('pengajuan.index')" :active="request()->routeIs('pengajuan.*')" wire:navigate>
                            <svg class="w-4 h-4 me-1.5 inline-block text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('Pengajuan Etik') }}
                        </x-nav-link>
                    @endhasanyrole

                    @hasanyrole('asessor|admin')
                        <x-nav-link :href="route('penilaian.index')" :active="request()->routeIs('penilaian.*')" wire:navigate>
                            <svg class="w-4 h-4 me-1.5 inline-block text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            {{ __('Telaah Etik') }}
                        </x-nav-link>
                    @endhasanyrole

                    @role('admin')
                        <x-nav-link :href="route('admin.kriteria.index')" :active="request()->routeIs('admin.kriteria.*')" wire:navigate>
                            <svg class="w-4 h-4 me-1.5 inline-block text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            {{ __('Kriteria & Acuan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" wire:navigate>
                            <svg class="w-4 h-4 me-1.5 inline-block text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            {{ __('Manajemen Akun') }}
                        </x-nav-link>
                    @endrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 border border-slate-200 text-sm leading-4 font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition ease-in-out duration-150">
                            <div class="w-7 h-7 rounded-full bg-primary-100 text-primary-800 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="text-start">
                                <div class="font-medium text-xs text-slate-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                                <div class="text-[10px] text-slate-400">
                                    {{ auth()->user()->roles->first()?->name ?? 'User' }}
                                </div>
                            </div>

                            <svg class="fill-current h-4 w-4 text-slate-400 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-slate-400 border-b border-slate-100">
                            {{ auth()->user()->email }}
                        </div>

                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profil Pengguna') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link class="text-red-600 hover:bg-red-50">
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @hasrole('anggota')
                @if ($latestApp)
                    <x-responsive-nav-link :href="route('pengajuan.evaluasi-diri', $latestApp)" :active="request()->routeIs('pengajuan.evaluasi-diri')" wire:navigate>
                        {{ __('Evaluasi Diri') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('pengajuan.show', $latestApp)" :active="request()->routeIs('pengajuan.show')" wire:navigate>
                        {{ __('Hasil Akreditasi') }}
                    </x-responsive-nav-link>
                @endif
            @endhasrole

            @hasanyrole('ketua_kepk|admin')
                <x-responsive-nav-link :href="route('pengajuan.index')" :active="request()->routeIs('pengajuan.*')" wire:navigate>
                    {{ __('Pengajuan Etik') }}
                </x-responsive-nav-link>
            @endhasanyrole

            @hasanyrole('asessor|admin')
                <x-responsive-nav-link :href="route('penilaian.index')" :active="request()->routeIs('penilaian.*')" wire:navigate>
                    {{ __('Telaah Etik') }}
                </x-responsive-nav-link>
            @endhasanyrole

            @role('admin')
                <x-responsive-nav-link :href="route('admin.kriteria.index')" :active="request()->routeIs('admin.kriteria.*')" wire:navigate>
                    {{ __('Kriteria & Acuan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" wire:navigate>
                    {{ __('Manajemen Akun') }}
                </x-responsive-nav-link>
            @endrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-200">
            <div class="px-4">
                <div class="font-medium text-base text-slate-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-slate-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profil Pengguna') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link class="text-red-600">
                        {{ __('Keluar (Log Out)') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
