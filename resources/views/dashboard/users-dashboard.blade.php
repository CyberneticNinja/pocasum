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
            @include('blueprints.navigations.navigation')
        </div>
@endsection
