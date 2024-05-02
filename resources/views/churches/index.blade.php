@extends('blueprints.churches.default')

@section('title', 'Churches')

@section('dashboard-title', 'List of Churches')

@section('content')
    <div class="text-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Overview</h3>
        <p class="mt-1 text-sm text-gray-600">
            This is a list of churches who have a group.
        </p>

        <!-- Additional dashboard widgets or info can go here -->
        <div class="mt-4">
{{--            @include('blueprints.navigations.admin-nav')--}}
        </div>
        @livewire('churches')

@endsection


