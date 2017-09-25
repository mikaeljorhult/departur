<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Departur') }}</title>

    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>

<body>
    <header>
        <a class="logo" href="{{ url('/') }}">
            {{ config('app.name', 'Departur') }}
        </a>

        <nav class="main-navigation">
            <ul>
                <li><a href="{{ route('schedules.index') }}">Schedules</a></li>
                <li><a href="{{ route('calendars.index') }}">Calendars</a></li>
                <li><a href="{{ route('users.index') }}">Users</a></li>
                <li>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
