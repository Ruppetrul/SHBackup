<script setup>
  import { ref } from "vue";
  import ItemPreloader from "../components/cart/ItemPreloader.vue";
  import Layout from './Layout.vue';
  const isLoading = ref(true);
  import tgHelper from "../js/tg_helper.js";

  tgHelper.tg_init();
  if (tgHelper.is_tg) {
      tgHelper.tg_init_main_button('/mini/' + shop_id + '/order', 'Оформить');
      tgHelper.tg_back_button_hide();
  }

  const props = defineProps(['shop_id']);
  const shop_id = props.shop_id;
</script>

<template>
  <Layout :need_web_button=!tgHelper.is_tg :text="'Оформить'" :link="`/mini/${shop_id}/order`">
    <h1>Корзина</h1>
    <hr>
    <template v-if="isLoading">
      <div class="placeholder_image">
        <ItemPreloader v-for="index in 10" :key="index" />
      </div>
    </template>
    <template v-else>

    </template>
  </Layout>
</template>

<style scoped>
</style>
