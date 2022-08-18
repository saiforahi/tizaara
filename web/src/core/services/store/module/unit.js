import ApiService from "@/core/services/api.service";

// action types
export const UNIT_LIST = "unit/list";

// mutation types
export const SET_UNIT_LIST = "setUnitList";
export const SET_ERROR = "setError";
export const UNIT_SET_LOADING = "unitSetLoading";

const state = {
    errors: null,
    loading: false,
    unit: [],
};

const getters = {
    unitList(state) {
        return state.unit;
    },
    unitErrors(state) {
        return state.errors;
    },
    unitIsLoaded(state) {
        return !state.loading;
    }
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_UNIT_LIST](state, units) {
        units.length > 0 ? state.unit = units : [];
    },
    [UNIT_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};

const actions = {
    [UNIT_LIST]({commit}) {
        commit(UNIT_SET_LOADING)
        ApiService.get("unit")
            .then(({data}) => {
                commit(SET_UNIT_LIST, data);
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
