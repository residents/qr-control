<?php

namespace App\Http\Livewire\Appointment;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;

class Showlist extends Component
{
	use WithPagination;

	protected $queryString = [
		'search' => ['except' => ''], 
		'perPage' => ['except' => '5']
	];

	//Propiedades de la lista
	public $search;
	public $perPage;
	public $isOpen;

	//Propiedades del formulario
	public $id_appointment;
	public $id_doctor;
	public $id_patient;
	public $date;
	public $hour;
	public $doctors;
	public $patients;

	public function mount(){
		$this->fill([
			'search' => '',
			'perPage' => '5',
			'isOpen' => false,
			'doctors' => Doctor::get(),
			'patients' => Patient::get(),
		]);
	}

    public function render()
    {
    	$appointments = Appointment::where('date', 'like', "%$this->search%")->paginate($this->perPage);
        return view('livewire.appointment.showlist', ['appointments' => $appointments]);
    }

    public function clearFilter(){
    	$this->reset(['search','page','perPage']);
    }

    public function openModal(){
		$this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
        $this->reset(['id_appointment', 'id_doctor', 'id_patient', 'date', 'hour']);
    }

    public function store()
    {
        $this->validate([
            'id_doctor' => 'required|numeric',
            'id_patient' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d',
            'hour' => 'required|date_format:H:i',
        ]);
        $data = array(
            'id_doctor' => $this->id_doctor,
            'id_patient' => $this->id_patient,
            'date' => $this->date,
            'hour' => $this->hour
        );
        $appointment = Appointment::updateOrCreate(['id' => $this->id_appointment], $data);
        session()->flash('message', $this->id_appointment ? 'Cita actualizada correctamente.' : 'Cita creada correctamente.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $this->appointments = $appointment->appointments;
        $this->id_appointment = $id;
        $this->id_doctor = $appointment->id_doctor;
        $this->id_patient = $appointment->id_patient;
        $this->date = $appointment->date;
        $this->hour = $appointment->hour;
        $this->openModal();
    }

    public function delete($id)
    {
        $this->id_appointment = $id;

        $appointment = Appointment::find($id);
        $appointment->delete();
        session()->flash('message', 'Cita eliminada correctamente.');
    }
}
