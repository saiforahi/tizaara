import ApiService from "@/core/services/api.service";

// action types
export const CERTIFICATE_LIST = "certificates";

// mutation types
export const SET_CERTIFICATE_LIST = "setCertificates";


const state = {
    certificates: [],

};

const getters = {
    certificates(state) {
        return state.certificates;
    },
};

const mutations = {
    [SET_CERTIFICATE_LIST](state, certificates) {
        state.certificates = certificates;
    }
};

const actions = {
    [CERTIFICATE_LIST]({commit}) {
        ApiService.get("get/certificates")
            .then(({data}) => {
                commit(SET_CERTIFICATE_LIST, data);
            })
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};