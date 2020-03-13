<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Google Map Location Provider</title>
        <!-- app icon -- -->
        <link rel="shortcut icon" type="image/png" href="/images/logo.png">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="app">
            <main-app/>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBGQ4RFLbX_ObaSpYDq5moqE5_bcVDQbnI&libraries=places" async="" defer=""></script> 
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
