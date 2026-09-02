<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-slate-100 min-h-screen">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'SekarMU' }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-[#f7fafc] min-h-screen" x-data="{ sidebarOpen: false, logoutModalOpen: false }">
        <div class="min-h-screen flex">
            <!-- Mobile Sidebar Backdrop -->
            <div
                x-show="sidebarOpen"
                @click="sidebarOpen = false"
                class="fixed inset-0 z-40 bg-slate-900/25 lg:hidden"
                style="display: none;"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>

            <!-- Left Sidebar Partial -->
            @include('layouts.partials.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Topbar Partial -->
                @include('layouts.partials.topbar')

                <!-- Page Body -->
                <main class="p-4 sm:p-6 lg:p-8 flex-1 bg-slate-100/80">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Global Modal Konfirmasi Logout -->
        <div
            x-show="logoutModalOpen"
            @keydown.escape.window="logoutModalOpen = false"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <!-- Backdrop -->
            <div
                x-show="logoutModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="logoutModalOpen = false"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"
            ></div>

            <!-- Modal Dialog Container -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    x-show="logoutModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all w-full max-w-sm border border-slate-200/90 my-8 p-6 text-center space-y-5"
                >
                    <!-- Warning Icon Badge -->
                    <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 border border-rose-200/70 flex items-center justify-center mx-auto shadow-2xs">
                        <span class="material-symbols-outlined text-[36px]">power_settings_new</span>
                    </div>

                    <!-- Content -->
                    <div class="space-y-1.5">
                        <h3 class="font-display text-lg font-extrabold text-slate-900 leading-snug">
                            Konfirmasi Keluar Akses
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed px-2">
                            Apakah Anda yakin ingin keluar dari sesi kerja akun <strong class="text-slate-700 font-semibold">{{ auth()->user()?->name }}</strong>?
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button
                            type="button"
                            @click="logoutModalOpen = false"
                            class="w-full px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition cursor-pointer border border-slate-200/80"
                        >
                            Batal
                        </button>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button
                                type="submit"
                                class="w-full px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:bg-rose-800 transition cursor-pointer shadow-sm shadow-rose-600/20 flex items-center justify-center gap-1.5"
                            >
                                <span class="material-symbols-outlined text-[16px]">logout</span>
                                <span>Ya, Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
