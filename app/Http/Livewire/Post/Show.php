<?php

namespace App\Http\Livewire\Post;

use App\Models\Calc_ip_opo_i;
use Livewire\WithPagination;
use App\User;
use Livewire\Component;


class Show extends Component
{
    use WithPagination;
    public $search = '';
    public $users;
    public $name;
    public $from_type_obj;
    public $event_id;
   // public $filters = '';
    public $showEditModal = false;
  //  public Event_types $editing;






    private function resetInputFields(){
        $this->name = '';
        $this->from_type_obj = '';
    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'from_type_obj' => 'required',
        ]);
         Event_types::create($validatedDate);

       // session()->flash('message', 'Events Created Successfully.');

         $this->resetInputFields();

      //  $this->emit('userStore'); // Close model to using to jquery
        return redirect()->to('/search/1');

    }
    public function render()
    {
        if ($this->search <>'') {
            $search = '%' . $this->search . '%';
         //   $this->events = Event_types::orwhere('from_type_obj', '=', $this->search)->orderBy('id')->get();
            return view('livewire.post.show', [
                'events'=> Calc_ip_opo_i::orwhere('date', '>', $this->search)->orderBy('id')->simplePaginate(20),
            ]);
        }
        else
        {
            //  $search = '%' . $this->search . '%';
          //  $this->users = Event_types::orderby('id')->paginate(15);
            return view('livewire.post.show', [
                'events'=>Calc_ip_opo_i::orderby('id')->simplePaginate(20),
            ]);
        }

    }
    public function delete($id)
    {
        if($id){
            Event_types::where('id',$id)->delete();
            session()->flash('message', 'Users Deleted Successfully.');
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $event= Event_types::where('id',$id)->first();
        $this->event_id = $id;
        $this->name = $event->name;
        $this->from_type_obj = $event->from_type_obj;

    }
    public function update()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'from_type_obj' => 'required',
        ]);

        if ($this->event_id) {
            $event = Event_types::find($this->event_id);
            $event->update([
                'name' => $this->name,
                'from_type_obj' => $this->from_type_obj,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Users Updated Successfully.');
            $this->resetInputFields();

        }
    }

}
