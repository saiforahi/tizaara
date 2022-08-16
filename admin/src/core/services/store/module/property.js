import ApiService from "@/core/services/api.service";

// action types
export const PROPERTY_LIST = "property/list";

// mutation types
export const SET_PROPERTY_LIST = "setPropertyList";
export const PROPERTY_ADD = "addNewProperty";
export const PROPERTY_MODIFY = "modifyProperty";
export const PROPERTY_REMOVE = "deleteProperty";
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
    [PROPERTY_REMOVE](state, propertiesId) {
        let index = state.property.findIndex(value => value.id === propertiesId);
        state.property.splice(index, 1);
        state.loading = false;
    },
    [PROPERTY_MODIFY](state, properties) {
        let index = state.property.findIndex(value => value.id === properties.id)
        state.property[index] = properties;
        state.loading = false;
    },
    [PROPERTY_ADD](state, properties) {
        state.property.unshift(properties);
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
