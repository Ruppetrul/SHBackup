<script setup>
import {defineProps} from "vue";

const props = defineProps(['item'])
</script>

<template>
    <div class="cart_item">
        <div class="item_name">
            {{ props.item.title }}
        </div>
    </div>
</template>

<style scoped>
</style>
