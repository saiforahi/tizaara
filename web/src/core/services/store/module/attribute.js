import ApiService from "@/core/services/api.service";

// action types
export const ATTRIBUTE_LIST = "attribute/list";

// mutation types
export const SET_ATTRIBUTE_LIST = "setAttributeList";

const state = {
    attribute: [],
};

const getters = {
    attributeList(state) {
        return state.attribute;
    }
};

const mutations = {
    [SET_ATTRIBUTE_LIST](state, attributes) {
        attributes.length > 0 ? state.attribute = attributes : [];
    }
};

const actions = {
    [ATTRIBUTE_LIST]({commit}) {
        ApiService.get("attribute")
            .then(({data}) => {
                commit(SET_ATTRIBUTE_LIST, data);
            })
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};
