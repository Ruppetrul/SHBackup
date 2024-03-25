<script>

    const shopId = {{ $shopId }};
    const baseUrl = '{{ url('') }}';

/* Api */
    function do_request(item_id, url, data = {}, successCallback){

        url = baseUrl + '/' + url;
        if (data === null || typeof data !== 'object') {
            data = {};
        }

        data.product_id = item_id;
        data["_token"] = document.querySelector('meta[name="csrf-token"]').content;

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function (data) {
                console.log("Success:", data);
                if (successCallback && typeof successCallback === 'function') {
                    successCallback(data);
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

    function item_update(item_id, new_count, successCallback = null){
        var url = 'mini/' + shopId + `/cart-add/${item_id}/${new_count}`;
        const data = {
            new_count: new_count
        }

        do_request(item_id, url, data, successCallback);
    }

    function item_delete(item_id){
        var url = 'mini/' + shopId + "/cart/" + item_id + "/delete";

        do_request(item_id, url);
    }

    function item_add(item_id, successCallback = null){
        var url = 'mini/' + shopId + "/cart-add/" + item_id;

        do_request(item_id, url, successCallback);
    }

/* User actions */
    function search(url, search, successCallback) {
        $.ajax({
            type: "GET",
            url: url,
            data: {
                search: search,
                "_token": document.querySelector('meta[name="csrf-token"]').content,
            },
            success: function (data) {
                console.log("Success:", data);
                if (successCallback && typeof successCallback === 'function') {
                    successCallback(data);
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

    function check_and_init_web_main_button(url, text) {
        if (!is_tg) {
            const button = document.createElement('button');
            button.classList.add('btn', 'btn-lg', 'btn-light', 'w-100');
            button.textContent = text;
            button.type = 'submit';

            button.addEventListener('click', function() {
                if (url) {
                    window.location.href = url;
                } else {
                    document.getElementById("order").submit();
                    //create_order_request();
                }
            });

            const summeryBox = document.querySelector('.summery-box');
            summeryBox.appendChild(button);
        }
    }

    function create_order_request() {
        const formData = new FormData(document.getElementById('order'));
        const params = Object.fromEntries(formData);

        params["_token"] = document.querySelector('meta[name="csrf-token"]').content;

        $.ajax({
            type: "GET",
            url: '{{ route("home.create.order", ['shopIdOrName' => $shopName]) }}',
            data: params,
            success: function (data) {
                window.location.href = data.redirect_url;
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
</script>
