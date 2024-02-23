<!DOCTYPE html>
<html lang="en">
    <head>
        @include('Mini::section.meta') {{-- Include meta tags --}}
        <meta name="csrf-token" content="{{csrf_token()}}">
        <script src="https://telegram.org/js/telegram-web-app.js"></script>
        @include('Mini::layouts.miniHelper')
        <script>
            tg_init();

            tg_init_main_button('/mini/{{ $shopid }}/carts', 'Оформить заказ');
            tg_init_back_button();
        </script>
        <title>{{ config('app.name') }} - {{ env('APP_SHOP_DESCRIPTION') }}</title>

        @include('Mini::section.css') {{-- Include css files --}}
        <link href="{{ asset('font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body class="bg-effect">
        @yield('content') {{-- Yield content data --}}
        <div class="bg-overlay"></div>
        <div class="bg-overlay"></div>
        @include('Mini::section.js') {{-- Include js files --}}
    </body>
</html>
