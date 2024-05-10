<script setup>
  import { getItem } from '../assets/js/api/getItem.js';
  import { ref } from "vue";

  import Medias from "../components/detail/Medias.vue";
  import InfoPanel from "../components/detail/InfoPanel.vue";
  import Layout from './Layout.vue';
  import tgHelper from "../js/tg_helper.js";

  const props = defineProps(['shop_id', 'item_id']);

  const shop_id = props.shop_id;
  const item_id = props.item_id;

  const item = ref([]);
  const medias = ref([]);
  const isLoading = ref(true);

  tgHelper.tg_init();
  if (tgHelper.is_tg) {
      tgHelper.tg_init_main_button('/mini/' + shop_id + '/carts', 'Корзина');
      tgHelper.tg_back_button_hide();
  }

  (async () => {
    try {
      item.value = await getItem(item_id, shop_id);
      medias.value = item.value.medias;
      isLoading.value = false;
    } catch (error) {
      console.error(error);
    }
  })();
</script>

<template>
  <Layout :need_web_button=!tgHelper.is_tg>
    <div class="row">
      <div class="col-12 col-xl-6 medias_panel">
        <Medias :isLoading="isLoading" :medias="medias"/>
        <br>
      </div>
      <div class="col-12 col-xl-6 info_panel">
        <InfoPanel :isLoading="isLoading" :item="item" :shop_id="shop_id"/>
      </div>
    </div>
  </Layout>
</template>

<style scoped>
  .row {
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    min-height: 50vh;
  }

  .medias_panel {
    text-align: center;
  }

  @media (min-width: 768px) {
    .medias_panel {
      text-align: right;
    }

    .info_panel {
      text-align: left;
    }
  }
</style>
