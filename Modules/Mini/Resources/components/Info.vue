<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps(['company_data', 'shop_id']);

</script>

<template>
    <div class="container">
        <Link :href="`/mini/${props.shop_id}/`" class="back-link">Назад на главную</Link>
        <div class="info-block">
            <div class="info-item">
                <span class="info-label">Номер:</span>
                <span class="info-value">{{ props.company_data.number }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Фио:</span>
                <span class="info-value">{{ props.company_data.fio }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Адрес:</span>
                <span class="info-value">{{ props.company_data.address }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">ИНН:</span>
                <span class="info-value">{{ props.company_data.inn }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">ОГРНИП:</span>
                <span class="info-value">{{ props.company_data.ogrnip }}</span>
            </div>
        </div>
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
    text-decoration: underline;
}

.back-link:hover {
    text-decoration: none;
}

.info-block {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px; /* Optional: space between columns */
    padding: 20px;
    background-color: #fff; /* Optional: background color for the block */
    border-radius: 8px; /* Optional: rounded corners */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional: shadow for the block */
}

.info-item {
    display: contents;
}

.info-label {
    font-weight: bold;
}

.info-value {
    text-align: right;
}
</style>
