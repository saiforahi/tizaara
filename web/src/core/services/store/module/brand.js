import ApiService from "@/core/services/api.service";

// action types
export const BRAND_LIST = "brand/list";

// mutation types
export const SET_BRAND_LIST = "setBrandList";
export const SET_ERROR = "setError";
export const BRAND_SET_LOADING = "brandSetLoading";

const state = {
    errors: null,
    loading: false,
    brands: [],
};

const getters = {
    brandList(state) {
        return state.brands;
    },
    brandErrors(state) {
        return state.errors;
    },
    brandIsLoaded(state) {
        return !state.loading;
    },

    getBrandListing(state) {
        let data = [];
        for (let i = 0; i < state.brands.length; i++) {
            if (state.brands[i].serial != null) {
                data.push(state.brands[i]);
            }
        }
        return data;
    }
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_BRAND_LIST](state, brands) {
        brands.length > 0 ? state.brands = brands : '';
    },
    [BRAND_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};

const actions = {
    [BRAND_LIST]({commit}) {
        commit(BRAND_SET_LOADING)
        ApiService.get("brand")
            .then(({data}) => {
                commit(SET_BRAND_LIST, data);
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
