<div class="flex justify-center items-center p-6 space-x-4">

    <a href="{{ route('home-page') }}" class="flex items-center space-x-2">
        <img src="/images/home.webp" alt="Home" style="height: 45px; width: 45px;">
        <span>{{ __('Home') }}</span>
    </a>
    <a href="{{ route('churches') }}" class="flex items-center space-x-2">
        <img src="/images/church.webp" alt="Church" style="height: 45px; width: 45px;">
        <span>{{ __('churches') }}</span>
    </a>
    @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('users') }}" class="flex items-center space-x-2">
            <img src="/images/users.webp" alt="Users" style="height: 45px; width: 45px;">
            <span>{{ __('Users') }}</span>
        </a>
    @endif
    <a href="{{ route('groups') }}" class="flex items-center space-x-2">
        <img src="/images/group.webp" alt="Groups" style="height: 45px; width: 45px;">
        <span>{{ __('Groups') }}</span>
    </a>
{{--    @if((auth()->user()->hasRole('admin')) || auth()->user()->hasRole('group-leader'))--}}

{{--    @endif--}}
    <a href="{{ route('calendar') }}" class="flex items-center space-x-2">
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
</div>
