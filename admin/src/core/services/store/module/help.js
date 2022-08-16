import ApiService from "@/core/services/api.service";
// action types
export const HELP_CATEGORY_LIST = "help_category/list";
export const HELP_SUBCATEGORY_LIST = "help_subcategory/list";
export const HELP_QUESTION = "help_question/list";

// mutation types
export const SET_HELP_CATEGORY_LIST = "setHelpCategoryList";
export const SET_HELP_SUBCATEGORY_LIST = "setHelpSubcategoryList";
export const SET_HELP_QUESTION_LIST = "setHelpQuestionList";
export const HELP_CATEGORY_ADD = "addNewHelpCategory";
export const HELP_SUBCATEGORY_ADD = "addNewHelpSubcategory";
export const HELP_QUESTION_ADD = "addNewHelpQuestion";
export const HELP_CATEGORY_MODIFY = "modifyHelpCategory";
export const HELP_SUBCATEGORY_MODIFY = "modifyHelpSubcategory";
export const HELP_QUESTION_MODIFY = "modifyHelpQuestion";
export const HELP_QUESTION_STATUS_MODIFY = "modifyHelpQuestionStatus";
export const HELP_CATEGORY_REMOVE = "deleteHelpCategory";
export const HELP_SUBCATEGORY_REMOVE = "deleteHelpSubcategory";
export const HELP_QUESTION_REMOVE = "deleteHelpQuestion";

const state = {
    help_category: [],
    help_subcategory: [{}],
    help_question: [{}],
};

const getters = {
    helpCategoryList: state => state.help_category,
    helpSubcategoryList: state => state.help_subcategory,
    helpQuestionList: state => state.help_question,
    getHelpSubcategoryById: state => id => state.help_subcategory.filter(value => value.category_id === id),
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
    [HELP_CATEGORY_ADD](state, category) {
        state.help_category.unshift(category);
    },
    [HELP_SUBCATEGORY_ADD](state, category) {
        state.help_subcategory.unshift(category);
    },
    [HELP_QUESTION_ADD](state, category) {
        state.help_question.unshift(category);
    },
    [HELP_CATEGORY_MODIFY](state, categories) {
        let index = state.help_category.findIndex(value => value.id === categories.id)
        state.help_category[index] = categories;
    },
    [HELP_SUBCATEGORY_MODIFY](state, categories) {
        let index = state.help_subcategory.findIndex(value => value.id === categories.id)
        state.help_subcategory[index] = categories;
    },
    [HELP_QUESTION_MODIFY](state, categories) {
        let index = state.help_question.findIndex(value => value.id === categories.id)
        state.help_question[index] = categories;
    },
    [HELP_QUESTION_STATUS_MODIFY](state, categories) {
        let index = state.help_question.findIndex(value => value.id === categories.id)
        state.help_question[index].status = categories.status;
    },
    [HELP_CATEGORY_REMOVE](state, categoryId) {
        let index = state.help_category.findIndex(value => value.id === categoryId);
        state.help_category.splice(index, 1);
    },
    [HELP_SUBCATEGORY_REMOVE](state, categoryId) {
        let index = state.help_subcategory.findIndex(value => value.id === categoryId);
        state.help_subcategory.splice(index, 1);
    },
    [HELP_QUESTION_REMOVE](state, categoryId) {
        let index = state.help_question.findIndex(value => value.id === categoryId);
        state.help_question.splice(index, 1);
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