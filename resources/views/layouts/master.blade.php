<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@yield('meta-description')">
                
        <meta name="author" content="">

        <title>@yield('title') - Strucko </title>

        <link rel="stylesheet" href="/css/app.css">

        @include('layouts.cookie_consent')
    </head>
    <body>
        
        @if(getenv('APP_ENV')=='production')
            @include('layouts.analytics')
        @endif
        
        {{-- Currently disabled
        @if(getenv('APP_ENV')=='production')
            @include('layouts.antiblock')
        @endif
        --}}
        <div class="container">
            <div class="row">
                @include('layouts.header')
            </div>

            @include('layouts.navbar')

            <div class="row margin-top-10">
                @include('shared.alert')
            </div>
            <div class="row">
                <div class="col-sm-8">
                    @yield('content')
                </div>
                <div class="col-sm-4">
                    @include('layouts.right')
                </div>
            </div>
            <div class="row">
                @include('errors.list')
            </div>
            <hr>
            @include('layouts.footer')
        </div> <!-- /container -->


        <script type="text/javascript" src="/js/all.js"></script>
        
    </body>
</html>
