<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\StrukturaPAO;

class MapGda extends Component
{
    public function render()
    {
        return view('livewire.map-gda', ['rows'=> StrukturaPAO::orderby('id')->get()]);
    }
}
