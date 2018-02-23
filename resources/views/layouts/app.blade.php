<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Base URL -->
    <meta name="base-url" content="{{ env('APP_URL') }}">

    <title>{{ config('app.name', 'Strucko - IT Dictionary') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @if(env('APP_ENV') == 'production')
        @include('layouts.adsense')
    @endif
</head>
<body>
    <div id="app">

        @include('layouts.navbar')

        <div class="container">
            <div class="col-xs-12">

                @yield('content')

            </div>
        </div>

        @include('layouts.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
