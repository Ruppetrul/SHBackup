<x-app-layout>
    <div class="container py-6">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ isset($product) ? __('shop.edit_item') : __('shop.new_item') }}</div>
                    <div class="card-body">
                        <form method="POST"
                              action="{{route('product.create',  ['shopId' => $shopId])}}"
                        >
                            @csrf
                            @if(isset($product))
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label for="name">{{ __('shop.item_title') }}</label>
                                <x-text-input id="product_name" class="block mt-1 w-full" type="text" name="title" value="{{ isset($product) ? $product->name : old('name') }}" required autofocus autocomplete="title" />
                            </div>
                            <button type="submit" class="btn">{{ isset($product) ? __('general.update') : __('general.create') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
