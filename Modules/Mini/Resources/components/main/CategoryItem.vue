<script setup>
  import { defineProps } from "vue";

  const props = defineProps(['category', 'clickHandler']);
</script>

<template>
    <div class="categories-container" @click="props.clickHandler(props.category.id)">
        <h3 class="categories-name">{{ props.category.name }}</h3>
    </div>
</template>

<style scoped>
.categories-container {
    border-radius: 15%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 10em;
    border: 1px solid #ddd;
    box-sizing: border-box;
}

.categories-name {
    border-radius: 15%;
    background: rgba(0, 0, 0, 0.5);
    color: #fff;
    text-align: center;
    padding: 0.5em;
    box-sizing: border-box;
}
</style>

