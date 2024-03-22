<!DOCTYPE html>
<html lang="en">
    <head>
        @include('Mini::section.meta') {{-- Include meta tags --}}
        <meta name="csrf-token" content="{{csrf_token()}}">
        <script src="https://telegram.org/js/telegram-web-app.js"></script>
        <script src="{{ asset('home/js/modules/mini/tg_helper.js') }}?v=2"></script>
        <script>
            tg_init();
            //order
            tg_init_main_button('', 'Оформить заказ!');
            tg_update_main_button_total({{ $cart_total }});
        </script>

        <title>{{ config('app.name') }} - {{ env('APP_SHOP_DESCRIPTION') }}</title>

        @include('Mini::section.css') {{-- Include css files --}}
    </head>
    <body class="bg-effect">
        @yield('content') {{-- Yield content data --}}
        <div class="bg-overlay"></div>
        <div class="bg-overlay"></div>
    </body>
</html>
