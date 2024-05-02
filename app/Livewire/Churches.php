<?php

namespace App\Livewire;

use App\Models\Church;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Churches extends Component
{
    public $churches;
    public bool $tableSummary = true;
    public bool $showForm = false;  // Controls whether the form is shown
    public bool $editMode = false;  // When true, the component is in "edit" mode
    public bool $deleteMode = false;  // When true, the component is in "delete" mode
    public bool $createForm = false;  // When true, the component is in "create" mode
    public $currentChurch = null;  // Stores the current church being edited or viewed

    public array $churchInfo = [];
    public function mount()
    {
        // Check if the user is not authenticated
        if (!Auth::check()) {
            // Redirect to the home page
            return redirect()->route('home-page');
        }
        $this->isAdmin = auth()->user()->hasRole('admin'); // Check if user is admin
        $this->churches = Church::all(); // Fetch all churches
    }

    public function render()
    {
        return view('livewire.churches');
    }

    public function editChurch(Church $church)
    {
        $this->showForm = true;
        $this->editMode = true;
        $this->currentChurch = $church;
    }

    public function createChurch()
    {
        $this->tableSummary = false;
        $this->createForm = true;
        $this->currentChurch = new Church();  // Creating a new instance for binding
    }

    public function saveChurch()
    {
//        $this->currentChurch->name = 'Love-Joy church';
        $churchInfo = [];
        $this->validate($this->getValidationRules());
//        dd($this->churchInfo['description']);
        $this->currentChurch->name = $this->churchInfo['name'];
        $this->currentChurch->description = $this->churchInfo['description'];
        $this->currentChurch->save();
        $this->resetForm();
        $this->churches = Church::all(); // Fetch all churches
    }

    public function deleteChurch(Church $church)
    {
        $church->delete();
        $this->resetForm();
        $this->churches = Church::all(); // Refresh the churches list
    }
    protected function getValidationRules()
    {
        if ($this->createForm) {
            return [
                'churchInfo.name' => 'required|string|max:255|unique:churches,name',
                'churchInfo.description' => 'required|string'
            ];
        } elseif ($this->editMode) {
            return [
                'currentChurch.name' => 'required|string|max:255|unique:churches,name,' . $this->currentChurch->id,
                'currentChurch.description' => 'string'
            ];
        }
    }
    public function resetForm()
    {
        $this->tableSummary = true;
        $this->showForm = false;
        $this->editMode = false;
        $this->deleteMode = false;
        $this->createForm = false;
        $this->currentChurch = null;
    }
}
