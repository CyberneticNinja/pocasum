<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Users List</h1>
    <table class="min-w-full leading-normal">
        <thead>
        <tr>
            <th wire:click="sort('name')" class="cursor-pointer">
                Name
                @if ($sortBy === 'name')
                    <i class="fas fa-{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}"></i>
                @endif
            </th>
            <th wire:click="sort('email')" class="cursor-pointer">
                Email
                @if ($sortBy === 'email')
                    <i class="fas fa-{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}"></i>
                @endif
            </th>
            <th wire:click="sort('roles')" class="cursor-pointer">
                Roles
                @if ($sortBy === 'roles')
                    <i class="fas fa-{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}"></i>
                @endif
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">
                        {{ $user->name }}
                    </p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->email }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    @foreach ($user->roles as $role)
                        <span
                            class="relative inline-block px-3 py-1 font-semibold text-gray-900 leading-tight {{ $role->name == 'admin' ? 'bg-red-200' : ($role->name == 'user' ? 'bg-blue-200' : 'bg-green-200') }}">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <button wire:click="editRoles({{ $user->id }})"
                            class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded hover:bg-blue-700">
                        Manage Roles
                    </button>
                    @if($user->hasRole('group-leader'))
                        <!-- Check if the user is a group leader -->
                        <button wire:click="editGroups({{ $user->id }})"
                                class="ml-2 px-4 py-2 bg-green-500 text-white text-sm font-medium rounded hover:bg-green-700">
                            Manage Groups
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="py-3">
        {{ $users->links() }}
    </div>
    <!-- Role Management Modal -->
    @if($showRoleModal)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Manage Roles for {{ $currentUser->name }}
                                </h3>
                                <div class="mt-2">
                                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                        <div>
                                            <label>
                                                <input type="checkbox" wire:model.defer="selectedRoles"
                                                       value="{{ $role->id }}"
                                                       @if($currentUser->roles->contains($role)) checked @endif>
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="updateRoles" type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Changes
                        </button>
                        <button wire:click="$set('showRoleModal', false)" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($showGroupModal)
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

                <!-- Modal panel -->
                <div
                    class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Manage Group Leadership</h3>
                        <div class="mt-2">
                            @foreach($allGroups as $group)
                                <label class="block">
                                    <input type="checkbox" wire:model="selectedGroups" value="{{ $group->id }}"
                                           @if(in_array($group->id, $currentUserGroups)) checked @endif>
                                    {{ $group->name }}
                                    <span class="text-xs text-gray-600">({{ $group->church->name }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveGroupLeadership" type="button"
                                class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button wire:click="$set('showGroupModal', false)" type="button"
                                class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
