<?php

namespace App\Http\Livewire\Rtn;

use App\Models\Rtn\Data_result_check;
use Livewire\Component;
use Livewire\WithPagination;

class DataResultCheck extends Component
{
    use WithPagination;
    public $search = '';
    public $users;
    public $num_order;
    public $date_order;
    public $name_order;
    public $desc_order;
    public $name_f;
    public $name_l;
    public $name_p;
    public $desc_violation;
    public $time_violation;
    public $date_violation;
    public $reasons_nonperf;
    public $confirm_doc;
    public $year_report;
    public $result_check_id;


    public function render()
    {
        return view('livewire.rtn.data-result-check', ['rows'=>\App\Models\Rtn\Data_result_check::orderby('id')->get()]);
    }

    private function resetInputFields(){
        $this->num_order = '';
        $this->date_order = '';
        $this->name_order = '';
        $this->desc_order = '';
        $this->name_f = '';
        $this->name_l = '';
        $this->name_p = '';
        $this->desc_violation = '';
        $this->time_violation = '';
        $this->date_violation = '';
        $this->reasons_nonperf = '';
        $this->confirm_doc = '';
        $this->year_report = '';

    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'num_order'=> 'required',
            'date_order'=> 'required',
            'name_order'=> 'required',
            'desc_order'=> 'required',
            'name_f'=> 'required',
            'name_l'=> 'required',
            'name_p'=> 'required',
            'desc_violation'=> 'required',
            'time_violation'=> 'required',
            'date_violation'=> 'required',
            'reasons_nonperf'=> 'required',
            'confirm_doc'=> 'required',
            'year_report'=> 'required',

        ]);
        Data_result_check::create($validatedDate);
        $this->resetInputFields();
        return redirect()->to('/docs/events');

    }

    public function delete($id)
    {
        if($id){
            Data_result_check::where('id',$id)->delete();
            session()->flash('message', 'Data Deleted Successfully.');
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $result_check = Data_result_check::where('id',$id)->first();
        $this->result_check_id = $id;
        $this->num_order = $result_check->num_order;
        $this->date_order = $result_check->date_order;
        $this->name_order = $result_check->name_order;
        $this->desc_order = $result_check->desc_order;
        $this->name_f = $result_check->name_f;
        $this->name_l = $result_check->name_l;
        $this->name_p = $result_check->name_p;
        $this->desc_violation = $result_check->desc_violation;
        $this->time_violation = $result_check->time_violation;
        $this->date_violation = $result_check->date_violation;
        $this->reasons_nonperf = $result_check->reasons_nonperf;
        $this->confirm_doc = $result_check->сonfirm_doc;
        $this->year_report = $result_check->year_report;

    }
    public function update()
    {
        $validatedDate = $this->validate([
            'num_order'=> 'required',
            'date_order'=> 'required',
            'name_order'=> 'required',
            'desc_order'=> 'required',
            'name_f'=> 'required',
            'name_l'=> 'required',
            'name_p'=> 'required',
            'desc_violation'=> 'required',
            'time_violation'=> 'required',
            'date_violation'=> 'required',
            'reasons_nonperf'=> 'required',
            'confirm_doc'=> 'required',
            'year_report'=> 'required',


        ]);

        if ($this-> result_check_id) {
            $result_check = Data_result_check::find($this->result_check_id);
            $result_check ->update([
                'num_order'=> $this->num_order,
                'date_order'=> $this->date_order,
                'name_order'=> $this->name_order,
                'desc_order'=> $this->desc_order,
                'name_f'=> $this->name_f,
                'name_l'=> $this->name_l,
                'name_p'=> $this->name_p,
                'desc_violation'=> $this->desc_violation,
                'time_violation'=> $this->time_violation,
                'date_violation'=> $this->date_violation,
                'reasons_nonperf'=> $this->reasons_nonperf,
                'confirm_doc'=> $this->confirm_doc,
                'year_report'=> $this->year_report,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Data Updated Successfully.');
            $this->resetInputFields();
            return redirect()->to('/docs/events');
        }
    }
}
