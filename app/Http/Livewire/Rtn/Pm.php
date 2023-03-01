<?php

namespace App\Http\Livewire\Rtn;

use App\Models\Rtn\Pmla;
use Livewire\Component;
use Livewire\WithPagination;

class Pm extends Component
{
    use WithPagination;
    public $search = '';
    public $users;
    public $name_crash;
    public $level_crash;
    public $place_crash;
    public $sign_crash;
    public $metod_protect;
    public $name_paz;
    public $name_f;
    public $name_l;
    public $name_p;
    public $education_worker;
    public $exper_worker;
    public $date_certif;
    public $order_action;
    public $comments;
    public $year_report;
    public $pmla_id;

    public function render()
    {
        return view('livewire.rtn.pm', ['rows'=>Pmla::orderby('id')->get()]);
    }

    private function resetInputFields(){
        $this->name_crash = '';
        $this->level_crash = '';
        $this->place_crash = '';
        $this->sign_crash = '';
        $this->metod_protect = '';
        $this->name_paz = '';
        $this->name_f = '';
        $this->name_l = '';
        $this->name_p = '';
        $this->education_worker = '';
        $this->exper_worker = '';
        $this->date_certif = '';
        $this->order_action = '';
        $this->comments = '';
        $this->year_report = '';
    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'name_crash'=> 'required',
            'level_crash'=> 'required',
            'place_crash'=> 'required',
            'sign_crash'=> 'required',
            'metod_protect'=> 'required',
            'name_paz'=> 'required',
            'name_f'=> 'required',
            'name_l'=> 'required',
            'name_p'=> 'required',
            'education_worker'=> 'required',
            'exper_worker'=> 'required',
            'date_certif'=> 'required',
            'order_action'=> 'required',
            'comments'=> 'required',
            'year_report'=> 'required',
        ]);
        Pmla::create($validatedDate);
        $this->resetInputFields();
        return redirect()->to('/docs/events');

    }

    public function delete($id)
    {
        if($id){
            Pmla::where('id',$id)->delete();
            session()->flash('message', 'Data Deleted Successfully.');
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $plan= Pmla::where('id',$id)->first();
        $this->pmla_id = $id;
        $this->name_crash = $plan->name_crash;
        $this->level_crash = $plan->level_crash;
        $this->place_crash = $plan->place_crash;
        $this->sign_crash = $plan->sign_crash;
        $this->metod_protect = $plan->metod_protect;
        $this->name_paz = $plan->name_paz;
        $this->name_f= $plan->name_f;
        $this->name_l= $plan->name_l;
        $this->name_p= $plan->name_p;
        $this->education_worker = $plan->education_worker;
        $this->exper_worker = $plan->exper_worker;
        $this->date_certif = $plan->date_certif;
//        $this->order_action = $plan->order_action;
        $this->comments = $plan->comments;
        $this->year_report = $plan->year_report;

    }

    public function update()
    {
        $validatedDate = $this->validate([
            'name_crash'=> 'required',
            'level_crash'=> 'required',
            'place_crash'=> 'required',
            'sign_crash'=> 'required',
            'metod_protect'=> 'required',
            'name_paz'=> 'required',
            'name_f'=> 'required',
            'name_l'=> 'required',
            'name_p'=> 'required',
            'education_worker'=> 'required',
            'exper_worker'=> 'required',
            'date_certif'=> 'required',
            'order_action'=> 'required',
            'comments'=> 'required',
            'year_report'=> 'required',

        ]);

        if ($this->pmla_id) {
            $plan = Pmla::find($this->pmla_id);
            $plan ->update([
                'name_crash' => $this->name_crash,
                'level_crash' => $this->level_crash,
                'place_crash' => $this->place_crash,
                'sign_crash' => $this->sign_crash,
                'metod_protect' => $this->metod_protect,
                'name_paz' => $this->name_paz,
                'name_f' => $this->name_f,
                'name_l' => $this->name_l,
                'name_p' => $this->name_p,
                'education_worker' => $this->education_worker,
                'exper_worker' => $this->exper_worker,
                'date_certif' => $this->date_certif,
//                'order_action' => $this->order_action,
                'comments' => $this->comments,
                'year_report' => $this->year_report,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Data Updated Successfully.');
            $this->resetInputFields();
            return redirect()->to('/docs/events');
        }

    }
}
