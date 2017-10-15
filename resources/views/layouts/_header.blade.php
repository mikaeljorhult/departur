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