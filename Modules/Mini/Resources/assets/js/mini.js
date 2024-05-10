import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
    id: 'app',
    resolve: name => {
        console.log('pages');
        const pages = import.meta.glob('./../../components/*.vue', { eager: true })
        return pages[`../../components/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        console.log('pages');
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})
