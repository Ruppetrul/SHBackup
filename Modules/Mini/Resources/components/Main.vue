<script setup>
import SearchPanel from "../components/main/SearchPanel.vue";
import TopFilterPanel from "../components/main/TopFilterPanel.vue";
import ItemsPanel from "../components/main/ItemsPanel.vue";
import PreloaderItemsPanel from "../components/main/PreloaderItemsPanel.vue";
import Layout from './Layout.vue';

import {onMounted, ref, watch} from "vue";
import { getItems } from '../assets/js/api/getItems.js';
import tgHelper from '../js/tg_helper.js';
import Categories from "./main/Categories.vue";
import BottomPanel from "./main/BottomPanel.vue";

const isLoading = ref(false);
const isEmpty = ref(true);

const props = defineProps(['shop_id', 'title', 'message', 'categories']);

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
let category = '';

async function fetchItems(is_paginate = false) {
    const params = new URLSearchParams(
        {
            only_data: 1,
            page: page,
            search: search,
            category: category,
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

function select_category_filter(category_filter) {
    page = 0;
    category = category_filter ?? null;
    setHasMore(true);
    items.value = [];
    fetchData();
}

async function paginate() {
    page += 1;
    return fetchItems(true);
}

const bottomOfPage = ref(false);

watch(bottomOfPage, (newValue) => {
    if (newValue && has_more) {
        fetchData();
    }
});

const handleScroll = () => {
    const scrollTop = document.documentElement.scrollTop;
    const scrollHeight = document.documentElement.scrollHeight;
    const clientHeight = document.documentElement.clientHeight;

    bottomOfPage.value = scrollTop + clientHeight >= scrollHeight - 50;
}

const fetchData = async () => {
    if (isLoading.value === false) {
        isEmpty.value = false;
        isLoading.value = true;

        const preloaders = Array.from({ length: 5 }, () => ({ isPreloader: true }));

        items.value = items.value.concat(preloaders);

        const response = await paginate();

        has_more = response.data.has_more;
        setHasMore(has_more);
        if (!has_more) {
            window.removeEventListener('scroll', handleScroll);
        }

        items.value.splice(-5, 5);

        items.value = items.value.concat(response.data.products.data)
        isLoading.value = false;

        // If screen too large we need fill full screen
        const scrollEvent = new Event('scroll');
        window.dispatchEvent(scrollEvent);
    }
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    fetchData();
});

document.addEventListener("DOMContentLoaded", function() {
    var notification = document.getElementById('notification');
    notification.style.display = 'block';
    document.body.style.paddingTop = notification.offsetHeight + 'px';
    setTimeout(function() {
        notification.style.display = 'none';
        document.body.style.paddingTop = '0';
    }, 3000);
});
</script>

<template>
    <div v-if="props.message" id="notification" class="alert alert-success position-fixed top-0 start-50 translate-middle-x w-75"
         role="alert">
        {{ props.message }}
    </div>
    <Layout :need_web_button=!tgHelper.is_tg :text="'Корзина'" :link="`/mini/${props.shop_id}/cart`" :title="title"
            :shop_id="props.shop_id">
        <SearchPanel :change_search_filter="change_search_filter"/>
        <TopFilterPanel :change_order_filter="change_order_filter"/>
        <Categories :categories="props.categories" :clickHandler="select_category_filter"></Categories>
        <template v-if="isEmpty">
      <PreloaderItemsPanel />
    </template>
    <template v-else>
      <div id="page">
        <ItemsPanel :items="items" :has_more="has_more" :shop_id="shop_id"/>
      </div>
    </template>
    <hr>
    <BottomPanel :shop_id="shop_id"/>
  </Layout>
</template>

<style scoped>
</style>
