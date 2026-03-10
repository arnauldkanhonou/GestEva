
require('./bootstrap');

import { createApp } from "vue";
// window.Vue = require('vue').default;
import { createStore } from "vuex";
//import Customerindex from "./components/customers/Customerindex.vue";
import TitreApplication from "./components/block/TitreApplication";
import App from "./components/block/App";
import router from "./router"
import store from "./store/index";

/*const store = createStore({
    state() {
        return {
            errors: {}
        }
    },
    getters: {},
    mutations: {
        setError(state, payload) {
            console.log('il a bien fonctionner.',payload)
            state.errors = payload;
           // console.log('i see you',state.errors['name'][0])
            // state.counter = state.counter + payload
        }
    },
    actions: {
        hasError (context,fieldName) {
            //console.log((fieldName in context.state.errors))
            return (fieldName in context.state.errors);
        },
        getError (context,fieldName) {
            //console.log('ok',context.state.errors[fieldName][0])
            return context.state.errors[fieldName][0] ;
        },
        setError(context, payload) {
            context.commit('setError', payload);
        }
    }
})*/


createApp ({
    components: {App},
}).use(router).use(store).mount('#app')
