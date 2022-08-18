import ApiService from "@/core/services/api.service";
// action types
export const BUSINESS_TYPE_LIST = "business_type/list";
export const ACTIVE_BUSINESS_TYPE_LIST = "active/business_type/list";

// mutation types
export const SET_BUSINESS_TYPE_LIST = "setBusiness_typeList";

const state = {
    business_type: [],
};

const getters = {
    business_typeList: state => state.business_type,

};

const mutations = {
    [SET_BUSINESS_TYPE_LIST](state, business_type) {
        state.business_type = business_type;
    },
};

const actions = {
    [BUSINESS_TYPE_LIST]({commit}) {
        ApiService.get("business_type")
            .then(({data}) => {
                commit(SET_BUSINESS_TYPE_LIST, data);
            })
    },
    [ACTIVE_BUSINESS_TYPE_LIST]({commit}) {
        ApiService.get("active/business_types")
            .then(({data}) => {
                commit(SET_BUSINESS_TYPE_LIST, data);
            })
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};