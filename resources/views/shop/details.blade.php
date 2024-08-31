<link rel="stylesheet" type="text/css" href="{{ asset('home/css/custom.css') }}">
<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <button class="btn btn-primary " id="openModalBtn"
                    onclick="window.location='{{ route('shops.view') }}'">{{__('shop.back_to_shops')}}</button>
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
                        <h3><strong> @if($shop['name'])
                                    {{ $shop['name'] }}
                                @endif</strong> (@if ($shop['state'])
                                {{__('states.' . $shop['state'])}}
                            @endif)</h3>
                        <div class="flex items-center mt-2">
                            Ваш магазин доступен тут:
                            <a href="{{ route('mini.mini', ['shopIdOrName' => $shop['id']]) }}"
                               id="shopLink"
                               class="mr-2 px-2 py-1 border-gray-300 rounded-md"
                               target="_blank"
                               rel="noopener noreferrer"
                               style="text-decoration: underline;">{{ route('mini.mini', ['shopIdOrName' => $shop['id']]) }}</a>
                        </div>
                        <br>
                        <p>{{__('general.created_at')}} @if($shop['created_at'])
                                {{$shop['created_at']->format('Y-m-d')}}
                            @endif</p>
                    </div>
                </div>
            </div>
        </div>
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
                        <button id="showAddTelegramButton"
                                class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
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
                                            id="categories-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#categories"
                                            type="button"
                                            role="tab"
                                            aria-controls="categories"
                                            aria-selected="false">{{__('shop.categories')}}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link"
                                            id="order-history-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#order_history"
                                            type="button"
                                            role="tab"
                                            aria-controls="order_history"
                                            aria-selected="false">{{__('shop.order_history')}}</button>
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
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link"
                                            id="shop_payment-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#shop_payment"
                                            type="button"
                                            role="tab"
                                            aria-controls="shop_payment"
                                            aria-selected="false">{{__('shop.shop_payment')}}</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="shopTabsContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                     aria-labelledby="home-tab">

                                    <div class="container py-6">
                                        @if (count($products) == 0)
                                            <div class="p-6 col d-flex align-items-center justify-content-center">
                                                <div class="card text-center p-4">
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{__('shop.no_products')}}</h5>
                                                        <a href="{{ route('product.create.view', ['shopId' => $shop['id']]) }}"
                                                           class="btn btn-primary mt-3">{{__('shop.add_item')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="overflow-hidden sm:rounded-lg">
                                                <div class="d-flex align-items-center" style="padding-bottom: 1.5rem">
                                                    <div></div>
                                                    <a href="{{ route('product.create.view', ['shopId' => $shop['id']]) }}"
                                                       class="btn btn-primary mt-3">{{__('shop.add_item')}}</a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                                            @foreach ($products as $product)
                                                <div class="col mb-4">
                                                    <div class="card">
                                                        @if (isset($product['avatar_url']))
                                                            <img src="{{ $product['avatar_url'] }}"
                                                                 class="item-card-img" alt="{{ $product['title'] }}">
                                                        @else
                                                            <img src="{{ asset('home/images/default_item_img.jpg') }}"
                                                                 class="item-card-img" alt="{{ $product['title'] }}">
                                                        @endif
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $product['title'] }}</h5>
                                                            <p class="card-text">{{__('general.created_at')}} {{ $product['created_at'] }}</p>
                                                            <p class="card-text">{{__('general.status')}} {{ $product['status'] }}</p>
                                                            <p class="card-text">{{__('shop.item_price')}} {{ $product['price'] }}</p>
                                                            <div class="d-flex">
                                                                <a class="btn btn-primary me-2 flex-grow-1 editButton"
                                                                   href="{{ route('product.edit.view', ['shopId' => $shop['id'], 'itemId' => $product['id']]) }}">Редактировать</a>
                                                                <button class="btn btn-danger ms-auto deleteButton"
                                                                        data-product-id="{{ $product['id'] }}"><i
                                                                        class="fas fa-trash-alt"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="order_history" role="tabpanel"
                                     aria-labelledby="order-history">
                                    @if (isset($orders) && count($orders))
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
                                    @else
                                        <div class="text-center py-5">
                                            <p>{{ __('shop.no_orders_message') }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="options" role="tabpanel" aria-labelledby="options-tab">
                                    <div class="py-6">
                                        <button class="btn btn-danger btn-block"
                                                id="deleteShop">{{__('shop.delete')}}</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="shop_payment" role="tabpanel"
                                     aria-labelledby="shop_payment-tab" style="padding-top: 10px">
                                    <form method="POST"
                                          action="{{ route('shop.save-yookassa-token', ['shopId' => $shop['id']]) }}">
                                        @csrf
                                        <h1>В данный момент поддерживается только интеграция с ЮКаssa!</h1> <br>
                                        <h1>Инструкция по подключению:</h1>
                                        <a> 1. Зарегистрируйтесь в ЮКаsse.</a><br>
                                        <a> 2. В ЮKassa скопируйте ShopID из раздела Настройки -> Магазин</a><br>
                                        <a> 3. Перейдите в чат с ботом @BotFather в Telegram</a><br>
                                        <a> 4. Отправте команду /mybots и нажмите кнопку Payments</a><br>
                                        <a> 5. Из списка платежных систем выберите ЮКаssa и укажите ваш ShopID</a><br>
                                        <a> В ответ бот @BotFather вам пришлет платежный токен в формате:
                                            "390540012:LIVE:27425". Укажите его в поле Платежный токен</a><br>
                                        <h4>Платежный токен</h4>
                                        <input type="text" name="yookassa_token" class="form-control"
                                               value="{{ $yookassaToken }}"/>
                                        <div class="py-6">
                                            <button class="btn btn-danger btn-block" id="shop_payment">Сохранить
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                                    <div class="py-6">
                                        <button class="btn btn-danger btn-block" id="categories_add">Создать категорию
                                        </button>
                                    </div>
                                    <div>
                                        @if (isset($categories) && count($categories))
                                            @foreach ($categories as $category)
                                                <div>
                                                    <div class="list-group py-1">
                                                        <a href="#" class="list-group-item list-group-item-action">
                                                            {{ $category->name }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
             aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">{{__('shop.delete')}}</h5>
                    </div>
                    <div class="modal-body">
                        {{__('shop.delete_info')}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"
                                id="confirmDeleteBtn">{{__('shop.delete_confirm')}}</button>
                        <button type="button" class="btn" id="cancelDeleteShopBtn">{{__('shop.delete_cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Модальное окно -->
        <div class="modal fade" id="addTelegramModal" tabindex="-1" role="dialog" aria-labelledby="createShopModalLabel"
             aria-hidden="true">
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
                        <input type="text" id="telegram_token" class="form-control"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" id="cancelAddTelegramBtn" data-dismiss="modal">Отмена</button>
                        <button type="button" class="btn" id="addTelegramBtn">Подключить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно -->
        <div class="modal fade" id="createCategory" tabindex="-1" role="dialog" aria-labelledby="createCategoryLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCategoryLabel">Как назвать категорию?</h5>
                    </div>
                    <div class="modal-body">
{{--                        <label for="shop_name">Название категории:</label>--}}
                        <input type="text" id="category_name" class="form-control" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" id="cancelCreateCategoryBtn" data-dismiss="modal">Отмена</button>
                        <button type="button" class="btn" id="createCreateCategoryBtn">Создать</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        function copyShopLink(event) {
            var shopLink = document.getElementById('shopLink');
            shopLink.select();
            document.execCommand('copy');

            var copiedNotification = document.createElement('div');
            copiedNotification.textContent = 'Ссылка скопирована!';
            copiedNotification.classList.add('bg-green-500', 'px-4', 'py-2', 'rounded-md', 'absolute', 'z-10');

            var buttonRect = event.target.getBoundingClientRect();
            copiedNotification.style.top = buttonRect.top - 20 + 'px';
            copiedNotification.style.left = buttonRect.left + 'px';

            document.body.appendChild(copiedNotification);

            setTimeout(function () {
                copiedNotification.remove();
            }, 2000);
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#showAddTelegramButton, #cancelAddTelegramBtn').click(function () {
                $('#addTelegramModal').modal('toggle');
            });

            $('#addTelegramBtn').click(function () {
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

            $('.deleteButton').on('click', function () {
                const productId = $(this).data('product-id');

                const formData = new FormData();
                formData.append('id', productId);

                delete_item(
                    formData,
                    '{{ route('product.delete', ['shopId' => $shop['id']]) }}',
                    document.querySelector('meta[name="csrf-token"]').content,
                    function (response) {
                        window.location.reload();
                    },
                    function (error) {
                    }
                );
            });
        });

        $(document).on('click', '#deleteShop', function () {

            $('#confirmDeleteModal').modal('toggle');

            $('#cancelDeleteShopBtn').click(function () {
                $('#confirmDeleteModal').modal('toggle');
            });

            $('#confirmDeleteBtn').on('click', function () {
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

        //Categories
        $('#categories_add').click(function() {
            $('#createCategory').modal('toggle');
        });

        $('#cancelCreateCategoryBtn, #createCreateCategoryBtn').click(function () {
            $('#createCategory').modal('toggle');
        });

        $('#createCreateCategoryBtn').click(function () {
            const categoryName = $('#category_name').val();
            category_add(
                categoryName,
                document.querySelector('meta[name="csrf-token"]').content,
                '{{ route('categories.store', ['shopId' => $shop['id']]) }}',
                function (response) {
                    window.location.reload();
                },
                function (error) {
                }
            );
        });
    </script>
</x-app-layout>
