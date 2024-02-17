@extends('Mini::layouts.mini')

@section('content')
<section class="section-b-space shop-section mini-infinite-scroll-container">
    <div class="container-fluid-lg">
        <div class="input-group">
            <input id="search_text" name="search" type="search" class="form-control" placeholder="Что ищем?" aria-label="Recipient's username" aria-describedby="button-addon2">
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
        let priorityFilter = false;

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

        function clearProductsContainer() {
            const container = document.querySelector('.product-list-section');
            container.innerHTML = "";
        }

        function appendProducts(data) {
            const container = document.querySelector('.product-list-section');
            container.insertAdjacentHTML('beforeend', data);
        }

        function loadMoreProducts(changedFilters = false) {
            if (changedFilters) {
                loadedPages = 0;
            }

            let params = {
                page: loadedPages + 1
            };

            const search_text = document.getElementById("search_text").value;
            if (search_text) {
                params.search = encodeURIComponent(search_text);
            }

            if (priorityFilter) {
                params.priority_filter = encodeURIComponent(priorityFilter);
            }

            const queryString = Object.keys(params).map(key => key + '=' + params[key]).join('&');
            let url = `/mini/{{ $shopId }}/ajax/products?${queryString}`;

            $.ajax({
                type: "GET",
                url: url,
                success: function(data) {
                    if (data.success) {
                        if (data.total) {
                            loadedPages++;
                        }
                        clearProductsContainer();
                        appendProducts(data.view);

                        if (!data.has_more) {
                            isEnd = true;
                        } else {
                            isEnd = false;
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

        let searchInputDebounceTimer;
        document.getElementById("search_text").addEventListener("input", function() {
            clearTimeout(searchInputDebounceTimer);

            searchInputDebounceTimer = setTimeout(async () => {
                loadMoreProducts(true);
            }, 300);
        });

        let selectElement = document.getElementById("filterSelect");

        selectElement.addEventListener("change", function() {
            priorityFilter = selectElement.value;
            loadMoreProducts(true);
        });
    });
</script>
@endsection
