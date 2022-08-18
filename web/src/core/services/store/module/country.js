import ApiService from "@/core/services/api.service";

// action types
export const COUNTRY_LIST = "country/list";

// mutation types
export const SET_COUNTRY_LIST = "setCountryList";

const state = {
    country: [],
};

const getters = {
    countryList(state) {
        return state.country;
    },
    getCountryNameById: state => id => state.country.find(value => value.id === id),
};

const mutations = {
    [SET_COUNTRY_LIST](state, country) {
        state.country = country;
    }
};

const actions = {
    [COUNTRY_LIST]({commit}) {
        ApiService.get("country")
            .then(({data}) => {
                commit(SET_COUNTRY_LIST, data);
            })
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};