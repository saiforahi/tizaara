import ApiService from "@/core/services/api.service";

// action types
export const GENERAL_LIST = "general/list";

// mutation types
export const SET_ERROR = "setError";
export const SET_GENERAL_LIST = "setGeneralList";
export const GENERAL_SET_LOADING = "generalSetLoading";

const state = {
    errors: null,
    loading: false,
    generals: {},
};

const getters = {
    generalList: (state) => state.generals,
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_GENERAL_LIST](state, brands) {
        state.generals = brands;
    },
    [GENERAL_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
}

const actions = {
    [GENERAL_LIST]({commit, dispatch}) {
        commit(GENERAL_SET_LOADING)
        ApiService.setHeader();
        ApiService.get("general-settings")
            .then(({data}) => {
                commit(SET_GENERAL_LIST, data);
            })
            .catch(({response}) => {
                commit(SET_ERROR, response.data.errors);
            });
    },
}

export default {
    state,
    actions,
    mutations,
    getters
};
