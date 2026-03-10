<template>
    <div>
        <section class="h-screen">
            <div class="px-6 h-full text-gray-800">
                <div class="flex xl:justify-center lg:justify-between justify-center items-center flex-wrap h-full g-6">
                    <div class="grow-0 shrink-1 md:shrink-0 basis-auto xl:w-6/12 lg:w-6/12 md:w-9/12 mb-12 md:mb-0">
                        <img v-bind:src="store.state.MIX_API_URL_IMAGE+'images/login.jpg'" class="w-full"
                             alt="image login"/>
                    </div>
                    <div class="xl:ml-20 xl:w-5/12 lg:w-5/12 md:w-8/12 mb-12 md:mb-0">
                        <div class="alert alert-danger" v-if="tabError">
                            <strong >{{ tabError }}</strong>
                        </div>
                        <div v-if="message!==''" class="alert alert-success">
                            <strong> {{ message }} </strong>
                        </div>
                        <form method="post" @submit.prevent="handleLogin">
                            <!--<div class=" items-center justify-center lg:justify-start">
                                <p class="text-lg mb-0 mr-4"><b style="font-size: 35px;color:#0a58ca">SCB-</b><b style="color: #057e72;font-size: 35px;">L</b><b style="color:#0a58ca;font-size: 35px;">afarge</b></p>
                            </div>-->
                            <div>
                                <p class="text-lg mb-0 mr-4"><b style="font-size: 30px;color: #057e72;">ASSESMENT
                                    MANAGER CONNEXION</b></p>
                            </div>
                            <div class="flex items-center my-4 before:flex-1 before:border-t before:border-gray-300 before:mt-0.5 after:flex-1 after:border-t after:border-gray-300 after:mt-0.5">
                                <p class="font-semibold mb-0"><b style="color:#0a6879;font-size: 20px">Veuillez saisir
                                    vos informations de connexion</b></p>
                            </div>

                            <!-- Email input -->
                            <div class="mb-6">
                                <input type="text" v-model="form.email" :class="Errors.email?'is-invalid':''" @keyup="validateIputEmail" @blur="validateIputEmail"
                                       class="form-control block w-full px-4 py-2 text-md font-normal text-gray-700 bg-white bg-clip-padding  rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                       id="exampleFormControlInput2d" placeholder="Saisissez votre E-mail"/>
                                <strong v-if="Errors.email" class="text-red-600">{{Errors.email }}</strong>
                            </div>

                            <!-- Password input -->
                            <div class="mb-6">
                                <input v-model="form.password" type="password" :class="Errors.password?'is-invalid':''" @keyup="validateInputPassword" @blur="validateInputPassword"
                                       class="form-control block w-full px-4 py-2 text-md font-normal text-gray-700 bg-white bg-clip-padding rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                       id="exampleFormControlInput2" placeholder="Saissez votre mot de passe"/>
                                <strong v-if="Errors.password" class="text-red-600">{{Errors.password }}</strong>
                            </div>

                            <div>
                                <button :disabled="isFormButtonDisabled" :class="isFormButtonDisabled?'btn btn-default text-white':'py-2 px-20 text-white align-content-center'" type="submit" class="w-full py-2 btn-green text-white ">
                                    <b id="btn-connect" style="font-size: 18px">Se connecter</b>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import {useStore} from "vuex";
    import {ref,onMounted,reactive} from "vue";
    import axiosClient from "../../axios";
    import {useRouter} from "vue-router"
    import axios from "axios"
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import appService from "../../services/appService";

    export default {
        name: "Login",
        props: {
          message:{
              default:'',
              type:String,
              required:false,
          }
        },
        setup(props) {
            onMounted(()=>{
                sessionStorage.clear();
            })
            const form = reactive({
                email: '',
                password: ''
            })
            const tabError = ref('')
            const store = useStore();
            const router = useRouter();
            //const axios = axiosClient;
            const {Errors,validateEmailField,validateNameField,validateLength} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(form,Errors);
            const {hasRole} = appService();

            const validateIputEmail = ()=>{
                validateEmailField('email',form.email)
            }
            const validateInputPassword = ()=>{
                (form.password ===''?validateNameField('password',form.password):
                    validateLength('password',form.password,8,false))
            }

            const handleLogin = async () => {
                props.message ='';
                $('#btn-connect').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                tabError.value='';
                axios.get('sanctum/csrf-cookie').then(() => {
                    axios.post('login', {...form}).then((response) => {
                        if (response.data.errors) {
                            router.push({name: 'login'});
                        }
                        if (response.data.success){
                            store.dispatch('setAuthenticate',response.data.success);
                            store.dispatch('setUser',response.data.user);
                            store.dispatch('setSalarie',response.data.employe);
                            let user = JSON.parse(store.state.user);
                            if (user.isFistLogin){
                                router.push({name: 'reset.password'});
                            }else {
                                if (hasRole('ROLE_ADMIN')||hasRole('ROLE_CODI')){
                                    router.push({name: 'dashboard.admin'});
                                }else if (hasRole('ROLE_RESPONSABLE') && (response.data.employe.respService || response.data.employe.respUnite)){
                                    router.push({name: 'dashboard.responsable'});
                                }else if (hasRole('ROLE_RESPONSABLE') && response.data.employe.respDepartement){
                                    router.push({name: 'dashboard.departement'});
                                } else if (hasRole('ROLE_DIRECTEUR')){
                                    router.push({name: 'dashboard.direction'});
                                }else {
                                    router.push({name: 'dashboard.user'});
                                }

                            }


                        }else {
                            tabError.value = response.data.message;
                        }
                        $('#btn-connect').html('Se connecter')
                    }).catch((error)=>{
                        let createCustomerErrors = error.response.data.errors;
                        if (error.response.status === 422) {
                            for (const key in createCustomerErrors) {
                                tabError.value += error.response.data.errors[key][0] + '| ';
                            }
                        }
                        $('#btn-connect').html('Se connecter')
                    })

                });
            }
            return {
                store,
                form,
                Errors,
                tabError,
                isFormButtonDisabled,
                validateIputEmail,
                validateInputPassword,
                handleLogin,
            }
        }
    }
</script>

<style scoped>

</style>
