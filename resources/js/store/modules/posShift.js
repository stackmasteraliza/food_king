import axios from 'axios'


export const posShift = {
    state: {
        posShiftStatus: false,
        posShiftsessionNumber: null,
        posShiftInfo: {},
        resetInfo: {
            shift_id: null,
            device_id: null,
            sessionNumber: null
        },
        lists: [],
        page: {},
        pagination: [],
        show: {},
        temp: {
            temp_id: null,
            isEditing: false,
        },
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        pagination: function (state) {
            return state.pagination;
        },
        page: function (state) {
            return state.page;
        },
        show: function (state) {
            return state.show;
        },
        temp: function (state) {
            return state.temp;
        },
        posShiftStatus: function (state) {
            return state.posShiftStatus;
        },
        posShiftsessionNumber: function (state) {
            return state.posShiftsessionNumber;
        },
        posShiftInfo: function (state) {
            return state.posShiftInfo;
        },
        resetInfo: function (state) {
            return state.resetInfo;
        }
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/pos-shifts";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (
                        typeof payload.vuex === "undefined" ||
                        payload.vuex === true
                    ) {
                        context.commit("lists", res.data.data);
                        context.commit("page", res.data.meta);
                        context.commit("pagination", res.data);
                    }
                    resolve(res);
                })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        openSession: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('admin/pos-shifts', payload).then((res) => {
                    context.commit('openSession', res.data);
                    context.commit('posShiftInfo', res.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        generatesession: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get('admin/pos-shifts/generate-session/' + payload.id).then((res) => {
                    context.commit('posShiftsessionNumber', res.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        updateposShiftInfo: function (context, payload) {
            return new Promise((resolve, reject) => {
                if (context.state.posShiftInfo.id === payload.id) {
                    context.commit('posShiftInfo', payload);
                    resolve(payload);
                } else {
                    reject('user data not match');
                }
            });
        },

    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        },
        pagination: function (state, payload) {
            state.pagination = payload;
        },
        page: function (state, payload) {
            if (typeof payload !== "undefined" && payload !== null) {
                state.page = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total,
                };
            }
        },
        show: function (state, payload) {
            state.show = payload;
        },
        temp: function (state, payload) {
            state.temp.temp_id = payload;
            state.temp.isEditing = true;
        },
        reset: function (state) {
            state.temp.temp_id = null;
            state.temp.isEditing = false;
        },
        openSession: function (state, payload) {
            state.posShiftStatus = true;
            state.posShiftsessionNumber = payload.token;
            state.posShiftInfo = payload.user;

        },
        posShiftClose: function (state) {
            state.posShiftStatus = false;
            state.posShiftsessionNumber = null;
            state.posShiftInfo = {};

        },

        posShiftInfo: function (state, payload) {
            state.posShiftInfo = payload;
        }
    },
}
