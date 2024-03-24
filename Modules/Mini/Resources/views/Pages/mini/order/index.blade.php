@extends('Mini::layouts.order.order')

@section('content')
<section class="section-b-space shop-section">
    <div class="container-fluid-lg">
        <div class="row">
            @include('Mini::Pages.mini.section.order', ['products' => ShopEloquent::getLatestActiveProducts()])
        </div>
    </div>
</section>
@endsection
