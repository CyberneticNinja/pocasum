<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    use WithPagination;
    public $sortBy = 'name';    // Default sort column
    public $showRoleModal = false;
    public $currentUser;
    public $sortDirection = 'asc'; // Default sort direction
    public $selectedRoles = [];
    public $showGroupModal = false;
    public $allGroups;
    public $selectedGroups = [];
    public $currentUserGroups = [];

    public function mount()
    {
        $this->allGroups = Group::all();
    }

    public function saveGroupLeadership()
    {
        $this->currentUser->groups()->sync($this->selectedGroups);
        $this->showGroupModal = false;
    }
    public function editRoles($userId)
    {
        $this->currentUser = User::with('roles')->find($userId);
        $this->showRoleModal = true;
    }
    public function editGroups($userId)
    {
        $this->currentUser = User::with('groups.church')->find($userId);  // Ensure church data is loaded with groups
        $this->selectedGroups = $this->currentUser->groups->pluck('id')->toArray();
        $this->showGroupModal = true;
    }
    public function sort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
            $this->sortBy = $field;
        }
    }

    public function updateRoles()
    {
        if ($this->selectedRoles) {
            $roleNames = Role::whereIn('id', $this->selectedRoles)->get()->pluck('name');
            $this->currentUser->syncRoles($roleNames);

            // Using contains to check if the collection has 'group-leader'
            if ($roleNames->contains('group-leader')) {
                $this->editRoles($this->currentUser->id);  // Re-check roles and possibly show group modal
            } else {
                $this->showRoleModal = false;
            }
        }
    }

    public function render()
    {
        $users = User::with('roles')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->where('model_has_roles.model_type', User::class)
            ->select('users.*', DB::raw('COUNT(model_has_roles.role_id) as roles_count'))
            ->groupBy('users.id')
            ->orderBy($this->sortBy === 'roles' ? 'roles_count' : $this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.users', compact('users'));
    }
}
