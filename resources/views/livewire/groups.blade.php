<div>
    @if($tableSummaryDisplay)
        <table class="min-w-full leading-normal">
            <tbody>
            @foreach ($churches as $church)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left">
                        {{ $church->name }}
                    </td>
                </tr>
                @foreach ($church->groups as $group)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm pl-10" colspan="2">
                            {{ $group->name }}
                        </td>
                        @if(auth()->user()->hasRole('admin'))
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <button wire:click="manageGroup({{ $group->id }})"
                                        class="text-indigo-600 hover:text-indigo-900">Manage
                                </button>
                            </td>
                        @endif
                        @if(auth()->user()->hasRole('group-leader') && auth()->user()->isGroupLeader($group->id))
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <button wire:click="manageGroup({{ $group->id }})"
                                        class="text-indigo-600 hover:text-indigo-900">Manage
                                </button>
                            </td>
                        @endif
                        @php
                            $userJoinedGroup = $this->userJoinedGroup($group->id);
                        @endphp
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            @if ($userJoinedGroup)
                                <button wire:click="leaveGroup({{ $group->id }})" class="bg-red-500 text-white px-4 py-2 rounded-md">Leave</button>
                            @else
                                <button wire:click="joinGroup({{ $group->id }})" class="bg-green-500 text-white px-4 py-2 rounded-md">Join</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
            @if(auth()->user()->hasRole('admin'))
                <!-- Button to trigger create form -->
                <button wire:click="createGroup"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Group
                </button>
            @endif
            </tbody>
        </table>
    @endif
    @if($createDisplay)
            <div class="mt-12"> <!-- Adjust the top margin as needed -->
                <div class="flex justify-center px-4 sm:px-0">
                    <div class="w-full max-w-lg p-6 bg-white shadow-md rounded-md">
                        <form wire:submit.prevent="createNewGroup">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Group Name</label>
                                <input wire:model="selectedGroup.name" id="name" type="text"
                                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">

                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Associated Church</label>
{{--                                    @php--}}
{{--                                        dd($churches);--}}
{{--                                    @endphp--}}

                                    <select wire:model="selectedChurchId" class="block w-full px-4 py-2 border rounded-md bg-white">
                                        @foreach($churches as $church)
                                            <option value="{{ $church->id }}" {{ $church->id === 1 ? 'selected' : '' }}>
                                                {{ $church->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select wire:model.live = selectedColor>
                                        @foreach($darkColors as $dc)
                                            <option value="{{ $dc['hex'] }}">
                                                {{ $dc['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <br/>
                                    <div style="background-color: {{ $selectedColor }}; width: 30%; height: 20px; margin: 0 auto;"> <p>&nbsp;</p> </div>

                                </div>
                                <br/>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 rounded-md text-white">
                                        Save Group
                                    </button>
                            </div>
                            @error('selectedChurchId') <span class="text-red-500">{{ $message }}</span> @enderror
                            @error('selectedGroup.name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </form>
                    </div>
                </div>
            </div>
    @endif
    @if($deleteDisplay)
        <div class="absolute inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-md shadow-md">
                <p class="text-xl text-gray-800 mb-4">Are you sure you want to delete this group?</p>
                <div class="flex justify-between">
                    <button wire:click="confirmDelete" type="button"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md mr-4">
                        Confirm Delete
                    </button>
                    <button wire:click="cancelDelete" type="button"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif
    @if($managedGroupDisplay)
        Managing the group
        <div class="mt-12"> <!-- Adjust the top margin as needed -->
            <div class="flex justify-center px-4 sm:px-0">
                <div class="w-full max-w-lg p-6 bg-white shadow-md rounded-md">
                    <form wire:submit.prevent="saveGroup">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Group Name:</label>
                            <input wire:model="selectedGroup.name" id="name" type="text"
                                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Church Name:</label>
                                <select wire:model="selectedChurchId"
                                        class="block w-full px-4 py-2 border rounded-md bg-white">
                                    <option value="">Select a church</option>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}"
                                                @if($selectedChurchId == $church->id) selected
                                            @endif>
                                            {{ $church->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model.live = selectedColor>
                                    @foreach($darkColors as $dc)
                                        <option value="{{ $dc['hex'] }}">
                                            @if($selectedColor === $dc['hex'])
                                                selected
                                            @endif
                                            {{ $dc['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <br/>
                                <div style="background-color: {{ $selectedColor }}; width: 30%; height: 20px; margin: 0 auto;"> <p>&nbsp;</p> </div>

                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 rounded-md text-white">
                                Edit Group
                            </button>

                            <button wire:click="deleteGroup" type="button"
                                    class="px-4 py-2 bg-red-500 rounded-md text-white">
                                Delete Group
                            </button>
                        </div>
                        @error('selectedChurchId') <span class="text-red-500">{{ $message }}</span> @enderror
                        @error('selectedGroup.name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
