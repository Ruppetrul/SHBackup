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

                                @if(isset($item))
                                    <label for="images">{{ __('shop.item_avatar') }}</label> <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input
                                                @if (isset($item['avatar']))
                                                    disabled
                                                @endif
                                                data-item-id="{{ isset($item) ? $item['id'] : null }}" id="imagePanelAvatar" type="file" name="file" multiple>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="d-flex overflow-auto">
                                                            <div id="avatarImagePanel" >
                                                                <div class="product-image-container" >
                                                                    @if (isset($item['avatar']))
                                                                        <img style="height: 150px;" src="
                                                                            {{ asset('storage/' . $shopId . '/' . $item['avatar']) }}
                                                                        " class="img-fluid blur-up lazyload" alt="">
                                                                        <div class="product-close-icon" data-media-id="{{ $item['first_media_id'] }}" onclick="deleteImage(this)">✖</div>
                                                                    @endif
                                                                </div>
                                                            </div>
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
                                            <ul id="imagePanel" class="sortable-container">
                                                @foreach($item['medias'] ?? [] as $media)
                                                    <li class="sortable-item">
                                                        <img src="{{ $media->url }}" alt="Image"  style="height: 150px;">
                                                        <div class="delete-icon" data-media-id="{{ $media->id }}" onclick="removeImage(this)">✖</div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn">{{ isset($item) ? __('general.update') : __('general.create') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const sortableList = new Sortable(document.getElementById('imagePanel'), {
            animation: 150,
            onEnd: function (evt) {
            }
        });

        function removeImage(element) {
            const mediaId = element.getAttribute('data-media-id');

            $.ajax({
                url: '/shops/{{ $shopId }}/product/delete-media',
                type: 'DELETE',
                data: {
                    media_id : mediaId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log(element);
                    const item = element.closest('.sortable-item');
                    item.remove();
                },
                error: function (error) {
                    console.error('File error', error);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#imagePanelAvatar').on('change', function () {
                var formData = new FormData();
                formData.append('file', $(this)[0].files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                const item_id = $(this).data('item-id');
                formData.append('itemId', item_id);
                $.ajax({
                    url: '/shops/{{ $shopId }}/product/update-avatar',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        const imagePanel = document.getElementById('avatarImagePanel');

                        const imageContainer = document.createElement('div');
                        imageContainer.classList.add('product-image-container');

                        const imgElement = document.createElement('img');
                        imgElement.src = response.url;
                        imgElement.style.height = '150px';
                        imgElement.classList.add('img-fluid', 'blur-up', 'lazyload');
                        imgElement.alt = '';

                        const closeIcon = document.createElement('div');
                        closeIcon.classList.add('product-close-icon');
                        closeIcon.setAttribute('data-media-id', response.media_id);
                        closeIcon.textContent = '✖';
                        closeIcon.onclick = function () {
                            deleteImage(this);
                        };

                        imageContainer.appendChild(imgElement);
                        imageContainer.appendChild(closeIcon);

                        imagePanel.appendChild(imageContainer);

                        const fileInput = document.getElementById('imagePanelAvatar');
                        fileInput.disabled = true;
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
                formData.append('itemId', item_id);
                $.ajax({
                    url: '/shops/{{ $shopId }}/product/update-image',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        const imagePanel = document.getElementById('imagePanel');

                        const imageContainer = document.createElement('li');
                        imageContainer.classList.add('sortable-item');

                        const imgElement = document.createElement('img');
                        imgElement.src = response.url;
                        imgElement.style.height = '150px';
                        imgElement.alt = 'Image';

                        const closeIcon = document.createElement('div');
                        closeIcon.classList.add('delete-icon');
                        closeIcon.setAttribute('data-media-id', response.media_id);
                        closeIcon.textContent = '✖';
                        closeIcon.onclick = function () {
                            removeImage(this);
                        };

                        imageContainer.appendChild(imgElement);
                        imageContainer.appendChild(closeIcon);

                        imagePanel.appendChild(imageContainer);
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

        function deleteImage(element) {
            const mediaId = element.getAttribute('data-media-id');

            $.ajax({
                url: '/shops/{{ $shopId }}/product/delete-media',
                type: 'DELETE',
                data: {
                    media_id : mediaId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    const imagePanel = document.getElementById('avatarImagePanel');
                    imagePanel.innerHTML = '';

                    const fileInput = document.getElementById('imagePanelAvatar');
                    fileInput.disabled = false;
                },
                error: function (error) {
                    console.error('File error', error);
                }
            });
        }
    </script>
</x-app-layout>
