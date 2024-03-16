<div class="col-xxl-12 col-lg-12">
    <div class="show-button">
        <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
        <div class="top-filter-menu">
            <div class="dropdown">
                <select class="custom-select" id="filterSelect">
                    {{--TODO move to transltations--}}
                    <option selected value="default">По умолчанию</option>
{{--                    <option value="new">Сначала новые</option>--}}
{{--                    <option value="old">Сначала старые</option>--}}
                    <option value="expensive">Сначала дорогие</option>
                    <option value="cheap">Сначала дешевые</option>
                </select>
            </div>
            <div class="grid-option">
                <ul>
                    <li class="three-grid d-xxl-inline-block d-none">
                        <a href="javascript:void(0)">
                            <img src="{{ asset('home/svg/grid-3.svg') }}" class="blur-up lazyload" alt="">
                        </a>
                    </li>
                    <li class="grid-btn active">
                        <a href="javascript:void(0)">
                            <img src="{{ asset('home/svg/grid-4.svg') }}"
                                 class="blur-up lazyload d-lg-inline-block d-none" alt="">
                            <img src="{{ asset('home/svg/grid.svg') }}"
                                 class="blur-up lazyload img-fluid d-lg-none d-inline-block" alt="">
                        </a>
                    </li>
                    <li class="list-btn">
                        <a href="javascript:void(0)">
                            <img src="{{ asset('home/svg/list.svg') }}" class="blur-up lazyload" alt="">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="mini_content" class="row g-sm-4 g-3 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2 product-list-section">
        @include('Mini::Pages.mini.section.products', ['products' => $products])
    </div>
</div>
<div class="col-xxl-12 fixed-margin">
    <div class="fixed-result">
        <div class="summery-box">
            <script>
                check_and_init_web_main_button('/mini/{{ $shopId }}/carts', 'Корзина');
            </script>
        </div>
    </div>
</div>

@if(session()->has('success_message'))
    <div id="notification" class="alert alert-success position-fixed top-0 start-50 translate-middle-x" role="alert">
        {{ session('success_message') }}
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var notification = document.getElementById('notification');
            notification.style.display = 'block';
            document.body.style.paddingTop = notification.offsetHeight + 'px';
            setTimeout(function() {
                notification.style.display = 'none';
                document.body.style.paddingTop = '0';
            }, 3000);
        });
    </script>
@endif
<script src="https://yastatic.net/jquery/3.3.1/jquery.min.js"></script>
@section('js')
    <script>
        $(document).ready(function() {

            let debounceTimer;
            const debounceTime = 300;

            $(".product-list-section").on('click', '.btn-add-cart' ,function () {
                clearTimeout(debounceTimer);
                tg_disable_main_button();

                const item_id = $(this).attr('data-id');
                const template = `<div class="cart_qty qty-box product-qty">
                                    <div class="input-group">
                                      <button class="cart-qty-left-minus" type="button" data-type="minus" data-product="${item_id}">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                      </button>
                                      <input name="quantity[${item_id}]" id="quantity_${item_id}" class="form-control input-number qty-input" type="text" value="1">
                                      <button class="cart-qty-right-plus" type="button" data-type="plus" data-product="${item_id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                      </button>
                                    </div>
                                  </div>`;
                $(`[data-id=${item_id}]`).parent().parent().append(template);
                $(`[data-id=${item_id}]`).parent().remove();

                debounceTimer = setTimeout(() => {
                    item_add(item_id);
                    tg_enable_main_button();
                }, debounceTime);

            });

            $('.product-list-section').on('click', '.cart-qty-right-plus', function(){
                clearTimeout(debounceTimer);
                tg_disable_main_button();

                const item_id = $(this).attr('data-product');
                const new_count =  Number($('#quantity_' + item_id).val()) + 1;
                $('#quantity_' + item_id).val(new_count);

                debounceTimer = setTimeout(() => {
                    item_update(item_id, new_count);
                    tg_enable_main_button();
                }, debounceTime);
            });

            $('.product-list-section').on('click', '.cart-qty-left-minus', function(){
                clearTimeout(debounceTimer);
                tg_disable_main_button();

                const item_id = $(this).attr('data-product');
                if ((Number($('#quantity_' + item_id).val()))>1){
                const new_count =  Number($('#quantity_' + item_id).val()) - 1;
                $('#quantity_' + item_id).val(new_count);
                    debounceTimer = setTimeout(() => {
                        item_update(item_id, new_count);
                        tg_enable_main_button();
                    }, debounceTime);
                } else{
                    const template = `<div class="add-to-cart-box bg-white">
                                        <a data-id="${item_id}" class="btn btn-add-cart addcart-button">
                                          Добавить в корзину
                                        </a>
                                      </div>`;
                    $(`[data-product=${item_id}]`).parent().parent().parent().append(template);
                    $(`[data-product=${item_id}]`).parent().parent().remove();

                    debounceTimer = setTimeout(() => {
                        item_delete(item_id);
                        tg_enable_main_button();
                    }, debounceTime);
                }
            });
        });
    </script>
@endsection
