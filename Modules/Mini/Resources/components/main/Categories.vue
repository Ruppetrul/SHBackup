<script setup>
  import { defineProps } from "vue";

  import CategoryItem from "./CategoryItem.vue";

  const props = defineProps(['categories', 'clickHandler'])
</script>

<template>
    <div id="categories-panel" class="py-3">
        <CategoryItem v-for="category in props.categories" :category="category" :clickHandler="clickHandler">
        </CategoryItem>
    </div>
</template>

<style scoped>
    #categories-panel {
        display: flex;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        width: 100%;
    }

    CategoryItem {
        display: inline-block;
    }
</style>
