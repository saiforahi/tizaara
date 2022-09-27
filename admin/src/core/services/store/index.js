import Vue from 'vue'
import Vuex from 'vuex'
import advertisement from './module/advertisement';
import auth from './auth.module';
import general from './general.module';
import user from './user.module';
import brand from './module/brand';
import category from './module/category';
import subcategory from './module/subcategory';
import subsubcategory from './module/subsubcategory';
import property from './module/property';
import unit from './module/unit';
import color from './module/color';
import attribute from './module/attribute';
import currency from './module/currency';
import homeslider from "@/core/services/store/module/homeslider";
import quotation from "@/core/services/store/module/quotation";
import page_manage from "@/core/services/store/module/page_manage";
import help from "@/core/services/store/module/help";
import flash_deals from "@/core/services/store/module/flash_deals";
import newsletter from "@/core/services/store/module/newsletter";
import testimonial from "@/core/services/store/module/testimonial";
import seller from "@/core/services/store/module/seller";
import customer from "@/core/services/store/module/customer";
import dashboard from "./module/dashboard";
import subscriber from "./module/subscriber";
import order from "./module/order";
import ecommerce_requests from "./module/ecommerce_requests"

Vue.use(Vuex)

const state = {
    dialog: false,
    isLoading: false,
    sidebarShow: 'responsive',
    sidebarMinimize: false
}

const getters = {
    dialog: state => state.dialog,
}

const mutations = {
    toggleSidebarDesktop(state) {
        const sidebarOpened = [true, 'responsive'].includes(state.sidebarShow)
        state.sidebarShow = sidebarOpened ? false : 'responsive'
    },
    toggleSidebarMobile(state) {
        const sidebarClosed = [false, 'responsive'].includes(state.sidebarShow)
        state.sidebarShow = sidebarClosed ? true : 'responsive'
    },
    set(state, [variable, value]) {
        state[variable] = value
    },
    modalStatus: (state, dialog) => state.dialog = dialog,
}

const actions = {
    setModalStatus: ({commit}, dialog) => commit('modalStatus', dialog),
}

export default new Vuex.Store({
    state,
    mutations,
    getters,
    actions,
    modules: {
        advertisement,
        auth,
        general,
        user,
        brand,
        category,
        subcategory,
        subsubcategory,
        property,
        unit,
        color,
        attribute,
        currency,
        homeslider,
        quotation,
        page_manage,
        help,
        flash_deals,
        newsletter,
        testimonial,
        seller,
        customer,
        dashboard,
        subscriber,order,ecommerce_requests
    }
})
