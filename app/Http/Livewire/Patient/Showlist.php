<?php

namespace App\Http\Livewire\Patient;

use Livewire\Component;
use Livewire\WithPagination;
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
	public $id_patient;
	public $name;
	public $birthday;
	public $gender;
	public $appointments;

	public function mount(){
		$this->fill([
			'search' => '',
			'perPage' => '5',
			'isOpen' => false,
		]);
	}

    public function render()
    {
    	$patients = Patient::where('name', 'like', "%$this->search%")->paginate($this->perPage);
        return view('livewire.patient.showlist', ['patients' => $patients]);
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
        $this->reset(['id_patient', 'name','birthday','gender', 'appointments']);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|max:255',
            'birthday' => 'required|date',
            'gender' => 'required|max:15',
        ]);
        $data = array(
            'name' => $this->name,
            'birthday' => $this->birthday,
            'gender' => $this->gender
        );
        $patient = Patient::updateOrCreate(['id' => $this->id_patient], $data);
        session()->flash('message', $this->id_patient ? 'Paciente actualizado correctamente.' : 'Paciente creado correctamente.');
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
        $patient = Patient::findOrFail($id);
        $this->appointments = $patient->appointments;
        $this->id_patient = $id;
        $this->name = $patient->name;
        $this->birthday = $patient->birthday;
        $this->gender = $patient->gender;
        $this->openModal();
    }

    public function delete($id)
    {
        $this->id_patient = $id;

        $patient = Patient::find($id);
        $patient->appointments()->delete();
        $patient->delete();
        session()->flash('message', 'Paciente eliminado correctamente.');
    }


}
