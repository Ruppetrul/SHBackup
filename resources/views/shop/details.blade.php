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
                    <div class="p-6 text-gray-900 dark:text-gray-100">
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
                    <div class="p-6 text-gray-900 dark:text-gray-100">
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
                                            id="profile-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#profile"
                                            type="button" role="tab"
                                            aria-controls="profile"
                                            aria-selected="false">{{__('shop.order_history')}}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link"
                                            id="contact-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#contact"
                                            type="button"
                                            role="tab"
                                            aria-controls="contact"
                                            aria-selected="false">{{__('shop.analytics')}}</button>
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
                                                            <img src="{{ $product['avatar_url'] }}" class="card-img-top" alt="{{ $product['title'] }}">
                                                        @else
                                                            <img src="https://placekitten.com/300/200" class="card-img-top" alt="{{ $product['title'] }}">
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
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
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
    @endif
    <script>
        $(document).ready(function() {
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
