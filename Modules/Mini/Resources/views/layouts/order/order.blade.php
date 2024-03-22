<!DOCTYPE html>
<html lang="en">
    <head>
        @include('Mini::section.meta') {{-- Include meta tags --}}

        <script src="https://telegram.org/js/telegram-web-app.js"></script>
        <script src="{{ asset('home/js/modules/mini/tg_helper.js') }}?v=2"></script>
        <script>
            tg_init();
            tg_init_main_button('/mini/{{ $shopId }}/carts', 'Корзина');
            tg_back_button_hide();
        </script>

        <title>{{ config('app.name') }} - {{ env('APP_SHOP_DESCRIPTION') }}</title>

        @include('Mini::section.css') {{-- Include css files --}}
    </head>
    <body class="bg-effect">
        @yield('content') {{-- Yield content data --}}
        <div class="bg-overlay"></div>
        <div class="bg-overlay"></div>
        @include('Mini::section.js') {{-- Include js files --}}
    </body>
</html>
