import ApiService from "@/core/services/api.service";
// action types
export const QUOTATION_LIST = "quotation/list";

// mutation types
export const SET_QUOTATION_LIST = "setQuotationList";
export const CHANGE_QUOTATION_USER_STATUS = "changeQtnUserStatus";

const state = {
    quotation: [],
};

const getters = {
    quotationList(state) {
        return state.quotation;
    },
};

const mutations = {
    [SET_QUOTATION_LIST](state, quotation) {
        state.quotation = quotation;
    },
};

const actions = {
    [QUOTATION_LIST]({commit}) {
        ApiService.get("quotation/for/supplier")
            .then(({data}) => {
                commit(SET_QUOTATION_LIST, data);
            })
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};