<script setup>
  import { defineProps, computed } from "vue";

  import CategoryItem from "./CategoryItem.vue";

  const props = defineProps(['categories', 'clickHandler'])
  const categoriesWithAll = computed(() => [
      { name: 'Все категории' },
      ...props.categories
  ]);
</script>

<template>
    <div id="categories-panel" class="py-3">
        <CategoryItem v-for="category in categoriesWithAll" :category="category" :clickHandler="clickHandler">
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
