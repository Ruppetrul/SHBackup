<script setup>
  import Item from './Item.vue';
  import { defineProps, onMounted, watch, ref } from "vue";

  const props = defineProps(['items', 'paginate', 'has_more', 'shop_id'])
</script>

<template>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5" id="items_panel">
    <Item v-for="(item, index) in props.items" :key="index" :itemData="item" :shop_id="shop_id"/>
  </div>
</template>

<style scoped>
  #items_panel {
    padding: 10px;
  }
</style>
