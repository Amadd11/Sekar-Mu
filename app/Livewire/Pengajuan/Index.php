<?php

namespace App\Livewire\Pengajuan;

use App\Models\Kepk;
use App\Models\SuratPengajuan;
use App\Services\PengajuanService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    // Modal Create State
    public bool $showCreateModal = false;
    public ?int $kepk_id = null;
    public string $nama_institusi = '';
    public string $singkatan = '';
    public string $alamat = '';
    public string $kota = '';
    public string $telepon = '';
    public string $email = '';
    public string $deskripsi = '';
    public string $visi = '';
    public string $misi = '';

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'kepk_id' => ['required', 'exists:kepk,id'],
            'nama_institusi' => ['required', 'string', 'max:255'],
            'singkatan' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'kota' => ['nullable', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'visi' => ['nullable', 'string'],
            'misi' => ['nullable', 'string'],
        ];
    }

    public function mount(): void
    {
        if (request()->query('create') == '1') {
            $this->bukaModalCreate();
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

    public function bukaModalCreate(): void
    {
        $this->resetValidation();
        $this->reset(['nama_institusi', 'singkatan', 'alamat', 'kota', 'telepon', 'email', 'deskripsi', 'visi', 'misi']);
        
        $defaultKepk = Kepk::where('status', 'active')->first() ?? Kepk::first();
        if ($defaultKepk) {
            $this->kepk_id = $defaultKepk->id;
        }

        $this->showCreateModal = true;
    }

    public function tutupModalCreate(): void
    {
        $this->showCreateModal = false;
    }

    public function simpanPengajuan(PengajuanService $service)
    {
        $validated = $this->validate();

        $surat = $service->createDraft(auth()->user(), $validated);

        session()->flash('status', 'Draft surat pengajuan berhasil dibuat! Silakan lanjutkan pengisian berkas evaluasi diri.');

        return $this->redirect(route('pengajuan.show', $surat), navigate: true);
    }

    public function render(): View
    {
        $user = auth()->user();

        $query = SuratPengajuan::query()
            ->with(['kepk.institusi', 'formulirAplikasi', 'penilai', 'penilaianEtik']);

        if (! $user->isAdmin() && ! $user->isReviewer() && ! $user->isApplicant()) {
            $query->where('user_id', $user->id);
        }

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

        return view('livewire.pengajuan.index', [
            'pengajuanList' => $query->latest()->paginate(10),
            'daftarKepk' => Kepk::with('institusi')->where('status', 'active')->get(),
        ])->layout('layouts.app');
    }
}
