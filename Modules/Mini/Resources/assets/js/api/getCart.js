import axios from 'axios';

export async function getCart(shop_id, items) {
    return axios.get(`/mini/${shop_id}/ajax/cart?`)
        .then(response => {
            items.value = response.data.details;
        })
        .catch(error => {
        });
}
