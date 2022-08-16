import 'core-js/stable'
import Vue from 'vue'
import App from './App'
import router from './router'
import CoreuiVue from '@coreui/vue'
import {iconsSet as icons} from './assets/icons/icons.js'
import store from './core/services/store'
import ApiService from "./core/services/api.service";
import {VERIFY_AUTH} from "./core/services/store/auth.module";
import message from "@/common/message.json";


window.$ = window.jQuery = require('jquery');

Vue.config.performance = true
Vue.use(CoreuiVue)
Vue.prototype.$log = console.log.bind(console)

ApiService.init();

// Vue 3rd party plugins
import "./core/plugins/bootstrap-vue";

import VueLazyload from 'vue-lazyload'

Vue.use(VueLazyload, {
    preLoad: 1.3,
    loading: 'https://i.ibb.co/jG5PXnD/spinner.gif',
    attempt: 1
})

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
// npm install vue2-perfect-scrollbar
============================================= */
import PerfectScrollbar from 'vue2-perfect-scrollbar'
import 'vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css'

Vue.use(PerfectScrollbar)

/*============================================
// npm install --save @ckeditor/ckeditor5-vue @ckeditor/ckeditor5-build-classic
============================================= */

import CKEditor from '@ckeditor/ckeditor5-vue';

Vue.use(CKEditor);

/*============================================
// npm i --save @fortawesome/fontawesome-svg-core
// npm i --save @fortawesome/free-solid-svg-icons
// npm i --save @fortawesome/vue-fontawesome
============================================= */

import {library} from '@fortawesome/fontawesome-svg-core'
import {faBars, faCheck, faBan} from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

library.add(faBars, faCheck, faBan)
Vue.component('font-awesome-icon', FontAwesomeIcon)

/*============================================
// npm i axios vform
============================================= */
import Embed from 'v-video-embed'

Vue.use(Embed);

/*============================================
// npm i axios vform
============================================= */
import {Form, HasError, AlertError} from 'vform';

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
window.Form = Form;

/*============================================
// npm install sweetalert2
============================================= */
import swal from 'sweetalert2'

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
window.toast = toast;
window.swal = swal;

/*============================================
// npm i vue-element-loading
============================================= */
import VueElementLoading from 'vue-element-loading'

/*============================================
// npm install vue-form-wizard
============================================= */
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'

Vue.use(VueFormWizard)

/*============================================
// npm install @johmun/vue-tags-input
============================================= */
import VueTagsInput from '@johmun/vue-tags-input';

Vue.component('VueTagsInput', VueTagsInput);

/*============================================
// npm install vue select
============================================= */
import vSelect from 'vue-select'

Vue.component('v-select', vSelect);

/*============================================
// npm install laravel-vue-pagination
============================================= */
Vue.component('pagination', require('laravel-vue-pagination'));

Vue.component('vue-element-loading', VueElementLoading);

import {ClientTable} from 'vue-tables-2';

Vue.use(ClientTable, {}, false, 'bootstrap4', {});

/*============================================
// npm install vue-data-tables
============================================= */
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

Vue.use(ElementUI)
import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'

locale.use(lang)
import VueDataTables from 'vue-data-tables'

Vue.use(VueDataTables)

/*============================================
// npm install vue-moment
============================================= */
Vue.use(require('vue-moment'));

/*============================================
// npm install vue2-editor
============================================= */
import Vue2Editor from "vue2-editor";

Vue.use(Vue2Editor);

router.beforeEach((to, from, next) => {
    Promise.all([store.dispatch(VERIFY_AUTH)]).then(next);
    setTimeout(() => {
        window.scrollTo(0, 0);
    }, 100);
    document.title = to.meta.title;
});

new Vue({
    router,
    store,
    icons,
    i18n,
    render: h => h(App)
}).$mount("#app");
