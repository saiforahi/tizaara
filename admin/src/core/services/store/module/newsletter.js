import ApiService from "@/core/services/api.service";

// action types
export const NEWSLETTER_LIST = "newsletter/list";

// mutation types
export const SET_NEWSLETTER = "setNewsletter";


const state = {
    newsletter: [{
        title: '',
        elements: []
    }],
};

const getters = {
    getNewsletter: state => state.newsletter,
};

const mutations = {
    [SET_NEWSLETTER](state, data) {
        data.length > 0 ? state.newsletter = data : '';
    },
};

const actions = {
    [NEWSLETTER_LIST]({commit}) {
        ApiService.get("newsletter")
            .then(({data}) => {
                commit(SET_NEWSLETTER, data);
            })
            .catch(({response}) => {
                console.log(response);
            });
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};