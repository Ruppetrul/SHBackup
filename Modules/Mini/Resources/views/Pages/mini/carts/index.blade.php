@extends('Mini::layouts.carts.carts')

@section('content')
<section class="section-b-space shop-section">
    <div class="container-fluid-lg">
        <div class="row">
            @include('Mini::Pages.mini.section.carts', ['products' => ShopEloquent::getLatestActiveProducts()])
        </div>
    </div>
</section>
@endsection
