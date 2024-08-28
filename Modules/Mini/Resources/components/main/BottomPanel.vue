<script setup>
import {defineProps} from "vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps(['company_data', 'shop_id'])
</script>

<template>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5" id="items_panel">
      <Link :href="`/mini/${props.shop_id}/info`">Контакты</Link>
  </div>
</template>

<style scoped>
  #items_panel {
    padding: 10px;
  }
</style>
