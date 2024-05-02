<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>

                <!-- Icon Links Row, centered and justified -->
                <div class="flex justify-center items-center p-6 space-x-4">
                    <a href="#" class="flex items-center space-x-2">
                        <img src="/images/home.webp" alt="Church" style="height: 91px; width: 91px;">
                        <span>{{ __('Home') }}</span>
                    </a>
                    <a href="#" class="flex items-center space-x-2">
                        <img src="/images/church.webp" alt="Church" style="height: 91px; width: 91px;">
                        <span>{{ __('churches') }}</span>
                    </a>
                    <a href="#" class="flex items-center space-x-2">
                        <img src="/images/users.webp" alt="Users" style="height: 91px; width: 91px;">
                        <span>{{ __('Users') }}</span>
                    </a>
                    <a href="#" class="flex items-center space-x-2">
                        <img src="/images/group.webp" alt="Groups" style="height: 91px; width: 91px;">
                        <span>{{ __('Groups') }}</span>
                    </a>
                    <a href="#" class="flex items-center space-x-2">
                        <img src="/images/calendar.webp" alt="Calendar" style="height: 91px; width: 91px;">
                        <span>{{ __('Calendar') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
