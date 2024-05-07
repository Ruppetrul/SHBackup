<script>
export default {
    name: 'App'
}
</script>

<template>
    <div class="container">
        <div class="container-fluid-lg" id="test-container">
            <router-view></router-view>
        </div>
    </div>
</template>

<style>
#test-container {
    padding-top: 30px;
}
</style>
