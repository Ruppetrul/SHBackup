<script setup>
    const props = defineProps(['need_web_button', 'text']);
</script>

<template>
    <div class="container" :style="{ 'padding-bottom': props.need_web_button ? '3em' : '0' }">
        <div id="test-container">
            <slot></slot>
        </div>
    </div>
    <button id="bottom-button" v-show="props.need_web_button === true">{{ props.text }}</button>
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
