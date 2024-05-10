<!DOCTYPE html>
<html lang="en">
    <head>
        @include('Mini::section.meta') {{-- Include meta tags --}}
        <meta name="csrf-token" content="{{csrf_token()}}">
        <script src="https://telegram.org/js/telegram-web-app.js"></script>
        <title>{{ config('app.name') }} - {{ env('APP_SHOP_DESCRIPTION') }}</title>

        @include('Mini::section.css') {{-- Include css files --}}
    </head>
    <body class="bg-effect">
        @inertia
        @yield('content') {{-- Yield content data --}}
    </body>
</html>
