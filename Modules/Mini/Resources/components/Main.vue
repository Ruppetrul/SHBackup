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
    const params = new URLSearchParams(
        {
            only_data: 1,
            page: page,
            search: search,
            priority_filter: order
        }
    );

    return getItems(params, is_paginate, items, setHasMore, shop_id);
}

function setHasMore(value) {
    has_more = value;
}

function change_search_filter(search_filter) {
    page = 0;
    search = search_filter;
    setHasMore(true);
    items.value = [];
    fetchData();
}

function change_order_filter(order_filter) {
    page = 0;
    order = order_filter;
    setHasMore(true);
    items.value = [];
    fetchData();
}

async function paginate() {
    page += 1;
    return fetchItems(true);
}

const fetchData = async () => {
    if (page === 0) {
        isLoading.value = true;
    }

    const response = await paginate();

    isLoading.value = false;

    has_more = response.data.has_more;
    setHasMore(has_more);
    if (!has_more) {
        window.removeEventListener('scroll', handleScroll);
    }

    items.value = items.value.concat(response.data.products.data);

    // If screen too large we need fill full screen
    const scrollEvent = new Event('scroll');
    window.dispatchEvent(scrollEvent);
}

onMounted(() => {
    const bottomOfPage = ref(false);
    const handleScroll = () => {
        const scrollTop = document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight;
        const clientHeight = document.documentElement.clientHeight;

        bottomOfPage.value = scrollTop + clientHeight >= scrollHeight - 50;
    }

    watch(bottomOfPage, (newValue) => {
        if (newValue && has_more) {
            fetchData();
        }
    });

    window.addEventListener('scroll', handleScroll);
    fetchData();
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
              <ItemsPanel :items="items" :has_more="has_more" :shop_id="shop_id"/>
          </div>
      </template>
  </Layout>
</template>

<style scoped>
</style>
