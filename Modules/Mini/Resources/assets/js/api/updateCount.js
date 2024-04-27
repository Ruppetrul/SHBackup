import axios from 'axios';

export async function updateCount(item_id, count, shop_id) {
    axios.post(`/mini/${shop_id}/cart-add/${item_id}/${count}`, null, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
    })
    .catch(error => {
    });
}
