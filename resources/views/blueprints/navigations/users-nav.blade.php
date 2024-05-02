<div class="flex justify-center items-center p-6 space-x-4">

    <a href="{{ route('home-page') }}" class="flex items-center space-x-2">
        <img src="/images/home.webp" alt="Home" style="height: 45px; width: 45px;">
        <span>{{ __('Home') }}</span>
    </a>
    <a href="{{ route('churches') }}" class="flex items-center space-x-2">
        <img src="/images/church.webp" alt="Church" style="height: 45px; width: 45px;">
        <span>{{ __('churches') }}</span>
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
</div>
