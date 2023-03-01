<?php

namespace App\Http\Livewire\Rtn;

use App\Models\Rtn\Data_check_out;
use Livewire\Component;

class DataCheckOut extends Component
{


    public function render()
    {
        return view('livewire.rtn.data-check-out', ['rows'=>\App\Models\Rtn\Data_check_out::orderby('id')->get()]);
    }

    private function resetInputFields(){
        $this->depart_do = '';
        $this->reg_num_opo = '';
        $this->date_check_out = '';
        $this->name_f = '';
        $this->name_l = '';
        $this->name_p = '';
        $this->stop_work = '';
        $this->char_violation = '';
        $this->norm_act = '';
        $this->point_act = '';
        $this->name_event = '';
        $this->time_violation = '';
        $this->date_violation = '';
        $this->reasons_nonperf = '';
        $this->data_reasons = '';
        $this->reasons_post = '';
        $this->worker_violation = '';
        $this->offers_spb = '';
        $this->year_report = '';

    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'depart_do' => 'required',
            'reg_num_opo' => 'required',
            'date_check_out' => 'required',
            'name_f' => 'required',
            'name_l' => 'required',
            'name_p' => 'required',
            'stop_work' => 'required',
            'char_violation' => 'required',
            'norm_act' => 'required',
            'point_act' => 'required',
            'name_event' => 'required',
            'time_violation' => 'required',
            'date_violation' => 'required',
            'reasons_nonperf' => 'required',
            'data_reasons' => 'required',
            'reasons_post' => 'required',
            'worker_violation'=> 'required',
            'offers_spb' => 'required',
            'year_report' => 'required',

        ]);
        Data_check_out::create($validatedDate);
        $this->resetInputFields();
        return redirect()->to('/docs/events');

    }

    public function delete($id)
    {
        if($id){
            Data_check_out::where('id',$id)->delete();
            session()->flash('message', 'Data Deleted Successfully.');
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $check_out = Data_check_out::where('id',$id)->first();
        $this->check_out_id = $id;
        $this->depart_do = $check_out->depart_do;
        $this->reg_num_opo = $check_out->reg_num_opo;
        $this->date_check_out = $check_out->date_check_out;
        $this->name_f = $check_out->name_f;
        $this->name_l = $check_out->name_l;
        $this->name_p = $check_out->name_p;
        $this->stop_work = $check_out->stop_work;
        $this->char_violation = $check_out->char_violation;
        $this->norm_act = $check_out->norm_act;
        $this->point_act = $check_out->point_act;
        $this->name_event = $check_out->name_event;
        $this->time_violation = $check_out->time_violation;
        $this->date_violation = $check_out->date_violation;
        $this->reasons_nonperf = $check_out->reasons_nonperf;
        $this->data_reasons = $check_out->data_reasons;
        $this->reasons_post = $check_out->reasons_post;
        $this->worker_violation = $check_out->worker_violation;
        $this->offers_spb = $check_out->offers_spb;
        $this->year_report = $check_out->year_report;

    }
    public function update()
    {
        $validatedDate = $this->validate([
            'depart_do' => 'required',
            'reg_num_opo' => 'required',
            'date_check_out' => 'required',
            'name_f' => 'required',
            'name_l' => 'required',
            'name_p' => 'required',
            'stop_work' => 'required',
            'char_violation' => 'required',
            'norm_act' => 'required',
            'point_act' => 'required',
            'name_event' => 'required',
            'time_violation' => 'required',
            'date_violation' => 'required',
            'reasons_nonperf' => 'required',
            'data_reasons' => 'required',
            'reasons_post' => 'required',
            'worker_violation' => 'required',
            'offers_spb' => 'required',
            'year_report' => 'required',


        ]);

        if ($this-> check_out_id) {
            $check_out = Data_check_out::find($this-> check_out_id);
            $check_out ->update([
                'depart_do'=> $this->depart_do,
                'reg_num_opo'=> $this->reg_num_opo,
                'date_check_out'=> $this->date_check_out,
                'name_f'=> $this->name_f,
                'name_l'=> $this->name_l,
                'name_p'=> $this->name_p,
                'stop_work'=> $this->stop_work,
                'char_violation'=> $this->char_violation,
                'norm_act'=> $this->norm_act,
                'point_act'=> $this->point_act,
                'name_event'=> $this->name_event,
                'time_violation'=> $this->time_violation,
                'date_violation'=> $this->date_violation,
                'reasons_nonperf'=> $this->reasons_nonperf,
                'data_reasons'=> $this->data_reasons,
                'reasons_post' => $this->reasons_post,
                'worker_violation'=> $this->worker_violation,
                'offers_spb'=> $this->offers_spb,
                'year_report'=> $this->year_report,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Data Updated Successfully.');
            $this->resetInputFields();
            return redirect()->to('/docs/events');
        }
    }

}
