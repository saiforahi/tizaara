import ApiService from "@/core/services/api.service";

// action types
export const CURRENCY_LIST = "currency/list";

// mutation types
export const SET_CURRENCY_LIST = "setCurrencyList";

const state = {
    currency: [],
};

const getters = {
    currencyList(state) {
        return state.currency;
    }
};

const mutations = {
    [SET_CURRENCY_LIST](state, currencyes) {
        currencyes.length > 0 ? state.currency = currencyes : [];
    }
};

const actions = {
    [CURRENCY_LIST]({commit}) {
        ApiService.get("currency")
            .then(({data}) => {
                commit(SET_CURRENCY_LIST, data);
            })
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};
