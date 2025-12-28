import axios from "axios";
import appService from "../../../services/appService";

export const frontendTimeSlot = {
    namespaced: true,
    state: {
        today: [],
        tomorrow: [],
        now: {}
    },
    getters: {
        today: function (state) {
            return state.today;
        },
        tomorrow: function (state) {
            return state.tomorrow;
        },
        now: function (state) {
            return state.now;
        }
    },
    actions: {
        today: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/time-slot/today";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("today", res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        tomorrow: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/time-slot/tomorrow";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("tomorrow", res.data.data);
                    }

                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        }
    },
    mutations: {
        today: function (state, payload) {
            if (payload.length > 0 && payload[0].label === 'now') {
                state.now = payload[0];
                payload.shift();
            }
            state.today = payload;
        },
        tomorrow: function (state, payload) {
            state.tomorrow = payload;
        }
    },
};
