import axios from 'axios';

export async function getItem(item_id, shop_id) {
    try {
        const { data } = await axios.get(`/mini/${shop_id}/ajax/product/${item_id}`);
        return data;
    } catch (error) {
        console.error('Error getItem:', error);
        throw error;
    }
}
