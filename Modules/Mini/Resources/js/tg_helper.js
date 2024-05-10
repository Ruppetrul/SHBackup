let tg_instance = null;
let is_tg = false;

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
    console.log('tg_init_main_button');
    if (link) {
        console.log('link!');
        tg.MainButton.onClick(function() {
            window.location.href = link;
        });
    } else {
        console.log('not link!');
        tg.MainButton.onClick(function() {
            tg.MainButton.showProgress();
            document.getElementById("order").submit();
        });
    }

    tg.MainButton.setText(title);
    tg.MainButton.show();
}

const tgHelper = {
    tg_get_instance,
    tg_init,
    tg_update_main_button_total,
    tg_disable_main_button,
    tg_enable_main_button,
    tg_init_back_button,
    tg_back_button_hide,
    tg_init_main_button,
};

export default tgHelper;
