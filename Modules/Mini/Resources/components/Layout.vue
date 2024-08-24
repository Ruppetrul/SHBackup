<script setup>
    import { Link, Head } from '@inertiajs/vue3'

    const props = defineProps(['need_web_button', 'text', 'link', 'title', 'shop_id']);

    function redirectToOrderPage() {
        console.log(props.text);
        if (props.text === 'Оплатить') {
            event.preventDefault()
            window.location.href = '/mini/' + props.shop_id + '/create-order'
        }
    }
</script>

<template>
    <Head :title="title ? `${title}` : 'Simply shop'"></Head>
    <div class="container" :style="{ 'padding-bottom': props.need_web_button ? '3em' : '0' }">
        <div id="test-container">
            <H2>{{props.title}}</H2>
            <br>
            <slot></slot>
        </div>
    </div>
    <Link :href="props.link">
        <button id="bottom-button" v-show="props.need_web_button === true" @click="redirectToOrderPage">{{ props.text }}</button>
    </Link>
</template>

<style>
#test-container {
    padding-top: 30px;
}

#bottom-button {
    background-color: #f0f8ff;
    height: 3em;
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
}
</style>
