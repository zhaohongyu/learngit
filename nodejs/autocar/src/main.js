import Vue from 'vue'
import VueRouter from 'vue-router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import store from './store/index.js'
import routes from './routes'
import App from './App.vue'
import settings from './conf/settings.js'

Vue.use(ElementUI);
Vue.use(VueRouter);

window.bus   = new Vue();
bus.settings = settings;

const router = new VueRouter({mode: 'history', routes: routes});

new Vue({
    el    : '#app',
    store : store,
    router: router,
    render: h => h(App)
});
