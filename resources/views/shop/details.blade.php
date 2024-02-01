<x-app-layout>
    <div class="py-6">
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
    <div class="py-6">
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
                                                    <img src="https://placekitten.com/300/200" class="card-img-top" alt="{{ $product['title'] }}">
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
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.deleteButton').on('click', function() {
                const productId = $(this).data('product-id');

                const data = {
                    id: productId,
                };

                const formData = new FormData();
                formData.append('id', productId);

                customer_do_request({
                    method: 'DELETE',
                    url: '{{ route('product.delete', ['shopId' => $shop['id']]) }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        alert(response);
                        location.reload();
                    },
                    error: function (error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</x-app-layout>
