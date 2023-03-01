<?php

namespace App\Http\Livewire\Maps;

use App\Ref_opo;
use Livewire\Component;

class Spt extends Component
{
    public function render()
    {
        return view('livewire.maps.spt',['opo' =>Ref_opo::find(2)]);
    }
}
