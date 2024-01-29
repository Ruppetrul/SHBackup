@section('content')
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Корзина</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(isset($message))
        <a style="
        display: table;
        margin: 0 auto 0 auto;
        font-size: 3em;
        margin: 2ex;
            ">{{ $message }}</a>
    @endif

    <section class="cart-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-sm-5 g-3">
                <div class="col-xxl-12">
                    @if($cart_detail)
                    @foreach($cart_detail as $product)
                            <div class="cart-table mini-cart-table">
                                <div class="table-responsive-xl">
                                    <div class="card">
                                        <div class="row no-gutters">
                                            <div class="col-xs-2 col-sm-3 col-md-3 col-lg-2 col-xl-2">
                                                <a href="{{ route('home.details', ['shopId' => $shopId, 'itemId' => $product['id']]) }}">
                                                    <img src="http://simply-shop/home/images/default_item_img.jpg" class="card-img" alt="product image">
                                                </a>
                                            </div>
                                            <div class="col-xs-10 col-sm-9 col-md-9 col-lg-10">
                                                <div class="card h-100">
                                                    <div class="card-body d-flex flex-column">
                                                        <div class="h-50">
                                                            <h5 class="card-title">
                                                                <a href="http://simply-shop/86/mini/3/detail">Тестовый товар 5</a>
                                                            </h5>
                                                        </div>
                                                        <div class="h-50">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="input-group">
                                                                        <button class="btn btn-outline-secondary" type="button" data-type="minus" data-product="3">
                                                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                                                        </button>
                                                                        <input name="quantity[3]" id="quantity_3" class="form-control input-number qty-input" type="text" value="2">
                                                                        <button class="btn btn-outline-secondary" type="button" data-type="plus" data-product="3">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7"></div>
                                                                <div class="col-md-2">
                                                                    <div class="ml-auto mt-3">
                                                                        <h6 class="card-subtitle mb-2 text-muted">Всего</h6>
                                                                        <div class="input-group">
                                                                            <p class="card-text text-nowrap">155.4 ₽</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                      <h1>Сейчас ваша корзина пуста!</h1>
                    @endif
                </div>
        </div>
        </div>
    </section>
@endsection

<script src="https://yastatic.net/jquery/3.3.1/jquery.min.js"></script>
@section('js')
    <script>
        let debounceTimer;
        const debounceTime = 300;

        $('body').on('click', '.cart-qty-right-plus', function() {
            clearTimeout(debounceTimer);
            tg_disable_main_button();

            const item_id = $(this).attr('data-product');
            const new_count =  Number($('#quantity_' + item_id).val()) + 1;
            $('#quantity_' + item_id).val(new_count);

            debounceTimer = setTimeout(() => {
                item_update(item_id, new_count, function (responseData) {
                    tg_update_main_button_total(responseData.total);
                });
                tg_enable_main_button();
            }, debounceTime);
        });

        $('body').on('click', '.cart-qty-left-minus', function() {
            const item_id = $(this).attr('data-product');
            const quantity = Number($('#quantity_' + item_id).val())
            if (quantity > 1) {
                clearTimeout(debounceTimer);
                tg_disable_main_button();

                const new_count =  quantity - 1;
                $('#quantity_' + item_id).val(new_count);

                debounceTimer = setTimeout(() => {
                    item_update(item_id, new_count, function (responseData) {
                        tg_update_main_button_total(responseData.total);
                    });
                    tg_enable_main_button();
                }, debounceTime);
            }
        });
    </script>
@endsection
