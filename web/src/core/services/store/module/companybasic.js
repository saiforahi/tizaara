import ApiService from "@/core/services/api.service";

// action types
export const COMPANY_BASIC_LIST = "area/companyBasic";

// mutation types
export const SET_COMPANY_BASIC_LIST = "setCompanyBasicList";


const state = {
    companyBasic: [],

};

const getters = {
    companyBasicInfo(state) {
        return state.companyBasic;
    },
    companyBasicInfoByUserID(state,id) {
        return state.companyBasic.find(item=>item.user_id===parseInt(id));
    },
};

const mutations = {
    [SET_COMPANY_BASIC_LIST](state, companyBasic) {
        state.companyBasic = companyBasic;
    }
};

const actions = {
    [COMPANY_BASIC_LIST]({commit}) {
        ApiService.get("factory")
            .then(({data}) => {
                commit(SET_COMPANY_BASIC_LIST, data);
            })
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};