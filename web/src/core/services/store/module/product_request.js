import ApiService from "@/core/services/api.service";

// action types
export const PRODUCT_REQUEST = "productRequest";
export const PRODUCT_REQUEST_ECOMMERCE = "productRequestEcommerceList";
export const PRODUCT_REQUEST_FLASH_DEAL = "productRequestFlashDealList";

// mutation types
export const SET_ECOMMERCE_LIST = "setEcommerceList";
export const SET_FLASH_DEAL_LIST = "setFlashDealList";

const state = {
    ecommerce_list: [],
    flash_deal_list: [],
};

const getters = {
    getEcommerceList: state => state.ecommerce_list,
    getFlashDealList: state => state.flash_deal_list,
};

const mutations = {
    [SET_ECOMMERCE_LIST](state, data) {
        data.length > 0 ? state.ecommerce_list = data : '';
    },

    [SET_FLASH_DEAL_LIST](state, data) {
        data.length > 0 ? state.flash_deal_list = data : '';
    },
};

const actions = {
    [PRODUCT_REQUEST](context, data) {
        return new Promise(resolve => {
            ApiService.post("user-product-request", data)
                .then(({data}) => {
                    resolve(data);
                })
                .catch(({response}) => {
                    console.log(response);
                });
        });
    },
    [PRODUCT_REQUEST_ECOMMERCE]({commit}) {
        ApiService.get("user-product-ecommerce-list")
            .then(({data}) => {
                commit(SET_ECOMMERCE_LIST, data);
            })
            .catch(({response}) => {
                console.log(response);
            });
    },

    [PRODUCT_REQUEST_FLASH_DEAL]({commit}) {
        ApiService.get("user-product-flash-list")
            .then(({data}) => {
                commit(SET_FLASH_DEAL_LIST, data);
            })
            .catch(({response}) => {
                console.log(response);
            });
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};