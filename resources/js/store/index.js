import {createStore} from "vuex";
const store = createStore({
    state() {
        return {
            MIX_API_URL_IMAGE:'http://evaluation.scb-lafarge.bj/storage/',
            //MIX_API_URL_IMAGE:'http://127.0.0.1:8085/storage/',
            MIX_API_URL:'http://evaluation.scb-lafarge.bj/',
            // MIX_API_URL:'http://127.0.0.1:8085/',
            MAX_LENGTH:20,
            user:sessionStorage.getItem('user'),
            salarie: sessionStorage.getItem('salarie'),
            authenticate: sessionStorage.getItem('authenticate'),
        }
    },
    getters: {
       getAuthenticate(state){
            return state.authenticate;
        }
    },
    mutations: {
        setUser(state,user){
            //console.log('okkk',user);
            state.user = user;
        } ,
        setAuthenticate(state){
            //console.log('je te suit',sessionStorage.getItem('authenticate'));
            state.authenticate = sessionStorage.getItem('authenticate');
        },
        setSalarie(state,salarie){
            console.log(salarie);
            state.salarie = salarie;
        }

    },
    actions: {
        setUser(context,data){
            let user = JSON.stringify(data)
            sessionStorage.setItem('user',user);
            context.commit('setUser',user);
        },
        setSalarie(context,data){
            let salarie = JSON.stringify(data)
            sessionStorage.setItem('salarie',salarie);
            context.commit('setSalarie',salarie);
        },
        setAuthenticate(context,data){
            sessionStorage.setItem('authenticate',data);
            context.commit('setAuthenticate');
        }
        ,
        isUserRole(context,role){
            let user = sessionStorage.getItem('user');
            let roles = JSON.parse(user).roles;
            let found = false;
            for (let i=0;i<roles.length;++i){
                if (roles[i].name ===role && found===false){
                    found = true;
                }
            }
            return found;
        }
    }
})

export default store

