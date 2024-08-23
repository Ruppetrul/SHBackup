<script setup>
  import Layout from './Layout.vue';
  import tgHelper from "../js/tg_helper.js";

  const props = defineProps(['shop_id', 'title', 'total']);
  const shop_id = props.shop_id;

  tgHelper.tg_init();
  if (tgHelper.is_tg) {
      tgHelper.tg_init_main_button('/mini/' + shop_id + '/order', 'Оплатить');
      tgHelper.tg_back_button_hide();
  }
</script>

<template>
  <Layout :need_web_button=!tgHelper.is_tg :text="'Оплатить'" :link="`/mini/${props.shop_id}/order`" :title="title">
    <h1>Заказ</h1>
    <hr>
    <h2>Сумма заказа: {{props.total}} р.</h2>
  </Layout>
</template>

<style scoped>
</style>
