import ApiService from "@/core/services/api.service";

// action types
export const TESTIMONIAL_LIST = "testimonial/list";

// mutation types
export const SET_TESTIMONIAL_LIST = "setTestimonial";

const state = {
    testimonial: [],
};

const getters = {
    getSingleTestimonial(state) {
        if (state.testimonial.length > 0) {
            return state.testimonial.find(value => value.status === 1);
        }
    },

    getTestimonial: state => state.testimonial,
};

const mutations = {
    [SET_TESTIMONIAL_LIST](state, data) {
        data.length > 0 ? state.testimonial = data : '';
    },
};

const actions = {
    [TESTIMONIAL_LIST]({commit}) {
        ApiService.get("testimonial")
            .then(({data}) => {
                commit(SET_TESTIMONIAL_LIST, data);
            })
            .catch(({response}) => {
                console.log(response.data.errors);
            });
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};