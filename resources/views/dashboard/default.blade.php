@extends('blueprints.dashboard.default')  <!-- Assuming the master layout is stored under resources/views/layouts/master.blade.php -->

@section('title', 'Default Dashboard')

@section('dashboard-title', 'Welcome to the Dashboard')

@section('content')
    <div class="text-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Overview</h3>
        <p class="mt-1 text-sm text-gray-600">
            This is your default dashboard view, where you can display various widgets or info about your application.
        </p>

        <!-- Additional dashboard widgets or info can go here -->
        <div class="mt-4">
            <p>More dashboard-specific content can be added here.</p>
        </div>
    </div>
@endsection

