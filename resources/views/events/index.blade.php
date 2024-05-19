@extends('blueprints.events.default')

@section('title', 'Events')

@section('dashboard-title', 'Events')

@section('content')
    <div class="text-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Overview</h3>
        <p class="mt-1 text-sm text-gray-600">
            Calendar Events
        </p>

        <!-- Additional dashboard widgets or info can go here -->
        <div class="mt-4">
            @include('blueprints.navigations.navigation')
        </div>
        @livewire('events')
@endsection
