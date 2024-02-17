@if (count($products) == 0)
    <div class="container-fluid">
        <h2>Измените параметры поиска или обратитесь к автору магазина.</h2>
    </div>
@endif

@foreach ($products as $product)
    <div>
        <div class="product-box-3 h-100 wow fadeInUp">
            <div class="product-header">
                <div class="product-image">
                    <a href="{{ route('home.details', ['shopIdOrName' => $shopName, 'itemId' => $product->id]) }}">
                        <img src="
                        @if (isset($product->avatar[0]->filename))
                            {{ asset('storage/' . $shopId . '/' . $product->avatar[0]->filename) }}
                        @else
                            {{ asset('home/images/default_item_img.jpg') }}
                        @endif
                        " class="img-fluid blur-up lazyload" alt="">
                    </a>
                </div>
            </div>
            <div class="product-body">
                <div class="product-detail">
                    <a href="{{ route('home.details', ['shopIdOrName' => $shopName, 'itemId' => $product->id]) }}">
                        <h5 class="name">{{ $product->title }}</h5>
                    </a>
                    <p class="text-content mt-1 mb-2 product-content">
                        {{ $product->description }}
                    </p>
                    <h5 class="price">
                        <span class="theme-color"> {{ $product->getPrice() }} ₽</span>
                    </h5>
                </div>
            </div>
            <div class="product-footer" style="display: flex; justify-content: center;">
                @if (isset($cart_detail[$product['id']]))
                    <div class="cart_qty qty-box product-qty">
                        <div class="input-group">
                            <button class="cart-qty-left-minus" type="button" data-type="minus" data-product="{{ $product['id'] }}">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </button>
                            <input name="quantity[{{ $product['id'] }}]" id="quantity_{{ $product['id'] }}" class="form-control input-number qty-input" type="text"
                                   @if (isset($cart_detail[$product['id']]))
                                       value="{{ $cart_detail[$product['id']]['quantity'] }}"
                                   @else
                                       value="0"
                                @endif
                            >
                            <button class="cart-qty-right-plus" type="button" data-type="plus"
                                    data-product="{{$product['id']}}">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="add-to-cart-box bg-white">
                        <a data-id="{{ $product['id'] }}" class="btn btn-add-cart addcart-button">
                            Добавить в корзину
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach
