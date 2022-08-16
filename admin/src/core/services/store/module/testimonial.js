import ApiService from "@/core/services/api.service";

// action types
export const TESTIMONIAL_LIST = "testimonial/list";

// mutation types
export const SET_TESTIMONIAL_LIST = "setTestimonial";
export const TESTIMONIAL_ADD = "addNewTestimonial";
export const TESTIMONIAL_REMOVE = "deleteTestimonial";
export const TESTIMONIAL_STATUS_UPDATE = "statusTestimonialUpdate";
export const TESTIMONIAL_UPDATE = "testimonialUpdate";

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

    [TESTIMONIAL_ADD](state, data) {
        state.testimonial.unshift(data);
    },
    [TESTIMONIAL_REMOVE](state, data) {
        let index = state.testimonial.findIndex(value => value.id === data);
        state.testimonial.splice(index, 1);
    },

    [TESTIMONIAL_STATUS_UPDATE](state, data) {
        let index = state.testimonial.findIndex(value => value.status === 1);
        let index2 = state.testimonial.findIndex(value => value.id === data);
        if (index >= 0) {
            state.testimonial[index].status = 0;
            index === index2 ? state.testimonial[index2].status = 0 : state.testimonial[index2].status = 1;
        } else {
            state.testimonial[index2].status = 1;
        }

    },
    [TESTIMONIAL_UPDATE](state, [data,id]) {
        let index = state.testimonial.findIndex(item=>item.id==id);
        if (index>=0) state.testimonial[index] =data;
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