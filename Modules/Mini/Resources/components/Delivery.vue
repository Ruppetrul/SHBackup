<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps(['company_data', 'shop_id']);

</script>

<template>
    <div class="container">
        <Link :href="`/mini/${props.shop_id}/`" class="back-link">
            <span class="material-icons">Назад на главную</span>
        </Link>
        <a>Доставка возможна через Почту России и ТК Сдэк. Стоимость и сроки рассчитываются ИНДИВИДУАЛЬНО.</a>
    </div>
</template>

<style scoped>
.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
}

.back-link {
    margin: 20px 0;
    padding: 10px 20px;
    font-size: 16px;
    color: #007bff;
    background: none;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    align-items: center;
}

.back-link .material-icons {
    margin-right: 8px;
}

.back-link:hover {
    text-decoration: underline;
}

</style>
