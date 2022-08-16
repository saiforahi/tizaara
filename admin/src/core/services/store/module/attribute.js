import ApiService from "@/core/services/api.service";

// action types
export const ATTRIBUTE_LIST = "attribute/list";

// mutation types
export const SET_ATTRIBUTE_LIST = "setAttributeList";
export const SET_ERROR = "setError";
export const ATTRIBUTE_SET_LOADING = "attributeSetLoading";

const state = {
    errors: null,
    loading: false,
    attribute: [],
};

const getters = {
    attributeList(state) {
        return state.attribute;
    },
    attributeErrors(state) {
        return state.errors;
    },
    attributeIsLoaded(state) {
        return !state.loading;
    }
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_ATTRIBUTE_LIST](state, attributes) {
        attributes.length > 0 ? state.attribute = attributes : [];
    },
    [ATTRIBUTE_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};

const actions = {
    [ATTRIBUTE_LIST]({commit}) {
        commit(ATTRIBUTE_SET_LOADING)
        ApiService.get("attribute")
            .then(({data}) => {
                commit(SET_ATTRIBUTE_LIST, data);
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
