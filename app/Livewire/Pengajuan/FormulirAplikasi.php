<?php

namespace App\Livewire\Pengajuan;

use App\Models\SuratPengajuan;
use App\Services\PengajuanService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class FormulirAplikasi extends Component
{
    public SuratPengajuan $suratPengajuan;

    public string $nama_institusi = '';
    public string $singkatan = '';
    public string $alamat = '';
    public string $kota = '';
    public string $telepon = '';
    public string $email = '';

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'nama_institusi' => ['required', 'string', 'max:255'],
            'singkatan' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'kota' => ['nullable', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
        ];
    }

    public function mount(SuratPengajuan $suratPengajuan): void
    {
        $this->authorize('update', $suratPengajuan);

        $this->suratPengajuan = $suratPengajuan->load('formulirAplikasi');

        if ($form = $suratPengajuan->formulirAplikasi) {
            $this->nama_institusi = $form->nama_institusi ?? '';
            $this->singkatan = $form->singkatan ?? '';
            $this->alamat = $form->alamat ?? '';
            $this->kota = $form->kota ?? '';
            $this->telepon = $form->telepon ?? '';
            $this->email = $form->email ?? '';
        }
    }

    public function save(PengajuanService $service): void
    {
        $validated = $this->validate();

        $service->updateFormulirAplikasi($this->suratPengajuan, $validated);

        session()->flash('status', 'Data Formulir Aplikasi (B01-02) berhasil disimpan.');
    }

    public function render(): View
    {
        return view('livewire.pengajuan.formulir-aplikasi')->layout('layouts.app');
    }
}
