<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shops') }}
        </h2>
    </x-slot>

    @php
        $shops = [
            ['name' => 'Shop 1', 'image' => 'https://placekitten.com/300/200'],
            ['name' => 'Shop 2', 'image' => 'https://placekitten.com/300/200'],
        ];
    @endphp

    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
            @foreach ($shops as $shop)
                <div class="col mb-4">
                    <div class="card">
                        <img src="{{ $shop['image'] }}" class="card-img-top" alt="{{ $shop['name'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $shop['name'] }}</h5>
                            <p class="card-text">Description</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
