import { createApp } from 'vue';

import { createRouter, createWebHistory } from 'vue-router'
import MiniApp from '../../components/MiniApp.vue';
import Main from '../../components/Main.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/mini/:shop_id', component: Main }
    ]
})

router.beforeResolve((to, from, next) => {
    if (to.path === '/mini/:shop_id/detail' && !to.params.item_id) {
        next('/')
    } else {
        next()
    }
})

createApp(MiniApp).use(router).mount('#app')
