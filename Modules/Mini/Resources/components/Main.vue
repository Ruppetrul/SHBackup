<script setup>
import SearchPanel from "../components/main/SearchPanel.vue";
import TopFilterPanel from "../components/main/TopFilterPanel.vue";
import ItemsPanel from "../components/main/ItemsPanel.vue";
import PreloaderItemsPanel from "../components/main/PreloaderItemsPanel.vue";
import Layout from './Layout.vue';

import {onMounted, ref, watch} from "vue";
import { getItems } from '../assets/js/api/getItems.js';
import tgHelper from '../js/tg_helper.js';

const isLoading = ref(true);

const props = defineProps(['shop_id', 'title']);

const shop_id = props.shop_id;

tgHelper.tg_init();
if (tgHelper.is_tg) {
    tgHelper.tg_init_main_button('/mini/' + shop_id + '/carts', 'Корзина');
    tgHelper.tg_back_button_hide();
}

const items = ref([]);
let page = 0;
let has_more = true;

let search = '';
let order = '';

async function fetchItems(is_paginate = false) {
    isLoading.value = true;
    const params = new URLSearchParams(
        {
            only_data: 1,
            page: page,
            search: search,
            priority_filter: order
        }
    );

    getItems(params, is_paginate, items, setHasMore, shop_id).finally(
        () => {
            isLoading.value = false;
        }
    );
}

function setHasMore(value) {
    has_more = value;
}

function change_search_filter(search_filter) {
    page = 1;
    search = search_filter;
    fetchItems();
}

function change_order_filter(order_filter) {
    page = 1;
    order = order_filter;
    fetchItems();
}

async function paginate() {
    page += 1;
    if (has_more) {
        await fetchItems(true);
    }
}

const bottomOfPage = ref(false);
const handleScroll = () => {
    const scrollTop = document.documentElement.scrollTop;
    const scrollHeight = document.documentElement.scrollHeight;
    const clientHeight = document.documentElement.clientHeight;

    bottomOfPage.value = scrollTop + clientHeight >= scrollHeight - 50;
}


watch(bottomOfPage, (newValue) => {
    if (newValue) {
        props.paginate();
    }
});

onMounted(() => {
    window.addEventListener('scroll', handleScroll);

    const fetchDataIfNeeded = async () => {
        await paginate();
        // setTimeout(() => {
        //   if (!isScreenFilledWithCards() && props.has_more) {
        //     fetchDataIfNeeded();
        // }}, 1000);
        // const isScreenFilledWithCards = () => {
        //     const clientHeight = document.documentElement.clientHeight;
        //     const cardContainerHeight = document.getElementById('items_panel').offsetHeight;
        //     return cardContainerHeight >= clientHeight;
        // }
    };

    fetchDataIfNeeded();
});
</script>

<template>
  <Layout :need_web_button=!tgHelper.is_tg :text="'Корзина'" :link="`/mini/${props.shop_id}/cart`" :title="title">
      <SearchPanel :change_search_filter="change_search_filter"/>
      <TopFilterPanel :change_order_filter="change_order_filter"/>
      <template v-if="isLoading">
          <PreloaderItemsPanel />
      </template>
      <template v-else>
          <div id="page">
              <ItemsPanel :items="items" :paginate="paginate" :has_more="has_more" :shop_id="shop_id"/>
          </div>
      </template>
  </Layout>
</template>

<style scoped>
</style>
