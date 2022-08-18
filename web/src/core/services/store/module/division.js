import ApiService from "@/core/services/api.service";

// action types
export const DIVISION_LIST = "division/list";

// mutation types
export const SET_DIVISION_LIST = "setDivisionList";

const state = {
    division: [],
};

const getters = {
    divisionList(state) {
        return state.division;
    },

    getDivisionById: state => id => state.division.filter(value => value.country_id === id),

    getDivisionNameById: state => id => state.division.find(value => value.id === id),
};

const mutations = {
    [SET_DIVISION_LIST](state, division) {
        state.division = division;
    }
};

const actions = {
    [DIVISION_LIST]({commit}) {
        ApiService.get("division")
            .then(({data}) => {
                commit(SET_DIVISION_LIST, data);
            })
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};