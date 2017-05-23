import * as types from '../types'

const contentHeaderModule = {
    state    : {
        title      : '',
        description: ''
    },
    mutations: {
        [types.UPDATE_CONTENT_HEADER] (state, payload) {
            state.title       = payload.title || '';
            state.description = payload.description || '';
        }
    }
};

export default contentHeaderModule;