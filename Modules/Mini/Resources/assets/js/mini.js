import { createApp } from 'vue';

import { createRouter, createWebHistory } from 'vue-router'
import MiniApp from '../../components/MiniApp.vue';
import Main from '../../components/Main.vue';
import Detail from '../../components/Detail.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/mini/:shop_id', component: Main },
        { path: '/mini/:shop_id/detail/:item_id', component: Detail, props: true, meta: { requiresItemId: true }},
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
