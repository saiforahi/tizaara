import Vue from 'vue'
import App from './App.vue'
import router from './router'
import {iconsSet as icons} from './assets/icons/icons.js'
import store from './core/services/store'
import ApiService from "./core/services/api.service";
import {VERIFY_AUTH} from "./core/services/store/auth.module";
import {GENERAL_LIST} from "@/core/services/store/general.module";
import message from "@/common/message.json";
import {initFacebookSdk} from "@/core/config/facebook_oAuth";

// Vue 3rd party plugins
import "./core/plugins/bootstrap";

ApiService.init();

initFacebookSdk();

/*============================================
// npm install vue-i18n
============================================= */
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

const i18n = new VueI18n({
    locale: 'en',
    messages: {
        en: {
            message
        }
    }
})

/*============================================
// npm install vue-infinite-loading
============================================= */
import InfiniteLoading from 'vue-infinite-loading'

Vue.use(InfiniteLoading)

/*============================================
// npm install vue-page-title
============================================= */
import VuePageTitle from 'vue-page-title'

Vue.use(VuePageTitle)

/*============================================
// fontawesome-svg-core
============================================= */
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.config.productionTip = false

router.beforeEach((to, from, next) => {
    Promise.all([store.dispatch(VERIFY_AUTH)]).then(next);
    document.title = to.meta.title;
});

window.Fire = new Vue();

new Vue({
    router,
    store,
    icons,
    i18n,
    render: h => h(App),
    created() {
        store.dispatch(GENERAL_LIST)
    },
}).$mount('#app')
