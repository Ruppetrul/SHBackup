<script setup>
  import { ref } from "vue";
  import ItemPreloader from "../components/cart/ItemPreloader.vue";
  import Layout from './Layout.vue';
  import Item from './cart/Item.vue';
  const isLoading = ref(true);
  import tgHelper from "../js/tg_helper.js";
  import { getCart } from '../assets/js/api/getCart.js';

  tgHelper.tg_init();
  if (tgHelper.is_tg) {
      tgHelper.tg_init_main_button('/mini/' + shop_id + '/order', 'Оформить');
      tgHelper.tg_back_button_hide();
  }

  const props = defineProps(['shop_id', 'title']);
  const shop_id = props.shop_id;
  const items = ref([]);

  getCart(shop_id, items)
      .finally(() => {
          isLoading.value = false;
      });

  const deleteItem = (index) => {
      items.value.splice(index, 1);
  };

  const preloadItem = (index) => {
      items.value.push()
  }
</script>

<template>
  <Layout :need_web_button=!tgHelper.is_tg :text="'Оформить'" :link="`/mini/${shop_id}/order`" :title="title" :shop_id="shop_id">
    <h1>Корзина</h1>
    <hr>
    <template v-if="isLoading">
      <div class="placeholder_image">
        <ItemPreloader v-for="index in 5" :key="index" />
      </div>
    </template>
    <template v-else>
      <div>
        <Item v-for="(item, index) in items" :item="item" :shop_id="shop_id"  @delete-item="deleteItem(index)"/>
      </div>
    </template>
  </Layout>
</template>

<style scoped>
</style>
