<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.landing.layout')]
#[Title('SP Ayam Broiler - Sistem Pakar Diagnosa Penyakit Ayam Broiler')]
class LandingPage extends Component
{
    public function render()
    {
        return view('livewire.landing-page');
    }
}
