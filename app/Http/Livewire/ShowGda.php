<?php

namespace App\Http\Livewire;

use App\Models\StrukturaPAO;
use Livewire\Component;

class ShowGda extends Component
{
    public function render()
    {
        return view('livewire.show-gda', ['rows'=> StrukturaPAO::orderby('id')->get()]);
    }
}
