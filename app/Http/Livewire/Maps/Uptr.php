<?php

namespace App\Http\Livewire\Maps;

use App\Ref_opo;
use Livewire\Component;

class Uptr extends Component
{
    public function render()
    {
        return view('livewire.maps.uptr',['opo' =>Ref_opo::find(9)]);
    }
}
