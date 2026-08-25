<?php

namespace App\Livewire\Admin;

use App\Models\BagianEvaluasi;
use App\Models\ButirEvaluasi;
use App\Models\KelompokEvaluasi;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class KriteriaEvaluasi extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $selectedBagian = '';
    public string $selectedKelompok = '';
    public string $criticalFilter = '';
    public int $perPage = 15;

    // Modal State for Butir (Kriteria & Acuan)
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    // Form fields for Butir
    public ?int $bagian_evaluasi_id = null;
    public ?int $kelompok_evaluasi_id = null;
    public string $kode = '';
    public string $pertanyaan = '';
    public bool $is_critical = false;
    public string $standar = '';
    public string $parameter = '';
    public string $evidence_required = '';

    // Modal State for Kelompok (Acuan Kategori)
    public bool $showKelompokModal = false;
    public ?int $kelompok_bagian_id = null;
    public string $kelompok_nama = '';
    public int $kelompok_urutan = 1;

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'kelompok_evaluasi_id' => ['required', 'exists:kelompok_evaluasi,id'],
            'kode' => ['nullable', 'string', 'max:50'],
            'pertanyaan' => ['required', 'string', 'min:5'],
            'is_critical' => ['boolean'],
            'standar' => ['nullable', 'string', 'max:255'],
            'parameter' => ['nullable', 'string'],
            'evidence_required' => ['nullable', 'string'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSelectedBagian(): void
    {
        $this->selectedKelompok = '';
        $this->resetPage();
    }

    public function updatingSelectedKelompok(): void
    {
        $this->resetPage();
    }

    public function updatingCriticalFilter(): void
    {
        $this->resetPage();
    }

    public function updatedBagianEvaluasiId($value): void
    {
        if ($value) {
            $firstKelompok = KelompokEvaluasi::where('bagian_evaluasi_id', $value)->first();
            $this->kelompok_evaluasi_id = $firstKelompok?->id;
        } else {
            $this->kelompok_evaluasi_id = null;
        }
    }

    public function bukaModalCreate(): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->editingId = null;

        $firstBagian = BagianEvaluasi::orderBy('urutan')->first();
        $this->bagian_evaluasi_id = $firstBagian?->id;

        $firstKelompok = $firstBagian ? KelompokEvaluasi::where('bagian_evaluasi_id', $firstBagian->id)->first() : null;
        $this->kelompok_evaluasi_id = $firstKelompok?->id;

        $this->kode = '';
        $this->pertanyaan = '';
        $this->is_critical = false;
        $this->standar = 'Standar WHO-CIOMS & KNEPK';
        $this->parameter = '';
        $this->evidence_required = '';

        $this->showModal = true;
    }

    public function bukaModalEdit(int $id): void
    {
        $this->resetValidation();
        $butir = ButirEvaluasi::with('kelompok')->findOrFail($id);

        $this->isEditing = true;
        $this->editingId = $butir->id;
        $this->bagian_evaluasi_id = $butir->kelompok?->bagian_evaluasi_id;
        $this->kelompok_evaluasi_id = $butir->kelompok_evaluasi_id;
        $this->kode = $butir->kode ?? '';
        $this->pertanyaan = $butir->pertanyaan;
        $this->is_critical = (bool) $butir->is_critical;
        $this->standar = $butir->standar ?? '';
        $this->parameter = $butir->parameter ?? '';
        $this->evidence_required = $butir->evidence_required ?? '';

        $this->showModal = true;
    }

    public function tutupModal(): void
    {
        $this->showModal = false;
    }

    public function simpanKriteria(): void
    {
        $validated = $this->validate();

        if (empty($validated['kode'])) {
            $kelompok = KelompokEvaluasi::with('bagian')->find($this->kelompok_evaluasi_id);
            $countInKelompok = ButirEvaluasi::where('kelompok_evaluasi_id', $this->kelompok_evaluasi_id)->count();
            $validated['kode'] = ($kelompok?->bagian?->kode ?? 'A') . ($kelompok?->urutan ?? 1) . '.' . ($countInKelompok + 1);
        }

        if ($this->isEditing && $this->editingId) {
            $butir = ButirEvaluasi::with('kelompok.bagian')->findOrFail($this->editingId);
            $butir->update($validated);
            $kodeItem = $butir->kode;
            session()->flash('status', "Kriteria & Acuan Butir {$kodeItem} berhasil diperbarui!");
        } else {
            $butir = ButirEvaluasi::create($validated);
            $kodeItem = $butir->kode;
            session()->flash('status', "Kriteria & Acuan Butir {$kodeItem} baru berhasil ditambahkan!");
        }

        $this->showModal = false;
    }

    public function hapusKriteria(int $id): void
    {
        $butir = ButirEvaluasi::with('kelompok.bagian')->findOrFail($id);
        $kodeItem = $butir->kode;
        $butir->delete();

        session()->flash('status', "Kriteria Butir {$kodeItem} berhasil dihapus dari sistem.");
    }

    // Modal Kelompok Acuan
    public function bukaModalKelompok(): void
    {
        $this->kelompok_bagian_id = BagianEvaluasi::orderBy('urutan')->first()?->id;
        $this->kelompok_nama = '';
        $this->kelompok_urutan = (KelompokEvaluasi::max('urutan') ?? 0) + 1;
        $this->showKelompokModal = true;
    }

    public function tutupModalKelompok(): void
    {
        $this->showKelompokModal = false;
    }

    public function simpanKelompok(): void
    {
        $this->validate([
            'kelompok_bagian_id' => ['required', 'exists:bagian_evaluasi,id'],
            'kelompok_nama' => ['required', 'string', 'min:3', 'max:255'],
            'kelompok_urutan' => ['required', 'integer', 'min:1'],
        ]);

        KelompokEvaluasi::create([
            'bagian_evaluasi_id' => $this->kelompok_bagian_id,
            'nama' => $this->kelompok_nama,
            'urutan' => $this->kelompok_urutan,
        ]);

        session()->flash('status', "Kelompok Acuan Standar '{$this->kelompok_nama}' berhasil ditambahkan!");
        $this->showKelompokModal = false;
    }

    public function render(): View
    {
        // Bagian & Kelompok for dropdowns
        $daftarBagian = BagianEvaluasi::orderBy('urutan')->get();
        $daftarKelompok = KelompokEvaluasi::query()
            ->when($this->selectedBagian, fn($q) => $q->where('bagian_evaluasi_id', $this->selectedBagian))
            ->orderBy('urutan')
            ->get();

        $modalKelompokOptions = KelompokEvaluasi::query()
            ->when($this->bagian_evaluasi_id, fn($q) => $q->where('bagian_evaluasi_id', $this->bagian_evaluasi_id))
            ->orderBy('urutan')
            ->get();

        // Main Query
        $query = ButirEvaluasi::query()
            ->join('kelompok_evaluasi', 'butir_evaluasi.kelompok_evaluasi_id', '=', 'kelompok_evaluasi.id')
            ->join('bagian_evaluasi', 'kelompok_evaluasi.bagian_evaluasi_id', '=', 'bagian_evaluasi.id')
            ->select('butir_evaluasi.*')
            ->with(['kelompok.bagian']);

        if ($this->selectedBagian) {
            $query->where('bagian_evaluasi.id', $this->selectedBagian);
        }

        if ($this->selectedKelompok) {
            $query->where('butir_evaluasi.kelompok_evaluasi_id', $this->selectedKelompok);
        }

        if ($this->criticalFilter === 'critical') {
            $query->where('butir_evaluasi.is_critical', true);
        } elseif ($this->criticalFilter === 'standard') {
            $query->where('butir_evaluasi.is_critical', false);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('butir_evaluasi.pertanyaan', 'like', "%{$this->search}%")
                    ->orWhere('butir_evaluasi.kode', 'like', "%{$this->search}%")
                    ->orWhere('butir_evaluasi.standar', 'like', "%{$this->search}%")
                    ->orWhere('butir_evaluasi.parameter', 'like', "%{$this->search}%")
                    ->orWhere('butir_evaluasi.evidence_required', 'like', "%{$this->search}%");
            });
        }

        $query->orderBy('bagian_evaluasi.urutan', 'asc')
            ->orderBy('kelompok_evaluasi.urutan', 'asc')
            ->orderBy('butir_evaluasi.id', 'asc');

        // Summary KPI
        $totalButir = ButirEvaluasi::count();
        $totalKritis = ButirEvaluasi::where('is_critical', true)->count();
        $totalKelompokCount = KelompokEvaluasi::count();
        $totalBagianCount = BagianEvaluasi::count();

        return view('livewire.admin.kriteria-evaluasi', [
            'butirList' => $query->paginate($this->perPage),
            'daftarBagian' => $daftarBagian,
            'daftarKelompok' => $daftarKelompok,
            'modalKelompokOptions' => $modalKelompokOptions,
            'totalButir' => $totalButir,
            'totalKritis' => $totalKritis,
            'totalKelompokCount' => $totalKelompokCount,
            'totalBagianCount' => $totalBagianCount,
        ])->layout('layouts.app');
    }
}
