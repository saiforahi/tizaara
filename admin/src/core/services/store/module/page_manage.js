import ApiService from "@/core/services/api.service";
// action types
export const TERM_CONDITIONS = "term_condition/list";
export const PRIVACY_POLICY = "privacy_policy/list";
export const ABOUT_US = "about_us/list";
export const JOIN_SALES = "join_sales/list";

// mutation types
export const SET_TERM_CONDITIONS = "setTerm_conditionList";
export const SET_PRIVACY_POLICY = "setPrivacy_policyList";
export const SET_ABOUT_US = "setAbout_usList";
export const SET_JOIN_SALES = "setJoin_salesList";


const state = {
    term_condition: '',
    privacy_policy: '',
    about_us: '',
    join_sales: '',
};

const actions = {
    [TERM_CONDITIONS]({commit}) {
        ApiService.get("term_condition")
            .then(({data}) => {
                commit(SET_TERM_CONDITIONS, data);
            })
            .catch(({response}) => {
                console.log(response.data.errors);
            });
    },

    [PRIVACY_POLICY]({commit}) {
        ApiService.get("privacy_policy")
            .then(({data}) => {
                commit(SET_PRIVACY_POLICY, data);
            })
            .catch(({response}) => {
                console.log(response.data.errors);
            });
    },

    [ABOUT_US]({commit}) {
        ApiService.get("about_us")
            .then(({data}) => {
                commit(SET_ABOUT_US, data);
            })
            .catch(({response}) => {
                console.log(response.data.errors);
            });
    },

    [JOIN_SALES]({commit}) {
        ApiService.get("join_sales")
            .then(({data}) => {
                commit(SET_JOIN_SALES, data);
            })
            .catch(({response}) => {
                console.log(response.data.errors);
            });
    },
};

const mutations = {
    [SET_TERM_CONDITIONS](state, data) {
        state.term_condition = data.terms_condition;
    },

    [SET_PRIVACY_POLICY](state, data) {
        state.privacy_policy = data.privacy;
    },

    [SET_ABOUT_US](state, data) {
        state.about_us = data.about_us;
    },

    [SET_JOIN_SALES](state, data) {
        state.join_sales = data.join_sales;
    },
};

const getters = {
    getTermCondition: state => state.term_condition,

    getPrivacyPolicy: state => state.privacy_policy,

    getAboutUs: state => state.about_us,

    getJoinSales: state => state.join_sales,
};


export default {
    state,
    actions,
    mutations,
    getters
};