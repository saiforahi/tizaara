import ApiService from "@/core/services/api.service";

// action types
export const COLOR_LIST = "color/list";

// mutation types
export const SET_COLOR_LIST = "setColorList";
export const SET_ERROR = "setError";
export const COLOR_SET_LOADING = "colorSetLoading";

const state = {
    errors: null,
    loading: false,
    color: [],
};

const getters = {
    colorList(state) {
        return state.color;
    },
    colorErrors(state) {
        return state.errors;
    },
    colorIsLoaded(state) {
        return !state.loading;
    }
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_COLOR_LIST](state, colors) {
        colors.length > 0 ? state.color = colors : [];
    },
    [COLOR_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};

const actions = {
    [COLOR_LIST]({commit}) {
        commit(COLOR_SET_LOADING)
        ApiService.get("color")
            .then(({data}) => {
                commit(SET_COLOR_LIST, data);
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
