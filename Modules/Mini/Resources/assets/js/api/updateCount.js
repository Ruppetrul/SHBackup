import axios from 'axios';

export async function updateCount(item_id, count, shop_id) {
    return axios.post(`/mini/${shop_id}/cart-add/${item_id}/${count}`, null, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
}
