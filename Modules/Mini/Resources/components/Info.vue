<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps(['company_data', 'shop_id']);

</script>

<template>
    <div class="container">
        <Link :href="`/mini/${props.shop_id}/`" class="back-link">
            <span class="material-icons">Назад на главную</span>
        </Link>
        <div class="info-block">
            <div class="info-item">
                <span class="info-label">Номер:</span>
                <span class="info-value">{{ props.company_data.number || 'Не указан' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Фио:</span>
                <span class="info-value">{{ props.company_data.fio || 'Не указан' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Адрес:</span>
                <span class="info-value">{{ props.company_data.address || 'Не указан' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">ИНН:</span>
                <span class="info-value">{{ props.company_data.inn || 'Не указан' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">ОГРНИП:</span>
                <span class="info-value">{{ props.company_data.ogrnip || 'Не указан' }}</span>
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
    text-decoration: none;
    display: flex;
    align-items: center;
}

.back-link .material-icons {
    margin-right: 8px; /* Space between icon and text */
}

.back-link:hover {
    text-decoration: underline;
}

.info-block {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
