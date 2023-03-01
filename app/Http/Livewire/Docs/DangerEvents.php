<?php

namespace App\Http\Livewire\Docs;

use Livewire\Component;
use App\Models\Matrix\DangerousEvent;
use Livewire\WithPagination;

class DangerEvents extends Component
{
    use WithPagination;
    public $search = '';

    public function render()
    {
        if ($this->search <>'')
            return view('livewire.docs.danger-events', ['rows' => DangerousEvent::orwhere('from_type_obj', '=', $this->search)->orderby('id')->get()]);
        else
             return view('livewire.docs.danger-events', ['rows' => DangerousEvent::orderby('id')->get()]);

    }
}
