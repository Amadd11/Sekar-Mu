@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-4 w-full">
        <!-- Showing Results Text -->
        <div class="text-xs text-slate-500 font-medium">
            <span>Menampilkan</span>
            <span class="font-bold text-slate-900 font-mono">{{ $paginator->firstItem() }}</span>
            <span>sampai</span>
            <span class="font-bold text-slate-900 font-mono">{{ $paginator->lastItem() }}</span>
            <span>dari</span>
            <span class="font-bold text-slate-900 font-mono">{{ $paginator->total() }}</span>
            <span>data</span>
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center gap-1.5">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-400 bg-slate-100/60 border border-slate-200/60 rounded-xl cursor-not-allowed select-none">
                    <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition cursor-pointer"
                >
                    <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="flex items-center gap-1">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-2 py-1.5 text-xs font-bold text-slate-400 font-mono">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-3.5 py-1.5 text-xs font-bold text-white bg-primary-700 rounded-xl shadow-xs ring-2 ring-primary-700/20 font-mono select-none">
                                    {{ $page }}
                                </span>
                            @else
                                <a
                                    href="{{ $url }}"
                                    class="px-3.5 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition cursor-pointer font-mono hover:text-primary-700"
                                >
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition cursor-pointer"
                >
                    <span class="hidden sm:inline">Berikutnya</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </a>
            @else
                <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-400 bg-slate-100/60 border border-slate-200/60 rounded-xl cursor-not-allowed select-none">
                    <span class="hidden sm:inline">Berikutnya</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </span>
            @endif
        </div>
    </nav>
@endif
