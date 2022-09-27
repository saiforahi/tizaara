import ApiService from "@/core/services/api.service";

// action types
export const ECOM_ZONE_LIST = "ecom_zone_requests/list";
export const ECOM_ZONE_REQUESTS = "request_flash_deals/list";

// mutation types
export const SET_ECOM_ZONE_REQUESTS = "setEcomZoneRequests";
export const ECOM_ZONE_REQUEST_REMOVE = "deleteEcomZoneRequest";


const state = {
    ecom_zone_requests: [],
    request_flash_deals: [],
};


const getters = {
    ecom_zone_requestsList: state => state.ecom_zone_requests,
};

const mutations = {
    [SET_ECOM_ZONE_REQUESTS](state, data) {
        data.length > 0 ? state.ecom_zone_requests = data : '';
    },
    [ECOM_ZONE_REQUEST_REMOVE](state, dataId) {
        let index = state.ecom_zone_requests.findIndex(value => value.id === dataId);
        state.ecom_zone_requests.splice(index, 1);
    },
};

const actions = {
    [ECOM_ZONE_LIST]({commit}) {
        ApiService.get("ecom-zone-request-list")
            .then(({data}) => {
                console.log('dataa',data)
                commit(SET_ECOM_ZONE_REQUESTS, data);
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