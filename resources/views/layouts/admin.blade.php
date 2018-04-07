<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Departur'))</title>

    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>

<body>
    @include('layouts._header')

    <section class="main-content">
        <nav class="main-navigation">
            <a class="navigation-heading" href="{{ route('admin') }}">
                Administration
            </a>

            <ul>
                <li>
                    @svg('browser-window')
                    <a href="{{ route('schedules.index') }}">Schedules</a>
                </li>
                <li>
                    @svg('calendar')
                    <a href="{{ route('calendars.index') }}">Calendars</a>
                </li>
                <li>
                    @svg('user')
                    <a href="{{ route('users.index') }}">Users</a>
                </li>
                <li>
                    @svg('stand-by')
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

        <main>
            @yield('content')
        </main>
    </section>

    @include('layouts._footer')

    <script src="{{ asset('js/admin.js') }}"></script>
</body>

</html>
