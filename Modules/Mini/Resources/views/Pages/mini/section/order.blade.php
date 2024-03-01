@section('content')
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Оформление заказа</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="cart-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-sm-5 g-3">
                <div class="col-xxl-12">
                    @if($cart_detail)
                    <form>
                        <div class="form-group mb-3">
                            <label for="cash" class="mb-2 fw-bold">Оплата</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cash" id="cash" checked>
                                <label class="form-check-label" for="cash">
                                    Наличными при получении
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cash" id="bank" disabled >
                                <label class="form-check-label" for="bank">
                                Оплата банковской картой
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cash" id="crypto" disabled >
                                <label class="form-check-label" for="crypto">
                                    Оплата криптовалютой USDT
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="pickup" class="mb-2 fw-bold">Доставка</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery" id="pickup" checked>
                                <label class="form-check-label" for="pickup">
                                    Самовывоз
                                </label>
                                <small id="emailHelp" style="display: block" class="form-text text-muted">Товар вы можете получить по прибытии в наш офис</small>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery" id="courier" disabled>
                                <label class="form-check-label" for="courier">
                                    Доставка курьером
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone" class="fw-bold mb-2">Контактный телефон <span style="color: red;">*</span>:</label>
                            <input class="form-control" type="number" style="max-width: 800px" id="phone">
                        </div>
                        <div class="form-group">
                            <label for="adress" class="fw-bold mb-2">Адрес доставки <span style="color: red;">*</span>:</label>
                            <input class="form-control" style="max-width: 800px" id="adress">
                        </div>
                    </form>

                    <div class="col-xxl-12 fixed-margin">
                        <div class="fixed-result">
                            <div class="summery-box">
                                <div class="summery-header">
                                    <h3>Итого с доставкой: <span class="theme-color">{{ $cart_total }} ₽</span></h3>
                                </div>
                                <ul class="summery-total">
                                    <li class="list-total border-top-0">
                                        <span class="text-muted">Доставка: Бесплатно</span>
                                    </li>
                                </ul>
                                <script>
                                    check_and_init_web_main_button('', 'Оформить');
                                </script>
                            </div>
                        </div>
                    </div>
                    @else
                    <h1>Сейчас ваша корзина пуста!</h1>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
