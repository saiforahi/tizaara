import ApiService from "@/core/services/api.service";

// action types
export const FLASH_DEALS_LIST = "flash_deals/list";

// mutation types
export const SET_FLASH_DEALS = "setFlashDeals";
export const FLASH_DEALS_REMOVE = "deleteFlashDeals";
export const FLASH_DEALS_ADD = "addNewFlashDeals";

const state = {
    flash_deals: [],
};


const getters = {
    flash_dealList: state => state.flash_deals,

    getFlashDealBySlug: state => slug => state.flash_deals.find(value => value.slug === slug),
};

const mutations = {
    [SET_FLASH_DEALS](state, data) {
        data.length > 0 ? state.flash_deals = data : '';
    },
    [FLASH_DEALS_REMOVE](state, dataId) {
        let index = state.flash_deals.findIndex(value => value.id === dataId);
        state.flash_deals.splice(index, 1);
    },
    [FLASH_DEALS_ADD](state, data) {
        state.flash_deals.unshift(data);
    },
};

const actions = {
    [FLASH_DEALS_LIST]({commit}) {
        ApiService.get("flash-deals-list")
            .then(({data}) => {
                commit(SET_FLASH_DEALS, data);
            })
            .catch(({response}) => {
                console.log(response);
            });
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};