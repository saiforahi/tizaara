import ApiService from "@/core/services/api.service";

// action types
export const PRODUCT = "get/single/product";
export const GET_PRODUCT_LISTS = "product/listing";
export const GET_CONTACT_SUPPLIER_PRODUCT = "contact/supplier/product";

// mutation types
export const SET_PRODUCT = "set/basic/SingleProduct";
export const SET_PRODUCT_LISTS = "setSingleProduct";
export const SET_CONTACT_SUPPLIER_PRODUCT = "set/contact/supplier/product";

const state = {
    m_product: {},
    product_lists:[],
    product_brands:[],//products exists unique brands store here
    product_colors:[],//products exists unique colors store here
    contact_product:{},//for contact supplier model product
};

const getters = {
    m_product(state) {
        return state.m_product;
    },
    product_lists(state) {
        return state.product_lists;
    },

    product_brands(state) {
        return state.product_brands;
    },
    product_colors(state) {
        return state.product_colors;
    },
    contact_product(state) {
        return state.contact_product;
    },
};

const mutations = {
    [SET_PRODUCT](state, data) {
        state.m_product = data;
    },

    [SET_PRODUCT_LISTS](state,data) {
        state.product_lists = data.products;
        state.product_brands = data.brands;
        state.product_colors = data.colors;
    },
    [SET_CONTACT_SUPPLIER_PRODUCT](state,product) {
        state.contact_product = product;
    },

};

const actions = {
    [PRODUCT]({commit},id) {
        ApiService.get(`product/basic/information${id}`)
            .then(({data}) => {
                commit(SET_PRODUCT, data);
            });
    },
    [GET_PRODUCT_LISTS]({commit},url) {
        ApiService.get(url)
            .then(({data}) => {
                commit(SET_PRODUCT_LISTS, data);
            });
    },
    [GET_CONTACT_SUPPLIER_PRODUCT]({commit}, product) {
        commit(SET_CONTACT_SUPPLIER_PRODUCT, product);
    },
    
};

export default {
    state,
    actions,
    mutations,
    getters
};