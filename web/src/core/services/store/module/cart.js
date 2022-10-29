
// action types
export const CART_LIST = "cart/list";

// mutation types
export const SET_CART_LIST = "setCartList";
export const REMOVE_FORM_CART = "REMOVE_FORM_CART";
export const UPDATE_CART = "UPDATE_CART";
export const CLEAR_CART = "CLEAR_CART";
const state = {
    carts: [],
};

const getters = {
    carts(state) {
        return state.carts;
    },
    getTotal(state){
        return state.carts.reduce((sum,item)=>{
            return sum + (item.price*item.quantity);
        },0)
    },
    totalTax(){
        return state.carts.reduce((sum,item)=>{
            return sum + item.product.tax;
        },0)
    },
    totalShippingCost(){
        return state.carts.reduce((sum,item)=>{
            return sum + item.product.shipping_cost;
        },0)
    },
    getSubTotal(state){
        return state.carts.reduce((sum,item)=>{
            return sum + (item.price*item.quantity);
        },0)
    },
    getCartsById: state => id => state.area.find(value => value.id === id),
};

const mutations = {
    [SET_CART_LIST](state, cart) {
        let index =state.carts.findIndex(item=>item.product.id==cart.product.id)
        if (index>=0) state.carts[index].quantity +=cart.quantity;
        else state.carts.push(cart);
    },
    [REMOVE_FORM_CART](state, index) {
        state.carts.splice(index,1);
    },
    [UPDATE_CART](state, [index,quantity]) {
        state.carts[index].quantity=quantity;
    },
    [CLEAR_CART](state) {
        state.carts=[];
    },
};

const actions = {
    [CART_LIST]({commit},data) {
        commit(SET_CART_LIST, data);
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};