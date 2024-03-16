@section('title', $product->title)

@section('content')
    <section class="product-section">
        <div class="container-fluid-lg">
            <div class="row justify-content-center">
                <div class="col-xxl-9 col-xl-8 col-lg-7 wow fadeInUp">
                    <div class="row g-4">
                        <div class="col-xl-6 wow fadeInUp">
                            <div class="product-left-box">
                                <div class="row g-2">
{{--                                    <div class="col-12">--}}
{{--                                        <div class="no-arrow product-main-1">--}}
{{--                                            <img src="--}}
{{--                                            @if (isset($product->avatar[0]->filename))--}}
{{--                                               {{ asset('storage/' . $shopId . '/' . $product->avatar[0]->filename) }}--}}
{{--                                            @else--}}
{{--                                                {{ asset('home/images/default_item_img.jpg') }}--}}
{{--                                            @endif--}}
{{--                                             " class="img-fluid blur-up lazyload" alt="">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-12">
                                        <div class="bottom-slider-image left-slider no-arrow slick-top">
                                            <div class="slider_navv">
                                                @foreach ($product->medias as $media)
{{--                                                    <div class="sidebar-image">--}}
                                                        <img src="{{ asset('storage/' . $shopId . '/' . $media->filename) }}" alt="gallery"
                                                        class="img-fluid blur-up lazyload">
{{--                                                    </div>--}}
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="right-box-contain right-box-contain-mini">
                                <h2 class="name">{{ $product->title }}</h2>
                                <div class="price-rating">
                                    <h3 class="theme-color price">
                                        {{ $product->getPrice() }} ₽
                                    </h3>
                                </div>
                                <div class="procuct-contain">
                                    <p>
                                        {{ $product->short_description }}
                                    </p>
                                </div>
                                <div class="note-box product-packege">
                                    @if (isset($cart_detail[$product['id']]))
                                    <div class="cart_qty qty-box product-qty">
                                        <div class="input-group">
                                            <button id="qty-left-minus" type="button" data-type="minus" data-field="">
                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                            </button>
                                            <input id="quantity" class="form-control input-number qty-input" type="text"
                                                   name="quantity"
                                                   @if (isset($cart_detail[$product['id']]))
                                                       value="{{ $cart_detail[$product['id']]['quantity'] }}"
                                                   @else
                                                       value="0"
                                                   @endif
                                            >
                                            <button id="qty-right-plus" type="button" data-type="plus"
                                                    data-field="">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    @if (!isset($cart_detail[$product['id']]))
                                    <button data-id="{{ $product['id'] }}" id="btn-add-cart"
                                            class="btn btn-md bg-dark cart-button text-white w-100">
                                        Добавить в корзину
                                    </button>
                                    @endif
                                </div>

                                <div class="buy-box">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-12 fixed-margin">
                <div class="fixed-result">
                    <div class="summery-box">
                        <script>
                            check_and_init_web_main_button('/mini/{{ $shopId }}/carts', 'Оформить заказ');
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script src="https://yastatic.net/jquery/3.3.1/jquery.min.js"></script>
@section('js')
    <script>
       $(document).ready(function() {
           $('.slider_navv').slick({
               centerMode: true,
               centerPadding: '40px',
               slidesToShow: 1,
               responsive: [
                   {
                       breakpoint: 768,
                       settings: {
                           arrows: false,
                           centerMode: true,
                           centerPadding: '140px',
                           slidesToShow: 3
                       }
                   },
                   {
                       breakpoint: 480,
                       settings: {
                           arrows: false,
                           centerMode: true,
                           centerPadding: '40px',
                           slidesToShow: 1
                       }
                   }
               ]
           });

            let debounceTimer;
            const debounceTime = 300;

            $(".note-box").on('click', '#btn-add-cart' ,function () {
                clearTimeout(debounceTimer);
                tg_disable_main_button();

                const item_id = {{ $product->id }};
                const template = `<div class="cart_qty qty-box product-qty">
                                      <div class="input-group">
                                          <button id="qty-left-minus" type="button" data-type="minus">
                                              <i class="fa fa-minus" aria-hidden="true"></i>
                                          </button>
                                          <input name="quantity[${item_id}]" id="quantity" class="form-control input-number qty-input" type="text" value="1">
                                          <button id="qty-right-plus" type="button" data-type="plus">
                                              <i class="fa fa-plus" aria-hidden="true"></i>
                                          </button>
                                      </div>
                                  </div>`;
                $(`[data-id=${item_id}]`).parent().append(template);
                $(`[data-id=${item_id}]`).remove();

                debounceTimer = setTimeout(() => {
                    item_add(item_id);
                    tg_enable_main_button();
                }, debounceTime);

            });

            $('.note-box').on('click', '#qty-right-plus', function(){
                clearTimeout(debounceTimer);
                tg_disable_main_button();

                const item_id = {{ $product->id }};
                const new_count =  Number($('#quantity').val()) + 1;
                $('#quantity').val(new_count);

                debounceTimer = setTimeout(() => {
                    item_update(item_id, new_count);
                    tg_enable_main_button();
                }, debounceTime);
            });

            $('.note-box').on('click', '#qty-left-minus', function(){
                clearTimeout(debounceTimer);
                tg_disable_main_button();

                const item_id = {{ $product->id }};

                if (Number($('#quantity').val()) > 1){
                const new_count =  Number($('#quantity').val()) - 1;
                $('#quantity').val(new_count);
                    debounceTimer = setTimeout(() => {
                        item_update(item_id, new_count);
                    }, debounceTime);
                } else{
                    const template = `<button data-id="{{ $product['id'] }}" id="btn-add-cart" class="btn btn-md bg-dark cart-button text-white w-100">
                                          Добавить в корзину
                                      </button>`;

                    $(`#quantity`).parent().parent().parent().append(template);
                    $(`#quantity`).parent().parent().remove();

                    debounceTimer = setTimeout(() => {
                        item_delete(item_id);
                        tg_enable_main_button();
                    }, debounceTime);
                }
            });
        });
    </script>
@endsection
