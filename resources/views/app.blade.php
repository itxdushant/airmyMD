<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">


        <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/style.css')}}" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

        <!-- Styles -->
        <!-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> -->

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
        @inertiaHead
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxJ_jS4zF8RliqqsoZDKUiN8zPN9fMiy8&libraries=places"></script>
    </head>
    <body class="font-sans antialiased">
        @inertia

        @env ('local')
            <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
        @endenv
    </body>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}" ></script>
    <script src="{{asset('js/jquery.min.js')}}" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
</html>
