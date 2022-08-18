import ApiService from "@/core/services/api.service";

// action types
export const PERSONAL_LIST = "area/personal";

// mutation types
export const SET_PERSONAL_LIST = "setPersonalList";

const state = {
    personal: [],
};

const getters = {
    personalInfo(state) {
        return state.personal;
    },
};

const mutations = {
    [SET_PERSONAL_LIST](state, personal) {
        state.personal = personal;
    }
};

const actions = {
    [PERSONAL_LIST]({commit}) {
        ApiService.get("personal")
            .then(({data}) => {
                commit(SET_PERSONAL_LIST, data);
            })
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};