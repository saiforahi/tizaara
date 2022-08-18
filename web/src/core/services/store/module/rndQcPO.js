/*
* model for rnd,qc staff list production line,annual output related
*
* */

import ApiService from "@/core/services/api.service";

// action types
export const RND_QC_STAFF_PRODUCTION_OUTPUT_LIST = "rnd/qc/production/annual/output";

// mutation types
export const SET_RND_STAFF_LIST = "setRndStaffList";
export const SET_QC_STAFF_LIST = "setQcStaffList";
export const SET_PRODUCTION_LINE_LIST = "setProductionLine";
export const SET_ANNUAL_OUTPUT_LIST = "setAnnualOutPut";


const state = {
    staff_numbers: [],//all staff numbers store
    rnd_staffs: [],//all rand staff store
    production_lines: [],//all production line store
    annual_outputs: [],//all annual output store
};

const getters = {
    /*
    * method for get QC staff
    * */
    staff_numbers(state) {
        return state.staff_numbers;
    },
    /*
    * method for get RND staff
    * */
    rnd_staffs(state) {
        return state.rnd_staffs;
    },
    /*
    * method for get production lines
    * */
    production_lines(state) {
        return state.production_lines;
    },
    /*
    * method for get annual outputs
    * */
    annual_outputs(state) {
        return state.annual_outputs;
    },
};

const mutations = {
    [SET_RND_STAFF_LIST](state, rnd_staffs) {
        state.rnd_staffs = rnd_staffs;
    },
    [SET_QC_STAFF_LIST](state, staff_numbers) {
        state.staff_numbers = staff_numbers;
    },
    [SET_PRODUCTION_LINE_LIST](state, production_lines) {
        state.production_lines = production_lines;
    },
    [SET_ANNUAL_OUTPUT_LIST](state, annual_outputs) {
        state.annual_outputs = annual_outputs;
    }
};

const actions = {
    /*method for get production lines,staff,annual output get*/
    [RND_QC_STAFF_PRODUCTION_OUTPUT_LIST]({commit}) {
        ApiService.get("rnd/qc/production/annual/output/get")
            .then(({data}) => {
                commit(SET_RND_STAFF_LIST, data.rnd_staffs);
                commit(SET_QC_STAFF_LIST, data.staff_numbers);
                commit(SET_PRODUCTION_LINE_LIST, data.production_lines);
                commit(SET_ANNUAL_OUTPUT_LIST, data.annual_outputs);
            })
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};