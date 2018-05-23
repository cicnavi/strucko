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

    <meta name="description" content="Open IT Dictionary">
    <meta name="author" content="Marko Ivnačić">

    <title>{{ config('app.name', 'Strucko - IT Dictionary') }}</title>

    <link rel="icon" type="image/png" href="https://strucko.com/img/favicon.png">

    <meta property="og:title"              content="{{ config('app.name', 'Strucko - IT Dictionary') }}" />
    <meta property="og:description"        content="Open IT Dictionary" />
    <meta property="og:image"              content="https://strucko.com/img/logo.png" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @include('layouts.cookie-consent')

    @if(env('APP_ENV') == 'production')
        @include('layouts.adsense')
        @include('layouts.analytics')
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
