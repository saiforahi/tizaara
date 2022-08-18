import Vue from "vue";
import {BootstrapVue, IconsPlugin} from "bootstrap-vue";

window._ = require('lodash');
try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {
}

// Install BootstrapVue
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);

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

//import GoogleAuth
import GoogleAuth from '@/core/config/google_oAuth'
const gauthOption = {
    clientId: google_client_id,
    scope: 'profile email',
    prompt: 'select_account'
}
Vue.use(GoogleAuth, gauthOption)

import VueLazyload from 'vue-lazyload'
const loadimage = require('@/assets/image/spinner.gif')
const errorimage = require('@/assets/image/error_image.png')
Vue.use(VueLazyload, {
    preLoad: 1.3,
    error: errorimage,
    loading: loadimage,
    attempt: 1
})

/*============================================
// npm install vue-form-wizard
============================================= */
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'

Vue.use(VueFormWizard)

/*============================================
// npm install vue select
============================================= */
import vSelect from 'vue-select'

Vue.component('v-select', vSelect);

/*============================================
// npm install v-video-embed
============================================= */
import Embed from 'v-video-embed'

Vue.use(Embed);

/*============================================
// npm install @johmun/vue-tags-input
============================================= */
import VueTagsInput from '@johmun/vue-tags-input';

Vue.component('VueTagsInput', VueTagsInput);

/*============================================
// npm install --save @ckeditor/ckeditor5-vue @ckeditor/ckeditor5-build-classic
============================================= */

import CKEditor from '@ckeditor/ckeditor5-vue';

Vue.use(CKEditor);

/*============================================
// npm install @mathieustan/vue-datepicker --save
============================================= */
import VueDatePicker from '@mathieustan/vue-datepicker';
import '@mathieustan/vue-datepicker/dist/vue-datepicker.min.css';

Vue.use(VueDatePicker);

/*============================================
// npm install --save vue-scrollto
============================================= */
const VueScrollTo = require('vue-scrollto')
Vue.use(VueScrollTo)

/*============================================
// npm install vue-suggestion
============================================= */
import VueSuggestion from 'vue-suggestion'

Vue.use(VueSuggestion)

/*============================================
// npm install vue-infinite-scroll --save
============================================= */
import infiniteScroll from 'vue-infinite-scroll'
import {google_client_id} from "@/core/config/app";
Vue.use(infiniteScroll)




















