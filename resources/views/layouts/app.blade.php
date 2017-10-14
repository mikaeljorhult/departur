<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Departur'))</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <header class="main-header">
        <h1>
            <a href="{{ url('/') }}">
                {{ config('app.name', 'Departur') }}
            </a>
        </h1>

        <ul>
            @guest
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @else
                <li>
                    <a href="{{ route('admin') }}">
                        Administration
                    </a>
                </li>

                <li>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            @endguest
        </ul>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="main-footer">
        Powered by <a href="http://departur.se">Departur</a>.
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
