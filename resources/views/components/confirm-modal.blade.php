@props([
    'show' => false,
    'title' => 'Konfirmasi Tindakan',
    'type' => 'danger', // 'danger', 'warning', 'info'
    'icon' => 'warning',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Batalkan',
    'onConfirm' => '',
    'onCancel' => '',
])

@php
    $typeClasses = [
        'danger' => [
            'icon_bg' => 'bg-rose-50 text-rose-600 border-rose-200/70',
            'btn' => 'bg-rose-600 hover:bg-rose-700 active:bg-rose-800 shadow-rose-600/20 text-white',
        ],
        'warning' => [
            'icon_bg' => 'bg-amber-50 text-amber-600 border-amber-200/70',
            'btn' => 'bg-amber-600 hover:bg-amber-700 active:bg-amber-800 shadow-amber-600/20 text-white',
        ],
        'info' => [
            'icon_bg' => 'bg-teal-50 text-teal-700 border-teal-200/70',
            'btn' => 'bg-teal-700 hover:bg-teal-600 active:bg-teal-800 shadow-teal-700/20 text-white',
        ],
    ][$type] ?? [
        'icon_bg' => 'bg-rose-50 text-rose-600 border-rose-200/70',
        'btn' => 'bg-rose-600 hover:bg-rose-700 text-white',
    ];
@endphp

@if ($show)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop Overlay -->
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" @if($onCancel) wire:click="{{ $onCancel }}" @endif></div>

        <!-- Modal Dialog Box -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative z-10 transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 p-6 sm:p-7 text-center space-y-5">
                <!-- Icon Chip -->
                <div class="w-16 h-16 rounded-2xl border flex items-center justify-center mx-auto shadow-2xs {{ $typeClasses['icon_bg'] }}">
                    <span class="material-symbols-outlined text-[36px]">{{ $icon }}</span>
                </div>

                <!-- Title & Body Slot -->
                <div class="space-y-2">
                    <h3 class="font-display text-lg font-extrabold text-slate-900 leading-snug" id="modal-title">
                        {{ $title }}
                    </h3>

                    <div class="text-xs text-slate-500 leading-relaxed px-2 space-y-2">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button
                        type="button"
                        @if($onCancel) wire:click="{{ $onCancel }}" @endif
                        class="w-full px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition cursor-pointer border border-slate-200/80"
                    >
                        {{ $cancelText }}
                    </button>

                    <button
                        type="button"
                        @if($onConfirm) wire:click="{{ $onConfirm }}" @endif
                        class="w-full px-4 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer shadow-sm flex items-center justify-center gap-1.5 {{ $typeClasses['btn'] }}"
                    >
                        <span class="material-symbols-outlined text-[16px]" wire:loading.remove @if($onConfirm) wire:target="{{ $onConfirm }}" @endif>check</span>
                        <span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" wire:loading @if($onConfirm) wire:target="{{ $onConfirm }}" @endif></span>
                        <span wire:loading.remove @if($onConfirm) wire:target="{{ $onConfirm }}" @endif>{{ $confirmText }}</span>
                        <span wire:loading @if($onConfirm) wire:target="{{ $onConfirm }}" @endif>Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
