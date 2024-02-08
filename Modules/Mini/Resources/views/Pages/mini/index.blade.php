@extends('Mini::layouts.mini')

@section('content')
<section class="section-b-space shop-section mini-infinite-scroll-container">
    <div class="container-fluid-lg">
        <div class="input-group">
            <input name="search" type="search" class="form-control" placeholder="Что ищем?" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn" >
                <svg class="w-6 h-6 text-gray-800 dark:text-white" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                    <path d="M18.85 1.1A1.99 1.99 0 0 0 17.063 0H2.937a2 2 0 0 0-1.566 3.242L6.99 9.868 7 14a1 1 0 0 0 .4.8l4 3A1 1 0 0 0 13 17l.01-7.134 5.66-6.676a1.99 1.99 0 0 0 .18-2.09Z"/>
                  </svg>
            </button>
        </div>
        <div class="row">
            @include('Mini::Pages.mini.section.mini', ['products' => $miniRepo->getLatestActiveProducts()])
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        let loadedPages = 1;
        let loading = false;
        let isEnd = false;
        let isError = false;

        function isScrollPastElementBottom(element) {
            var elementBottom = element.offset().top + element.height();
            return $(window).scrollTop() + $(window).height() >= elementBottom;
        }

        const container = $('.mini-infinite-scroll-container');

        $(window).scroll(function() {
            if (
                !loading
                && isScrollPastElementBottom(container)
            ) {
                if (!isEnd && !isError) {
                    loading = true;
                    loadMoreProducts();
                }
            }
        });

        function appendProducts(data) {
            const container = document.querySelector('.product-list-section');
            container.insertAdjacentHTML('beforeend', data);
        }

        function loadMoreProducts() {
            $.ajax({
                type: "GET",
                url: "/mini/{{ $shopId }}/ajax/products?page=" + (loadedPages + 1),
                success: function(data) {
                    if (data.success) {
                        if (data.total) {
                            loadedPages++;
                            appendProducts(data.view);
                        }
                        if (!data.has_more) {
                            isEnd = true;
                        }
                        loading = false;
                    }
                },
                error: function(data) {
                    console.log('Ups! Some error:', data);
                    loading = false;
                    isError = true;
                }
            });
        }
    });
</script>
@endsection
