import ApiService from "@/core/services/api.service";

// action types
export const CURRENCY_LIST = "currency/list";

// mutation types
export const SET_CURRENCY_LIST = "setCurrencyList";
export const SET_ERROR = "setError";
export const CURRENCY_SET_LOADING = "currencySetLoading";

const state = {
    errors: null,
    loading: false,
    currency: [],
};

const getters = {
    currencyList(state) {
        return state.currency;
    },
    currencyErrors(state) {
        return state.errors;
    },
    currencyIsLoaded(state) {
        return !state.loading;
    }
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_CURRENCY_LIST](state, currencyes) {
        currencyes.length > 0 ? state.currency = currencyes : [];
    },
    [CURRENCY_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};

const actions = {
    [CURRENCY_LIST]({commit}) {
        commit(CURRENCY_SET_LOADING)
        ApiService.get("currency")
            .then(({data}) => {
                commit(SET_CURRENCY_LIST, data);
            })
            .catch(({response}) => {
                commit(SET_ERROR, response.data.errors);
            });
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};
