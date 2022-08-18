import ApiService from "@/core/services/api.service";
// action types
export const HELP_CATEGORY_LIST = "help_category/list";
export const HELP_SUBCATEGORY_LIST = "help_subcategory/list";
export const HELP_QUESTION = "help_question/list";

// mutation types
export const SET_HELP_CATEGORY_LIST = "setHelpCategoryList";
export const SET_HELP_SUBCATEGORY_LIST = "setHelpSubcategoryList";
export const SET_HELP_QUESTION_LIST = "setHelpQuestionList";

const state = {
    help_category: [],
    help_subcategory: [],
    help_question: [],
};

const getters = {
    helpCategoryList: state => state.help_category,
    helpSubcategoryList: state => state.help_subcategory,
    helpQuestionList: state => state.help_question,

    getHelpSubcategoryById: state => id => state.help_subcategory.filter(value => value.category_id === id),
    getTopQuestion: state => state.help_question.filter(value => value.status === 1),
    getQuestionByCategory: state => id => state.help_question.filter(value => value.category_id === id),
    getQuestionBySubcategory: state => id => state.help_question.filter(value => value.subcategory_id === id),

    getQuestionBySlug: state => slug => state.help_question.find(value => value.slug === slug),
    getHelpSubcategoryBySlug: state => slug => state.help_subcategory.find(value => value.slug === slug),
    getHelpCategoryBySlug: state => slug => state.help_category.find(value => value.slug === slug),

    findHelpCategoryById: state => id => state.help_category.find(value => value.id === id),
    findHelpSubcategoryById: state => id => state.help_subcategory.find(value => value.id === id),
};

const mutations = {
    [SET_HELP_CATEGORY_LIST](state, category) {
        state.help_category = category;
    },
    [SET_HELP_SUBCATEGORY_LIST](state, category) {
        state.help_subcategory = category;
    },
    [SET_HELP_QUESTION_LIST](state, category) {
        state.help_question = category;
    },
};

const actions = {
    [HELP_CATEGORY_LIST]({commit}) {
        ApiService.get("help-category")
            .then(({data}) => {
                commit(SET_HELP_CATEGORY_LIST, data);
            })
            .catch(({response}) => {
                console.log(response);
            });
    },

    [HELP_SUBCATEGORY_LIST]({commit}) {
        ApiService.get("help-subcategory")
            .then(({data}) => {
                commit(SET_HELP_SUBCATEGORY_LIST, data);
            })
            .catch(({response}) => {
                console.log(response);
            });
    },

    [HELP_QUESTION]({commit}) {
        ApiService.get("help-question")
            .then(({data}) => {
                commit(SET_HELP_QUESTION_LIST, data);
            })
            .catch(({response}) => {
                console.log(response);
            });
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};