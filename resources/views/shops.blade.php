<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
{{--                {{ __('Shops') }}--}}
            </h2>
            <button class="btn btn-primary" id="openModalBtn">Создать магазин</button>
        </div>
    </x-slot>

    <div class="container py-6">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
            @foreach ($shops as $shop)
                <div class="col mb-4">
                    <div class="card">
                        <img src="https://placekitten.com/300/200" class="card-img-top" alt="{{ $shop['name'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $shop['name'] }}</h5>
                            <p class="card-text">{{__('general.created_at')}} {{ $shop['created_at']->format('Y-m-d') }}</p>
                            <p class="card-text">{{__('general.status')}} {{__('states.' . $shop['state'])}}</p>
                        </div>
                        <button class="btn btn-primary btn-sm edit-button" style="">{{__('general.management')}}</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

<!-- Модальное окно -->
<div class="modal fade" id="createShopModal" tabindex="-1" role="dialog" aria-labelledby="createShopModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createShopModalLabel">Как называется ваш магазин?</h5>
            </div>
            <div class="modal-body">
                <label for="shop_name">Название магазина:</label>
                <input type="text" id="shop_name" class="form-control" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" id="cancelShopBtn" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn" id="createShopBtn">Создать</button>
            </div>
        </div>
    </div>
</div>

<!-- Прелоадер -->
<div id="preloader" class="d-none">
    <!-- Здесь можно использовать какой-то спиннер или анимацию загрузки -->
    Loading...
</div>


<script>
    $(document).ready(function() {
        $('#openModalBtn, #cancelShopBtn').click(function() {
            $('#createShopModal').modal('toggle');
        });

        $('#createShopBtn').click(function() {
            const shop_name = $('#shop_name').val();
            if (shop_name == '') {
                alert('Введите название магазина');
                return false;
            }

            $.ajax({
                url: "{{ route('shops.create') }}",
                type: "POST",
                data: {
                    name: shop_name,
                    _token : document.querySelector('meta[name="csrf-token"]').content
                },
                success: function(response) {
                    if (response.success) {
                        $('#createShopModal').on('hidden.bs.modal', function () {
                            alert('Магазин успешно создан');
                            location.reload();
                        });
                        $('#createShopModal').modal('toggle');
                    } else {
                        alert(response.message || 'Неизвестная ошибка');
                    }
                }
            });
        });
    });
</script>
