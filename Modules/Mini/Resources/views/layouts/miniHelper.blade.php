<script>

    const shopId = {{ $shopId }};
    const baseUrl = '{{ url('') }}';

    let tg_instance = null;
    let is_tg = false;

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

/* Telegram */
    function tg_get_instance() {
        if (!tg_instance) {
            tg_instance = window.Telegram.WebApp;
        }

        if (tg_instance.initData) {
            is_tg = true;
        }

        return tg_instance;
    }

    function tg_init(){
        const tg = tg_get_instance();
        tg.expand();
    }

    function tg_update_main_button_total(total) {
        const tg = tg_get_instance();
        total = total.toLocaleString(
            'ru-RU',
            {
                style: 'currency',
                currency: 'RUB',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
        tg.MainButton.setText('Оформить заказ (' + total + ')');
        tg.MainButton.show();
    }

    function tg_disable_main_button() {
        const tg = tg_get_instance();
        tg.MainButton.disable();
        tg.MainButton.showProgress();
    }

    function tg_enable_main_button() {
        const tg = tg_get_instance();
        tg.MainButton.enable();
        tg.MainButton.hideProgress();
    }

    function tg_init_back_button() {
        const tg = tg_get_instance();
        tg.BackButton.onClick(function() {
            window.history.back();
        });
        tg.BackButton.show();
    }

    function tg_back_button_hide() {
        const tg = tg_get_instance();
        tg.BackButton.hide();
    }

    function tg_init_main_button(link, title) {
        const tg = tg_get_instance();
        tg.MainButton.onClick(function() {
            window.location.href = link;
        });

        tg.MainButton.setText(title);
        tg.MainButton.show();
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

            button.addEventListener('click', function() {
                window.location.href = url;
            });

            const summeryBox = document.querySelector('.summery-box');
            summeryBox.appendChild(button);
        }
    }
</script>
