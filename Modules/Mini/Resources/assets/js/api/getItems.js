import axios from 'axios';

export async function getItems(params, is_paginate, items, setHasMore, shop_id) {
    return axios.get(`/mini/${shop_id}/ajax/products?${params.toString()}`);
}
