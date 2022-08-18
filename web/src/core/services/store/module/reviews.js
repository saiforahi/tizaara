import ApiService from "@/core/services/api.service";

// action types
export const REVIEW_RATING_LIST = "review/rating/all";

// mutation types
export const SET_REVIEW_RATING_LIST = "set/review/rating";


const state = {
    reviews: [],//single product wise all review
    ratings:[],//single product wise all rating

};

const getters = {
    reviews(state) {
        return state.reviews;
    },
    ratings(state) {
        return state.ratings;
    },
};

const mutations = {
    [SET_REVIEW_RATING_LIST](state, data) {
        state.reviews = data.reviews;
        state.ratings = data.ratings;
    }
};

const actions = {
    [REVIEW_RATING_LIST]({commit},product_id) {
        ApiService.get(`get/product/review/rating${product_id}`)
            .then(({data}) => {
                commit(SET_REVIEW_RATING_LIST, data);
            })
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};