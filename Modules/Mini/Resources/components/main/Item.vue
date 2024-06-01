<script setup>
  import { defineProps, watch } from 'vue';
  import { updateCount } from '../../assets/js/api/updateCount.js';
  import { Link } from '@inertiajs/vue3'

  const props = defineProps(['itemData', 'shop_id'])

  let timer = null;

  const decreaseCount = () => {
    props.itemData.quantity_in_cart--;
  };

  const increaseCount = () => {
    props.itemData.quantity_in_cart++;
  };

  watch(() => props.itemData.quantity_in_cart, (newValue) => {
    clearTimeout(timer);
    timer = setTimeout(() => {
       updateCount(props.itemData.id, newValue, props.shop_id);
    }, 300);
  });
</script>

<template>
  <div class="item_cart_container">
    <div class="item_cart">
      <div class="item_avatar">
        <Link :href="`/mini/${props.shop_id}/detail/${props.itemData.id}`">
          <img :src="props.itemData.avatar_url" width="200" height="200" alt="Дефолтная картинка" loading="lazy">
        </Link>
      </div>
      <div class="item_detail">
          <Link :href="`/mini/${props.shop_id}/detail/${props.itemData.id}`">{{ props.itemData.title }}</Link>
        <h5>{{ props.itemData.price }}</h5>
      </div>
      <div class="item_footer">
        <template v-if="props.itemData.quantity_in_cart">
          <div class="item_quantity_control_panel">
            <button class="item_count_button" @click="decreaseCount">-</button>
            <span class="item_count" style="width: 40%;">{{ props.itemData.quantity_in_cart }}</span>
            <button class="item_count_button" @click="increaseCount">+</button>
          </div>
        </template>
        <template v-else>
          <div class="item_quantity_default_panel">
            <button class="item_count_button" @click="increaseCount">Добавить в корзину</button>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<style scoped>
  .item_cart_container {
    padding: 5px;
  }

  .item_cart {
    background-color: white;
    height: 100%;
    border-radius: 7%;
    display: flex;
    flex-direction: column;
  }

  .item_avatar {
    text-align: center;
  }

  .item_avatar img {
    width: 100%;
    height: auto;
    border-radius: 7%;
  }

  .item_footer {
    text-align: center;
    margin-top: 2px;
  }

  .item_detail {
    margin-top: auto;
  }

  .item_footer button {
    border: none;
    border-radius: 6px;
    background-color: aliceblue;
    width: 100%;
  }

  .item_quantity_control_panel {
    display: flex;
    justify-content: space-between;
  }

  .item_quantity_control_panel button {
    width: 30%;
  }

  .item_count_button {
      padding: 1em;
  }

  .item_count {
      align-content: center;
  }
</style>
