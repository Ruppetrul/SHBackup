<script setup>
  import Layout from './Layout.vue';
  import tgHelper from "../js/tg_helper.js";

  const props = defineProps(['shop_id', 'item_id']);
  const shop_id = props.shop_id;

  tgHelper.tg_init();
  if (tgHelper.is_tg) {
      tgHelper.tg_init_main_button('/mini/' + shop_id + '/order', 'Оплатить');
      tgHelper.tg_back_button_hide();
  }
</script>

<template>
  <Layout>
    <h1>Order page</h1>
  </Layout>
</template>

<style scoped>
</style>
