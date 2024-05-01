@extends('blueprints.dashboard.default')

@section('title', 'Users Dashboard')

@section('dashboard-title', 'Welcome to the User Dashboard')

@section('content')
    <div class="text-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Overview</h3>
        <p class="mt-1 text-sm text-gray-600">
            This is the users' dashboard.
        </p>

        <!-- Additional dashboard widgets or info can go here -->
        <div class="mt-4">
            <div class="flex justify-center items-center p-6 space-x-4">

                <a href="{{ route('home-page') }}" class="flex items-center space-x-2">
                    <img src="/images/home.webp" alt="Home" style="height: 45px; width: 45px;">
                    <span>{{ __('Home') }}</span>
                </a>
                <a href="#" class="flex items-center space-x-2">
                    <img src="/images/church.webp" alt="Church" style="height: 45px; width: 45px;">
                    <span>{{ __('Churches') }}</span>
                </a>
                <a href="#" class="flex items-center space-x-2">
                    <img src="/images/group.webp" alt="Groups" style="height: 45px; width: 45px;">
                    <span>{{ __('Groups') }}</span>
                </a>
                <a href="#" class="flex items-center space-x-2">
                    <img src="/images/calendar.webp" alt="Calendar" style="height: 45px; width: 45px;">
                    <span>{{ __('Calendar') }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="all: unset;">
                    @csrf
                    <button type="submit"
                            style="background: none; color: inherit; border: none; padding: 0; font: inherit; cursor: pointer; outline: inherit;">
                        <div class="flex items-center space-x-2">
                            <img src="/images/logout.webp" alt="Logout" style="height: 45px; width: 45px;">
                            <span>{{ __('Logout') }}</span>
                        </div>
                    </button>
                </form>
                <hr>

            </div>
        </div>
@endsection
