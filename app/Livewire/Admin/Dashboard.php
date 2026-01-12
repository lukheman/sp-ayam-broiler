<?php

namespace App\Livewire\Admin;

use App\Models\BasisPengetahuan;
use App\Models\Gejala;
use App\Models\Penyakit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.admin.livewire-layout')]
#[Title('Dashboard - SP Ayam Broiler')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'penyakitCount' => Penyakit::count(),
            'gejalaCount' => Gejala::count(),
            'basisPengetahuanCount' => BasisPengetahuan::count(),
        ]);
    }
}
