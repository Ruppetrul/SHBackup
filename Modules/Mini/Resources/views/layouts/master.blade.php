<!DOCTYPE html>
<html lang="en">
    <head>
        @include('Mini::section.meta') {{-- Include meta tags --}}

        <title>{{ config('app.name') }} - Большой выбор запчастей и аксесуаров для электросамокатов в ижевске</title>

        @include('Mini::section.css') {{-- Include css files --}}
    </head>
    <body class="bg-effect">
{{--        @include('Home::Home.section.preloader') --}}{{-- Include loader --}}
        @include('Mini::section.header')
,
 [$cart_detail]
 ) {{-- Include header --}}
{{--        @include('Home::Home.section.mobile-menu') --}}{{-- Include mobile menu --}}
            @yield('content') {{-- Yield content data --}}
        @include('Mini::section.footer') {{-- Include footer --}}
        @include('Mini::section.theme-options') {{-- Include theme options --}}
        @include('Mini::section.js') {{-- Include js files --}}
    </body>
</html>
