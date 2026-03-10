import axios from "axios";
import store from "../store";
//const store = useStore();

const axiosClient = axios.create({
    baseURL:'http://127.0.0.1:8085/api/'
})
// axiosClient.interceptors.request.use(config=>{
//     config.headers.common['X-XSRF-TOKEN'] = store.state.token;
//     return config;
// });

export default axiosClient;
