<script setup>
</script>

<template>
  <div class="item_cart_container">
    <div class="item_cart">
      <div class="preloader_image col-2">
      </div>
      <div class="preloader_name">
      </div>
      <div class="preloader_price">
      </div>
      <div class="preloader_control_panel">
      </div>
    </div>
  </div>
</template>

<style scoped>
  .item_cart_container {
    padding: 5px;
  }

  .preloader_image, .preloader_name, .preloader_price , .preloader_control_panel {
      background-color: #f0f0f0;
      height: 1em;
      animation: pulse 0.5s infinite alternate;
      border-radius: 5px;
  }

  .preloader_image {
      width: 100%;
      height: 15em;
      min-width: 4em;
  }

  .preloader_name {
      width: 50%;
      min-height: 1em;
      margin-top: 1em;
  }

  .preloader_price {
      width: 25%;
      min-height: 1em;
      margin-top: 1em;
  }

  .preloader_control_panel {
      width: 100%;
      min-height: 2em;
      margin-top: 1em;
  }
</style>
