<?php

namespace App\Livewire\Penilaian;

use App\Models\SuratPengajuan;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function mount(): void
    {
        if (! auth()->user()->isAsessor() && ! auth()->user()->isAdmin()) {
            abort(403, 'Akses terbatas untuk penilai etik dan admin.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $user = auth()->user();

        $query = SuratPengajuan::query()
            ->with(['kepk.institusi', 'formulirAplikasi', 'penilai', 'penilaianEtik']);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('formulirAplikasi', function ($sub) {
                    $sub->where('nama_institusi', 'like', "%{$this->search}%")
                        ->orWhere('kota', 'like', "%{$this->search}%");
                })->orWhere('id', 'like', "%{$this->search}%");
            });
        }

        return view('livewire.penilaian.index', [
            'pengajuanList' => $query->latest()->paginate(10),
        ])->layout('layouts.app');
    }
}
