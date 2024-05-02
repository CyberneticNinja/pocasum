<div>
    @include('blueprints.navigations.navigation')

    @if($tableSummary)
        <!-- Churches table -->
        <table class="min-w-full leading-normal">
            <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Name
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Description
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($churches as $church)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{ $church->name }}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{ $church->description }}
                    </td>
                    @if(auth()->user()->hasRole('admin'))
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <button wire:click="editChurch({{ $church->id }})"
                                    class="text-indigo-600 hover:text-indigo-900">Edit
                            </button>
                            <button wire:click="deleteChurch({{ $church->id }})"
                                    class="text-red-600 hover:text-red-900">Delete
                            </button>
                        </td>
                    @endif
                </tr>
            @endforeach
            @if(auth()->user()->hasRole('admin'))
                <!-- Button to trigger create form -->
                <button wire:click="createChurch"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Church
                </button>
            @endif
            </tbody>
        </table>
    @endif

    @if($createForm)
        <div class="mt-12"> <!-- Adjust the top margin as needed -->
            <div class="flex justify-center px-4 sm:px-0">
                <div class="w-full max-w-lg p-6 bg-white shadow-md rounded-md">

                    <form wire:submit.prevent="saveChurch">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Church Name:</label>
                            <input wire:model="churchInfo.name" id="name" type="text" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="mt-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                            <textarea wire:model="churchInfo.description" id="description" rows="4" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 rounded-md text-white">Save Church</button>
                        </div>
                        @error('churchInfo.name') <span class="text-red-500">{{ $message }}</span> @enderror
                        @error('churchInfo.description') <span class="text-red-500">{{ $message }}</span> @enderror
                    </form>

                </div>
            </div>
        </div>
    @endif
</div>
