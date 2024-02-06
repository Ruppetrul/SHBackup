<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <button class="btn btn-primary " id="openModalBtn" onclick="window.location='{{ route('shop.details',  ['shopId' => $shopId]) }}'">{{__('shop.back_to_products')}}</button>
        </div>
    </div>
    <div class="container py-3">
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
                                                            <div class="product-image-container" >
                                                                <ul id="avatarPanel" class="sortable-container">
                                                                    <li class="sortable-item">
                                                                        @if (isset($item['avatar']))
                                                                            <img style="height: 150px;" src="
                                                                                {{ asset('storage/' . $shopId . '/' . $item['avatar']) }}
                                                                            " class="img-fluid blur-up lazyload" alt="">
                                                                            <div class="product-close-icon deleteMedia" data-media-type="avatar" data-media-id="{{ $item['first_media_id'] }}">✖</div>
                                                                        @endif
                                                                    </li>
                                                                </ul>
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
                                            <input disabled data-item-id="{{ isset($item) ? $item['id'] : null }}" id="imagePanelAdditional" type="file" name="file" multiple>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <ul id="imagePanel" class="sortable-container">
                                                @foreach($item['medias'] ?? [] as $media)
                                                    <li class="sortable-item">
                                                        <img src="{{ $media->url }}" alt="Image"  style="height: 150px;">
                                                        <div class="delete-icon deleteMedia" data-media-type="image" data-media-id="{{ $media->id }}">✖</div>
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
        function updateImagePanel() {
            const additionalInput = document.getElementById('imagePanelAdditional');
            const mediasCount = document.querySelectorAll('#imagePanel .sortable-item').length;

            if (mediasCount <= 2) {
                additionalInput.disabled = false;
            }
        }

        updateImagePanel();

        const sortableList = new Sortable(document.getElementById('imagePanel'), {
            animation: 150,
            onEnd: function (evt) {
            }
        });

        const avatarSortableList = new Sortable(document.getElementById('avatarPanel'), {
            animation: 150,
            onEnd: function (evt) {
            }
        });

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

        $(document).ready(function () {
            $(document).on('click', '.deleteMedia', function (event) {
                const element = $(event.target);
                const formData = new FormData();
                const mediaType =  this.getAttribute('data-media-type');
                formData.append('media_id',  this.getAttribute('data-media-id'));
                delete_media(
                    formData,
                    '{{ route('product.delete.media', ['shopId' => $shopId]) }}',
                    document.querySelector('meta[name="csrf-token"]').content,
                    function (response) {
                        const item = event.currentTarget.closest('.sortable-item');
                        console.log(item);
                        item.remove();

                        if (mediaType === 'avatar') {
                            updateAvatarSuccess()
                        } else {
                            updateImagePanel();
                        }
                    },
                    function (error) {
                        console.error('File error', error);
                    }
                );

                function updateAvatarSuccess() {
                    const fileInput = document.getElementById('imagePanelAvatar');
                    fileInput.disabled = false;
                }
            });

            $('#imagePanelAdditional, #imagePanelAvatar').on('change', function (element) {
                const  formData = new FormData();
                formData.append('file', $(this)[0].files[0]);
                formData.append('itemId', $(this).data('item-id'));
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                const elementId = element.currentTarget.getAttribute('id');

                if (elementId === 'imagePanelAvatar') {
                    formData.append('mediaType', 'avatar');
                }

                update_media(
                    formData,
                    '{{ route('product.update.image', ['shopId' => $shopId]) }}',
                    function (response) {
                        if (response.url) {
                            let imagePanel = null;

                            if (elementId === 'imagePanelAvatar') {
                                imagePanel = document.getElementById('avatarPanel');
                            } else if (elementId === 'imagePanelAdditional') {
                                imagePanel = document.getElementById('imagePanel');
                            }

                            const imageContainer = document.createElement('li');
                            imageContainer.classList.add('sortable-item');

                            const imgElement = document.createElement('img');
                            imgElement.src = response.url;
                            imgElement.style.height = '150px';
                            imgElement.alt = 'Image';

                            const closeIcon = document.createElement('div');
                            closeIcon.classList.add('delete-icon', 'deleteMedia');
                            closeIcon.setAttribute('data-media-id', response.media_id);
                            closeIcon.setAttribute('data-media-type', elementId === 'imagePanelAvatar' ? 'avatar' : 'image');
                            closeIcon.textContent = '✖';

                            imageContainer.appendChild(imgElement);
                            imageContainer.appendChild(closeIcon);

                            imagePanel.appendChild(imageContainer);

                            if (elementId === 'imagePanelAvatar') {
                                const fileInput = document.getElementById('imagePanelAvatar');
                                fileInput.disabled = true;
                            } else if (elementId === 'imagePanelAdditional') {
                                updateImagePanel();
                            }
                        } else {
                            alert(message || "{{ __('shop.unknown_error') }}");
                        }
                    },
                    function (error) {
                        console.error('File error', error);
                    }
                );
            });
        });
    </script>
</x-app-layout>
