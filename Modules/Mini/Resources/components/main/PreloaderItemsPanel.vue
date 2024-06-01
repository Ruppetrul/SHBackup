<script setup>
  import PreloaderItem from './PreloaderItem.vue';
</script>

<template>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5" id="items_panel">
    <PreloaderItem v-for="index in 10" :key="index"/>
  </div>
</template>

<style scoped>
  #items_panel {
    padding: 10px;
  }
</style>
