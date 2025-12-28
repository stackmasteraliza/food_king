import axios from "axios";
import appService from "../../services/appService";

export const myOrderDetails = {
    namespaced: true,
    state: {
        orderDetails: {},
        orderItems: {},
        orderBranch: {},
        orderUser: {},
        orderAddress: {},
        order_Reservations: {},
    },
    getters: {
        orderDetails: function (state) {
            return state.orderDetails;
        },
        orderItems: function (state) {
            return state.orderItems;
        },
        orderBranch: function (state) {
            return state.orderBranch;
        },
        orderUser: function (state) {
            return state.orderUser;
        },
        orderAddress: function (state) {
            return state.orderAddress;
        },
        order_Reservations: function (state) {
            return state.order_Reservations;
        },
    },
    actions: {
        orderDetails: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/my-order/show/${payload.id}/${payload.orderId}`).then((res) => {
                    context.commit("orderDetails", res.data.data);
                    context.commit("orderItems", res.data.data.order_items);
                    context.commit("orderBranch", res.data.data.branch);
                    context.commit("orderUser", res.data.data.user);
                    context.commit("orderAddress", res.data.data.order_address);
                    context.commit("order_Reservations", res.data.data.order_Reservations);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        orderDetails: function (state, payload) {
            state.orderDetails = payload;
        },
        orderItems: function (state, payload) {
            state.orderItems = payload;
        },
        orderBranch: function (state, payload) {
            state.orderBranch = payload;
        },
        orderUser: function (state, payload) {
            state.orderUser = payload;
        },
        orderAddress: function (state, payload) {
            state.orderAddress = payload;
        },
        order_Reservations: function (state, payload) {
            state.order_Reservations = payload;
        },
    },
};
