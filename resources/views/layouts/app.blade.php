<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    @yield('style')
</head>
<body>
    <div id="app">
    	@if(!isset($db_host) && !isset($db_usuario))
        <nav class="navbar navbar-expand-sm navbar-light navbar-laravel" style="background-color:#1d5464">
			<div class="row" style="min-width: 100%;">
				<div class="col-sm" align="left">
					<a class="navbar-brand" href="{{ url('/') }}">
						<img src="{{ asset('img/untref.png')}}" height="50" class="d-inline-block align-top" style="padding-right:10px">
					</a>
				</div>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<!-- Left Side Of Navbar -->
					<div class="col-sm" align="center">
						<a class="navbar-brand" href="{{ url('/') }}" style="color:#FFFFFF">
							<h3><b>{{ config('app.name', 'Laravel') }}</b></h3>
						</a>
					</div>

					<!-- Right Side Of Navbar -->
					<div class="col-sm" align="right">
						<!-- Authentication Links -->
						@guest
							<div class="nav-item">
								<a class="btn btn-outline-success btn-sm d-inline-block align-top" href="{{ route('login') }}" style="margin:5px 10px 0px 5px">{{ __('Iniciar Sesión') }}</a>
							@if (Route::has('register'))
								<a class="btn btn-outline-info btn-sm d-inline-block align-top" href="{{ route('register') }}" style="margin:5px 0px 0px 10px">{{ __('Registrarse') }}</a>
							@endif
							</div>
						@else
							<div class="nav-item dropdown" style="padding-top:10px">
								<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="color:#FFFFFF">
									{{ Auth::user()->name }} <span class="caret"></span>
								</a>

								<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="{{ route('logout') }}"
									   onclick="event.preventDefault();
													 document.getElementById('logout-form').submit();">
										{{ __('Cerrar Sesión') }}
									</a>

									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										@csrf
									</form>
								</div>
							</div>
						@endguest
					</div>
				</div>
			</div>
        </nav>
        @endif
        <main class="py-1">
            @yield('content')
        </main>
    </div>
    @yield('script')
</body>
</html>
