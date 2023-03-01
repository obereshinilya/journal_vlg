<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\User;

class LiveTable extends Component
{
    use WithPagination;

  //  public $sortField = 'name'; // default sorting field
 //   public $sortAsc = true; // default sort direction
//    public $filters = '';
    public $search = '';
    public $users;

     public function render()
    {
//        if ( $this->search<>'') {
            $search = '%' . $this->search . '%';
            $this->users = User::where('name','ilike', $search)->orderBy('id')->get();
            return view('livewire.live-table');
//        }
//        else
////        {
//            $this->users = User::orderby('id')->get();
//            return view('livewire.live-table');
//        }
        //, [
//            'users' => //User::query()//search($this->search)
//                User::where (['name', 'ilike', $search])
//
////                ->when($this->filters, function($query, $status)
////                {
////                    return $query->where('id', '=', '52');
////                })
//           //     ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
//                  ->simplePaginate(10)
//        ]);
    }
}
