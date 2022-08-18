import ApiService from "@/core/services/api.service";

// action types
export const COMPANY_FACTORY_LIST = "companyFactory";

// mutation types
export const SET_COMPANY_FACTORY_LIST = "setCompanyFactory";


const state = {
    company_factories: [],

};

const getters = {
    company_factories(state) {
        return state.company_factories;
    },
};

const mutations = {
    [SET_COMPANY_FACTORY_LIST](state, company_factories) {
        state.company_factories = company_factories;
    }
};

const actions = {
    [COMPANY_FACTORY_LIST]({commit}) {
        ApiService.get("company/factory/info/get")
            .then(({data}) => {
                commit(SET_COMPANY_FACTORY_LIST, data);
            })
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};