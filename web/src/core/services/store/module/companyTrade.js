import ApiService from "@/core/services/api.service";

// action types
export const COMPANY_TRADE_LIST = "companyTrade";
export const COMPANY_ANNUAL_REVENUE_EXPORT_PERCENT_LIST = "company annual revenue export percent";

// mutation types
export const SET_COMPANY_TRADE_LIST = "setCompanyTrade";
export const SET_COMPANY_ANNUAL_REVENUE_LIST = "setCompanyAnnualRevenue";
export const SET_COMPANY_EXPORT_PERCENT_LIST = "setCompanyExportPercent";


const state = {
    company_trades: [],
    annual_revenues:[],
    export_percents:[],

};

const getters = {
    company_trades(state) {
        return state.company_trades;
    },
    annual_revenues(state) {
        return state.annual_revenues;
    },
    export_percents(state) {
        return state.export_percents;
    },
};

const mutations = {
    [SET_COMPANY_TRADE_LIST](state, company_trades) {
        state.company_trades = company_trades;
    },
    [SET_COMPANY_ANNUAL_REVENUE_LIST](state, annual_revenues) {
        state.annual_revenues = annual_revenues;
    },
    [SET_COMPANY_EXPORT_PERCENT_LIST](state, export_percents) {
        state.export_percents = export_percents;
    }
};

const actions = {
    [COMPANY_TRADE_LIST]({commit}) {
        ApiService.get("company/trade/info/get")
            .then(({data}) => {
                commit(SET_COMPANY_TRADE_LIST, data);
            })
    },
    [COMPANY_ANNUAL_REVENUE_EXPORT_PERCENT_LIST]({commit}) {
        ApiService.get("company/annual/revenue/export/percents")
            .then(({data}) => {
                commit(SET_COMPANY_ANNUAL_REVENUE_LIST, data.annual_revenues);
                commit(SET_COMPANY_EXPORT_PERCENT_LIST, data.export_percents);
            })
    },
};

export default {
    state,
    actions,
    mutations,
    getters
};