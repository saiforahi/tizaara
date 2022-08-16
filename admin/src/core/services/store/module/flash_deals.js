import ApiService from "@/core/services/api.service";

// action types
export const FLASH_DEALS_LIST = "flash_deals/list";
export const REQUEST_FLASH_DEAL = "request_flash_deals/list";
export const PRODUCT_REQUEST_FLASH_DEAL = "request_flash_deals/entry";

// mutation types
export const SET_FLASH_DEALS = "setFlashDeals";
export const SET_REQUEST_FLASH_DEALS = "setRequestFlashDeals";
export const FLASH_DEALS_REMOVE = "deleteFlashDeals";
export const REQUEST_FLASH_DEALS_REMOVE = "deleteRequestFlashDeals";
export const FLASH_DEALS_ADD = "addNewFlashDeals";

const state = {
    flash_deals: [],
    request_flash_deals: [],
};


const getters = {
    flash_dealList: state => state.flash_deals,
    requestFlashDealList: state => state.request_flash_deals
};

const mutations = {
    [SET_FLASH_DEALS](state, data) {
        data.length > 0 ? state.flash_deals = data : '';
    },
    [SET_REQUEST_FLASH_DEALS](state, data) {
        data.length > 0 ? state.request_flash_deals = data : '';
    },
    [FLASH_DEALS_REMOVE](state, dataId) {
        let index = state.flash_deals.findIndex(value => value.id === dataId);
        state.flash_deals.splice(index, 1);
    },
    [REQUEST_FLASH_DEALS_REMOVE](state, dataId) {
        let index = state.request_flash_deals.findIndex(value => value.id === dataId);
        state.flash_deals.splice(index, 1);
    },
    [FLASH_DEALS_ADD](state, data) {
        state.flash_deals.unshift(data);
    },
};

const actions = {
    [FLASH_DEALS_LIST]({commit}) {
        ApiService.get("flash-deals")
            .then(({data}) => {
                commit(SET_FLASH_DEALS, data);
            })
            .catch(({response}) => {
                console.log(response);
            });
    },

    [REQUEST_FLASH_DEAL](context, credentials) {
        return new Promise(resolve => {
            ApiService.get("request-flash-deals", credentials)
                .then(({data}) => {
                    context.commit(SET_REQUEST_FLASH_DEALS, data);
                    resolve(data);
                })
                .catch(({response}) => {
                    console.log(response);
                });
        });
    },

    [PRODUCT_REQUEST_FLASH_DEAL](context, data) {
        return new Promise(resolve => {
            ApiService.post("product_flash_request_input", data)
                .then(({data}) => {
                    context.commit(REQUEST_FLASH_DEALS_REMOVE, data);
                    resolve(data);
                })
                .catch(({response}) => {
                    console.log(response);
                });
        });
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};