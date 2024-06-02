<script setup>
    import { Link } from '@inertiajs/vue3'
    const props = defineProps(['item'])
</script>

<template>
    <div class="cart_item">
        <div class="container">
            <div class="row">
                <div class="item_avatar col-2">
                    <Link :href="`/mini/${props.shop_id}/detail/${props.item.id}`">
                        <img :src="props.item.avatar_url" width="100%" height="100%" alt="Дефолтная картинка" loading="lazy">
                    </Link>
                </div>
                <div class="col">
                    <div class="cart_title_panel">
                        <div class="item_title">{{ props.item.title }}</div>
                        <div class="item_price">{{ props.item.price }} р.</div>
                    </div>

                    <div class="count_panel">
                        <button>-</button>
                        <div class="item_count">{{ props.item.count }}</div>
                        <button>+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.item_avatar, .item_title, .item_price {
    border-radius: 5px;
}

.cart_title_panel {
    display: flex;
    justify-content: space-between;
}

.item_price {
    text-align: right;
}

.count_panel {
    display: flex;
}

.item_avatar {
    width: 10vw;
    height: 10vw;
    min-height: 4em;
    min-width: 4em;
}

.item_price {
    margin-top: 10px;
    width: 10vw;
}

.cart_item {
    margin-top: 10px;
}

</style>
