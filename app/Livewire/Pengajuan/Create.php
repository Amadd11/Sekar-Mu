<?php

namespace App\Livewire\Pengajuan;

use App\Models\Kepk;
use App\Services\PengajuanService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
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
        $defaultKepk = Kepk::first();
        if ($defaultKepk) {
            $this->kepk_id = $defaultKepk->id;
        }
    }

    public function save(PengajuanService $service)
    {
        $validated = $this->validate();

        $surat = $service->createDraft(auth()->user(), $validated);

        session()->flash('status', 'Draft surat pengajuan berhasil dibuat! Silakan lanjutkan pengisian berkas evaluasi diri.');

        return $this->redirect(route('pengajuan.show', $surat), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pengajuan.create', [
            'daftarKepk' => Kepk::with('institusi')->where('status', 'active')->get(),
        ])->layout('layouts.app');
    }
}
