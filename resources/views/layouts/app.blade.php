<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '15Onzas') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/solid.js" integrity="sha384-P4tSluxIpPk9wNy8WSD8wJDvA8YZIkC6AQ+BfAFLXcUZIPQGu4Ifv4Kqq+i2XzrM" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/fontawesome.js" integrity="sha384-2IUdwouOFWauLdwTuAyHeMMRFfeyy4vqYNjodih+28v2ReC+8j+sLF9cK339k5hY" crossorigin="anonymous"></script>
    <script src="{{ asset('js/mdb.min.js') }}" defer></script>
    <script src="{{ asset('js/pikaday.js') }}" defer></script>
    <script src="{{ asset('js/moment.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mdb.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pikaday.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.dataTables.min.css') }}" rel="stylesheet">
</head>
<body class="fixed-sn white-skin">
    <div id="app">
        <nav class="navbar navbar-expand-md scrolling-navbar double-nav navbar-light navbar-15O">
            <div class="container">
                <a class="navbar-brand breadcrumb-dn mr-auto" href="{{ url('/') }}">
                    <img src="{{ asset('/images/logo.png') }}" height="35px">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">Ingresar</a></li>
                        @else
                            @if(\Illuminate\Support\Facades\Auth::user()->rol != 4)
                                <notificaciones :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></notificaciones>
                            @endif

                            @if(\Illuminate\Support\Facades\Auth::user()->rol != 4)
                                <li class="nav-item dropdown">
                                    <a class="nav-link waves-effect" href="{{ route('dinamicas.index') }}" role="button">
                                        Dinamicas
                                    </a>
                                </li>
                            @endif

                            @if(\Illuminate\Support\Facades\Auth::user()->rol == 4 || \Illuminate\Support\Facades\Auth::user()->rol == 0)
                                <li class="nav-item dropdown">
                                    <a class="nav-link waves-effect" href="{{ route('admin') }}" role="button">
                                        Tickets
                                    </a>
                                </li>
                            @endif

                                <li class="nav-item dropdown">
                                    <a class="nav-link waves-effect" href="{{ route('kpi') }}" role="button">
                                        Reportes
                                    </a>
                                </li>

                            @if(\Illuminate\Support\Facades\Auth::user()->rol == 1 || \Illuminate\Support\Facades\Auth::user()->rol == 0)
                                <li class="nav-item dropdown">
                                    <a class="nav-link waves-effect" href="{{ route('users.index') }}" role="button">
                                        Usuarios
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle waves-effect" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Cerrar sesión
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script defer src="{{ asset('/js/dataTables.responsive.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
