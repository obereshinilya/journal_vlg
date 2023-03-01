<?php

namespace App\Http\Livewire\Rtn;

use App\Models\Rtn\Report_tu;
use Livewire\Component;
use Livewire\WithPagination;

class ReportTU extends Component
{
    use WithPagination;
    public $search = '';
    public $users;
    public $reg_n_opo;
    public $reg_n_tu;
    public $name_tu;
    public $serial_n_tu;
    public $gos_reg_n;
    public $factory_n_tu;
    public $type_tu;
    public $vid_tu;
    public $marks_tu;
    public $service_life;
    public $commissioning;
    public $wear_out;
    public $date_exam;
    public $date_next_exam;
    public $date_to;
    public $date_next_to;
    public $perm_life;
    public $safety_device;
    public $type_safety_dev;
    public $volume;
    public $pressure;
    public $diam;
    public $gs_type;
    public $gs_sub_type;
    public $or_volume;
    public $gs_mass;
    public $or_pressure;
    public $year_modern;
    public $activities_carried;
    public $sert_type;
    public $sert_number;
    public $sert_date;
    public $sert_issued;
    public $report_id;

    public function render()
    {
        return view('livewire.rtn.report-t-u', ['rows'=>Report_tu::orderby('id')->get()]);
    }
    private function resetInputFields(){
        $this->reg_n_opo = '';
        $this->reg_n_tu = '';
        $this->name_tu = '';
        $this->serial_n_tu = '';
        $this->gos_reg_n = '';
        $this->factory_n_tu = '';
        $this->type_tu = '';
        $this->vid_tu = '';
        $this->marks_tu = '';
        $this->service_life = '';
        $this->commissioning = '';
        $this->wear_out = '';
        $this->date_exam = '';
        $this->date_next_exam = '';
        $this->date_to = '';
        $this->date_next_to = '';
        $this->perm_life = '';
        $this->safety_device = '';
        $this->type_safety_dev = '';
        $this->volume = '';
        $this->pressure = '';
        $this->diam = '';
        $this->gs_type = '';
        $this->gs_sub_type = '';
        $this->or_volume = '';
        $this->gs_mass = '';
        $this->or_pressure = '';
        $this->year_modern = '';
        $this->activities_carried = '';
        $this->sert_type = '';
        $this->sert_number = '';
        $this->sert_date = '';
        $this->sert_issued = '';

    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'reg_n_opo' => 'required',
            'reg_n_tu' => 'required',
            'name_tu' => 'required',
            'serial_n_tu' => 'required',
            'gos_reg_n' => 'required',
            'factory_n_tu' => 'required',
            'type_tu' => 'required',
            'vid_tu' => 'required',
            'marks_tu' => 'required',
            'service_life' => 'required',
            'commissioning' => 'required',
            'wear_out' => 'required',
            'date_exam' => 'required',
            'date_next_exam' => 'required',
            'date_to' => 'required',
            'date_next_to' => 'required',
            'perm_life' => 'required',
            'safety_device' => 'required',
            'type_safety_dev' => 'required',
            'volume' => 'required',
            'pressure' => 'required',
            'diam' => 'required',
            'gs_type' => 'required',
            'gs_sub_type' => 'required',
            'or_volume' => 'required',
            'gs_mass' => 'required',
            'or_pressure' => 'required',
            'year_modern' => 'required',
            'activities_carried' => 'required',
            'sert_type' => 'required',
            'sert_number' => 'required',
            'sert_date' => 'required',
            'sert_issued' => 'required',
        ]);
        Report_tu::create($validatedDate);
        $this->resetInputFields();
        return redirect()->to('/docs/events');

    }
    public function delete($id)
    {
        if($id){
            Report_tu::where('id',$id)->delete();
            session()->flash('message', ' Data Deleted Successfully.');
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $report = Report_tu::where('id',$id)->first();
        $this->report_id = $id;
        $this-> reg_n_opo = $report-> reg_n_opo;
        $this-> reg_n_tu = $report-> reg_n_tu;
        $this-> name_tu = $report-> name_tu;
        $this-> serial_n_tu = $report-> serial_n_tu;
        $this-> gos_reg_n = $report-> gos_reg_n;
        $this-> factory_n_tu = $report-> factory_n_tu;
        $this-> type_tu = $report-> type_tu;
        $this-> vid_tu = $report-> vid_tu;
        $this-> marks_tu = $report-> marks_tu;
        $this-> service_life = $report-> service_life;
        $this-> commissioning = $report-> commissioning;
        $this-> wear_out = $report-> wear_out;
        $this-> date_exam = $report-> date_exam;
        $this-> date_next_exam = $report-> date_next_exam;
        $this-> date_to = $report-> date_to;
        $this-> date_next_to = $report-> date_next_to;
        $this-> perm_life = $report-> perm_life;
        $this-> safety_device = $report-> safety_device;
        $this-> type_safety_dev = $report-> type_safety_dev;
        $this-> volume = $report-> volume;
        $this-> pressure = $report-> pressure;
        $this-> diam = $report-> diam;
        $this-> gs_type = $report-> gs_type;
        $this-> gs_sub_type = $report-> gs_sub_type;
        $this-> or_volume = $report-> or_volume;
        $this-> gs_mass = $report-> gs_mass;
        $this-> or_pressure = $report-> or_pressure;
        $this-> year_modern = $report-> year_modern;
        $this-> activities_carried = $report-> activities_carried;
        $this-> sert_type = $report-> sert_type;
        $this-> sert_number = $report-> sert_number;
        $this-> sert_date = $report-> sert_date;
        $this-> sert_issued = $report-> sert_issued;
    }
    public function update()
    {
        $validatedDate = $this->validate([
            'reg_n_opo' => 'required',
            'reg_n_tu' => 'required',
            'name_tu' => 'required',
            'serial_n_tu' => 'required',
            'gos_reg_n' => 'required',
            'factory_n_tu' => 'required',
            'type_tu' => 'required',
            'vid_tu' => 'required',
            'marks_tu' => 'required',
            'service_life' => 'required',
            'commissioning' => 'required',
            'wear_out' => 'required',
            'date_exam' => 'required',
            'date_next_exam' => 'required',
            'date_to' => 'required',
            'date_next_to' => 'required',
            'perm_life' => 'required',
            'safety_device' => 'required',
            'type_safety_dev' => 'required',
            'volume' => 'required',
            'pressure' => 'required',
            'diam' => 'required',
            'gs_type' => 'required',
            'gs_sub_type' => 'required',
            'or_volume' => 'required',
            'gs_mass' => 'required',
            'or_pressure' => 'required',
            'year_modern' => 'required',
            'activities_carried' => 'required',
            'sert_type' => 'required',
            'sert_number' => 'required',
            'sert_date' => 'required',
            'sert_issued' => 'required',


        ]);

        if ($this-> report_id) {
            $info = Report_tu::find($this-> report_id);
            $info ->update([
                'reg_n_opo' => $this-> reg_n_opo,
                'reg_n_tu' => $this-> reg_n_tu,
                'name_tu' => $this-> name_tu,
                'serial_n_tu' => $this-> serial_n_tu,
                'gos_reg_n' => $this-> gos_reg_n,
                'factory_n_tu' => $this-> factory_n_tu,
                'type_tu' => $this-> type_tu,
                'vid_tu' => $this-> vid_tu,
                'marks_tu' => $this-> marks_tu,
                'service_life' => $this-> service_life,
                'commissioning' => $this-> commissioning,
                'wear_out' => $this-> wear_out,
                'date_exam' => $this-> date_exam,
                'date_next_exam' => $this-> date_next_exam,
                'date_to' => $this-> date_to,
                'date_next_to' => $this-> date_next_to,
                'perm_life' => $this-> perm_life,
                'safety_device' => $this-> safety_device,
                'type_safety_dev' => $this-> type_safety_dev,
                'volume' => $this-> volume,
                'pressure' => $this-> pressure,
                'diam' => $this-> diam,
                'gs_type' => $this-> gs_type,
                'gs_sub_type' => $this-> gs_sub_type,
                'or_volume' => $this-> or_volume,
                'gs_mass' => $this-> gs_mass,
                'or_pressure' => $this-> or_pressure,
                'year_modern' => $this-> year_modern,
                'activities_carried' => $this-> activities_carried,
                'sert_type' => $this-> sert_type,
                'sert_number' => $this-> sert_number,
                'sert_date' => $this-> sert_date,
                'sert_issued' => $this-> sert_issued,

            ]);
            $this->updateMode = false;
            session()->flash('message', 'Data Updated Successfully.');
            $this->resetInputFields();
            return redirect()->to('/docs/events');
        }

    }
}
