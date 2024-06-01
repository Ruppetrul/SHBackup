<script setup>
import {defineProps} from "vue";

const props = defineProps(['item'])
</script>

<template>
    <div class="cart_item">
        <div class="item_name">
            {{ props.item.title }}
        </div>
        <div class="item_count">
            {{ props.item.count }}
        </div>
    </div>
</template>

<style scoped>
.cart_item {
    display: flex;
    justify-content: space-between;
}
.item_count {
    text-align: right;
}
</style>
