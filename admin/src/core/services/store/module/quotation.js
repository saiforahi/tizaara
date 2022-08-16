import ApiService from "@/core/services/api.service";
// action types
export const QUOTATION_LIST = "quotation/list";

// mutation types
export const SET_QUOTATION_LIST = "setQuotationList";
export const QUOTATION_REMOVE = "deleteQuotation";
export const QUOTATION_MODIFY = "modifyQuotation";
export const QUOTATION_CANCEL = "QUOTATION_CANCEL";

const state = {
    quotation: {},
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

    [QUOTATION_REMOVE](state, quotationId) {
        let index = state.quotation.findIndex(value => value.id === quotationId);
        state.quotation.splice(index, 1);
    },

    [QUOTATION_MODIFY](state, quotationId) {
        let index = state.quotation.findIndex(value => value.id === quotationId)
        state.quotation[index].status = 1;
    },
    [QUOTATION_CANCEL](state, quotationId) {
        let index = state.quotation.findIndex(value => value.id === quotationId)
        state.quotation[index].is_cancel = 1;
    },
};

const actions = {
    [QUOTATION_LIST]({commit}) {
        ApiService.get("quotation")
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