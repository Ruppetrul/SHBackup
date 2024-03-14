<link rel="stylesheet" type="text/css" href="{{ asset('home/css/custom.css') }}">
<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <button class="btn btn-primary " id="openModalBtn" onclick="window.location='{{ route('shops.view') }}'">{{__('shop.back_to_shops')}}</button>
        </div>
    </div>
    @if($shop->state === 'deleted')
        <div class="p-6 col d-flex align-items-center justify-content-center">
            <div class="card text-center p-4">
                <div class="card-body">
                    <h3>{{__('shop.shop_deleted')}}</h3>
                </div>
            </div>
        </div>
    @else
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-600">
                    @if ($shop->is_attachment_tg !== 1)
                        <h3><strong>Ваш магазин ещё не привязан к телеграм боту.</strong></h3>
                    @elseif ($shop->tg_name != '')
                        Привязан к Telegram боту <span>@</span>{{$shop->tg_name}}
                    @endif

                    <br><br>
                    <button id="showAddTelegramButton" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        @if ($shop->is_attachment_tg !== 1)
                            Привязать к боту
                        @else
                            Сменить бота
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-600">
                        <h3><strong> @if($shop['name']) {{ $shop['name'] }} @endif</strong> (@if ($shop['state']) {{__('states.' . $shop['state'])}} @endif)</h3>
                        <br>
                        <p>{{__('general.created_at')}} @if($shop['created_at']) {{$shop['created_at']->format('Y-m-d')}} @endif</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-600">
                        @if (!$success)
                            <div class="p-6 col d-flex align-items-center justify-content-center">
                                <div class="card text-center p-4">
                                    <div class="card-body">
                                        <h5 class="card-title">{{__('shop.unknown_error')}}</h5>
                                    </div>
                                </div>
                            </div>
                        @else
                            <ul class="nav nav-tabs" id="shopTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active"
                                            id="home-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#home"
                                            type="button"
                                            role="tab"
                                            aria-controls="home"
                                            aria-selected="true">{{__('shop.items')}}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link"
                                            id="order-history-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#order_history"
                                            type="button"
                                            role="tab"
                                            aria-controls="order_history"
                                            aria-selected="true">{{__('shop.order_history')}}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link"
                                            id="options-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#options"
                                            type="button"
                                            role="tab"
                                            aria-controls="options"
                                            aria-selected="false">{{__('shop.options')}}</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="shopTabsContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    @if (count($products) == 0)
                                        <div class="p-6 col d-flex align-items-center justify-content-center">
                                            <div class="card text-center p-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{__('shop.no_products')}}</h5>
                                                    <a href="{{ route('product.create.view', ['shopId' => $shop['id']]) }}"  class="btn btn-primary mt-3">{{__('shop.add_item')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="overflow-hidden sm:rounded-lg">
                                            <div class="p-6">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div></div>
                                                    <a href="{{ route('product.create.view', ['shopId' => $shop['id']]) }}"  class="btn btn-primary mt-3">{{__('shop.add_item')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="container py-6">
                                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                                            @foreach ($products as $product)
                                                <div class="col mb-4">
                                                    <div class="card">
                                                        @if (isset($product['avatar_url']))
                                                            <img src="{{ $product['avatar_url'] }}" class="item-card-img" alt="{{ $product['title'] }}">
                                                        @else
                                                            <img src="https://placekitten.com/300/200" class="item-card-img" alt="{{ $product['title'] }}">
                                                        @endif
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $product['title'] }}</h5>
                                                            <p class="card-text">{{__('general.created_at')}} {{ $product['created_at'] }}</p>
                                                            <p class="card-text">{{__('general.status')}} {{ $product['status'] }}</p>
                                                            <p class="card-text">{{__('shop.item_price')}} {{ $product['price'] }}</p>
                                                            <div class="d-flex">
                                                                <a class="btn btn-primary me-2 flex-grow-1 editButton" href="{{ route('product.edit.view', ['shopId' => $shop['id'], 'itemId' => $product['id']]) }}">Редактировать</a>
                                                                <button class="btn btn-danger ms-auto deleteButton" data-product-id="{{ $product['id'] }}"><i class="fas fa-trash-alt"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="order_history" role="tabpanel" aria-labelledby="order-history">
                                    @if (isset($orders))
                                        @foreach ($orders as $order)
                                            <div class="list-group py-3">
                                                <a href="#" class="list-group-item list-group-item-action">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h5 class="mb-1"></h5>
                                                        <small>{{ $order['created_at'] }}</small>
                                                    </div>
                                                    <p class="mb-1">{{ __('shop.order_total') }} {{ $order['total'] }}</p>
                                                    <ul class="list-group">
                                                        @foreach ($order['lines'] as $line)
                                                            <li class="list-group-item">
                                                                <div class="d-flex w-100 justify-content-between">
                                                                    <h6 class="mb-1">{{ $line->title }}</h6>
                                                                    <span>{{ __('shop.quantity') }}: {{ $line->quantity }}</span>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="options" role="tabpanel" aria-labelledby="options-tab">
                                    <div class="py-6">
                                        <button class="btn btn-danger btn-block" id="deleteShop">{{__('shop.delete')}}</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">{{__('shop.delete')}}</h5>
                    </div>
                    <div class="modal-body">
                        {{__('shop.delete_info')}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal" id="confirmDeleteBtn">{{__('shop.delete_confirm')}}</button>
                        <button type="button" class="btn" id="cancelDeleteShopBtn">{{__('shop.delete_cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Модальное окно -->
        <div class="modal fade" id="addTelegramModal" tabindex="-1" role="dialog" aria-labelledby="createShopModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createShopModalLabel">Подключить магазин к Telegram</h5>
                    </div>
                    <div class="modal-body">
                        <p>Создайте бота в Telegram с помощью Telegram Father.</p>
                        <p>Скопируйте ваш токен и вставте сюда.</p>
                        <br>
                        <label for="telegram_token">Telegram token:</label>
                        <input type="text" id="telegram_token" class="form-control" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" id="cancelAddTelegramBtn" data-dismiss="modal">Отмена</button>
                        <button type="button" class="btn" id="addTelegramBtn">Подключить</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        $(document).ready(function() {
            $('#showAddTelegramButton, #cancelAddTelegramBtn').click(function() {
                $('#addTelegramModal').modal('toggle');
            });

            $('#addTelegramBtn').click(function() {
                const telegram_token = $('#telegram_token').val();
                if (telegram_token === '') {
                    alert("{{ __('shop.input_telegram_token') }}");
                    return false;
                }

                const csrf_token = document.querySelector('meta[name="csrf-token"]').content;

                console.log({{ $shop['id'] }});
                add_telegram_token(
                    telegram_token,
                    csrf_token,
                    "{{ route('shop.add_telegram_token', ['shopId' => $shop['id']]) }}",
                    function (response) {
                        $('#addTelegramModal').modal('toggle');
                    },
                    function (error) {
                    }
                );
            });

            $('.deleteButton').on('click', function() {
                const productId = $(this).data('product-id');

                const formData = new FormData();
                formData.append('id', productId);

                delete_item(
                    formData,
                    '{{ route('product.delete', ['shopId' => $shop['id']]) }}',
                    document.querySelector('meta[name="csrf-token"]').content,
                    function (response) {
                    },
                    function (error) {
                    }
                );
            });
        });

        $(document).on('click', '#deleteShop', function() {

            $('#confirmDeleteModal').modal('toggle');

            $('#cancelDeleteShopBtn').click(function() {
                $('#confirmDeleteModal').modal('toggle');
            });

            $('#confirmDeleteBtn').on('click', function() {
                delete_shop(
                    '{{ route('shop.delete', ['shopId' => $shop['id']]) }}',
                    document.querySelector('meta[name="csrf-token"]').content,
                    function (response) {
                        window.location.href = '{{ route('shops.view') }}';
                    },
                    function (error) {
                    }
                );

                $('#confirmDeleteModal').modal('hide');
            });
        });
    </script>
</x-app-layout>
