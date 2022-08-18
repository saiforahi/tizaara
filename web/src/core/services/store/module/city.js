import ApiService from "@/core/services/api.service";

// action types
export const CITY_LIST = "city/list";
export const TOP_CITY_LIST = "top/city/list";

// mutation types
export const SET_CITY_LIST = "setCityList";
export const SET_TOP__CITY_LIST = "setTopCityList";

const state = {
    city: [],
    top_cities: [],
};

const getters = {
    cityList(state) {
        return state.city;
    },
    top_cities(state) {
        return state.top_cities;
    },
    getCityById: state => id => state.city.filter(value => value.division_id === id),

    getCityNameById: state => id => state.city.find(value => value.id === id),
};

const mutations = {
    [SET_CITY_LIST](state, city) {
        state.city = city;
    },
    [SET_TOP__CITY_LIST](state, cities) {
        state.top_cities = cities;
    }
};

const actions = {
    [CITY_LIST]({commit}) {
        ApiService.get("city")
            .then(({data}) => {
                commit(SET_CITY_LIST, data);
            })
    },
    [TOP_CITY_LIST]({commit}) {
        return new Promise((resolve, reject) => {
            ApiService.get("get/supplier/from/top/cities")
                .then(({data}) => {
                    commit(SET_TOP__CITY_LIST, data);
                    resolve();
                })
                .catch(error => {
                    reject(error)
                })
        });
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};