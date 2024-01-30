<x-app-layout>
    <div class="container py-6">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ isset($item) ? __('shop.edit_item') : __('shop.new_item') }}</div>
                    <div class="card-body">
                        <form method="POST"
                              @if(isset($item))
                                  action="{{route('product.update',  ['shopId' => $shopId, 'itemId' => $item['id']] )}}"
                              @else
                                  action="{{route('product.create',  ['shopId' => $shopId])}}"
                              @endif
                        >
                            @csrf
                            @if(isset($item))
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label for="name">{{ __('shop.item_title') }}</label>
                                <x-text-input id="product_name" class="block mt-1 w-full" type="text" name="title" value="{{ isset($item) ? $item['title'] : old('title') }}" required autofocus autocomplete="title" />

                                <label for="price">{{ __('shop.item_price') }}</label>
                                <x-text-input id="product_price" class="block mt-1 w-full" type="text" name="price"
                                              value="{{ isset($item) ? $item['price'] : old('price') }}"
                                              required autofocus autocomplete="price"
                                              inputmode="numeric" oninput="formatDecimal(this)" pattern="[0-9]+([.][0-9]{0,2})?"
                                />

                                <label for="images">{{ __('shop.item_avatar') }}</label> <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input data-item-id="{{ isset($item) ? $item['id'] : null }}" id="imagePanelAvatar" type="file" name="file" multiple>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div id="imagePanel" class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex overflow-auto" style="height: 150px;">
                                                        <img src="
                                                            @if (isset($item['avatar']))
                                                                {{ asset('storage/' . $shopId . '/' . $item['avatar']) }}
                                                            @else
                                                                {{ asset('home/images/default_item_img.jpg') }}
                                                            @endif
                                                            " class="img-fluid blur-up lazyload" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label for="images">{{ __('shop.item_images') }}</label> <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input data-item-id="{{ isset($item) ? $item['id'] : null }}" id="imagePanelAdditional" type="file" name="file" multiple>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div id="imagePanel" class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex overflow-auto" style="height: 150px;">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn">{{ isset($item) ? __('general.update') : __('general.create') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#imagePanelAvatar').on('change', function () {
                var formData = new FormData();
                formData.append('file', $(this)[0].files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                const item_id = $(this).data('item-id');
                $.ajax({
                    url: '/shops/{{ $shopId }}/product/update-avatar/' + item_id,  // Замените на ваш роут для загрузки файлов
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        const imagePanel = document.getElementById('imagePanel');
                        imagePanel.innerHTML = '';

                        const imgElement = document.createElement('img');
                        imgElement.src = response.path;
                        imgElement.classList.add('mr-2');

                        imagePanel.appendChild(imgElement);
                    },
                    error: function (error) {
                        console.error('File error', error);
                    }
                });
            });

            $('#imagePanelAdditional').on('change', function () {
                var formData = new FormData();
                formData.append('file', $(this)[0].files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                const item_id = $(this).data('item-id');
                $.ajax({
                    url: '/shops/{{ $shopId }}/product/update-image/' + item_id,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        const imgElement = document.createElement('img');
                        imgElement.src = response;
                        imgElement.classList.add('mr-2');
                        document.getElementById('imagePanel').appendChild(imgElement);
                    },
                    error: function (error) {
                        console.error('File error', error);
                    }
                });
            });
        });
    </script>
    <script>
        function formatDecimal(input) {
            input.value = input.value.replace(/[^0-9.]/g, '');

            var parts = input.value.split('.');

            if (parts.length > 1) {
                parts[1] = parts[1].slice(0, 2);
                input.value = parts.join('.');
            }

            if (parts.length > 2) {
                input.value = parts.slice(0, 1).join('.') + '.' + parts.slice(1).join('').slice(0, 2);
            }
        }
    </script>
</x-app-layout>
