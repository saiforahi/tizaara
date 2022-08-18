import ApiService from "@/core/services/api.service";

// action types
export const PROPERTY_LIST = "property/list";

// mutation types
export const SET_PROPERTY_LIST = "setPropertyList";
export const SET_ERROR = "setError";
export const PROPERTY_SET_LOADING = "propertySetLoading";

const state = {
    errors: null,
    loading: false,
    property: [],
};

const getters = {
    propertyList: state => state.property,

    propertyErrors: state => state.errors,

    propertyIsLoaded: state => !state.loading,
};

const mutations = {
    [SET_ERROR](state, error) {
        state.errors = error;
        state.loading = false;
    },
    [SET_PROPERTY_LIST](state, properties) {
        properties.length > 0 ? state.property = properties : '';
    },
    [PROPERTY_SET_LOADING](state, loading = true) {
        state.loading = loading;
    }
};


const actions = {
    [PROPERTY_LIST]({commit}) {
        commit(PROPERTY_SET_LOADING)
        ApiService.get("property")
            .then(({data}) => {
                commit(SET_PROPERTY_LIST, data);
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
