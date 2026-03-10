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
                        <form method="post" @submit.prevent="resetPassword">
                            <!--<div class=" items-center justify-center lg:justify-start">
                                <p class="text-lg mb-0 mr-4"><b style="font-size: 35px;color:#0a58ca">SCB-</b><b style="color: #057e72;font-size: 35px;">L</b><b style="color:#0a58ca;font-size: 35px;">afarge</b></p>
                            </div>-->
                            <div>
                                <p class="text-lg mb-0 mr-4 text-red-600"><b style="font-size: 30px;">CHANGEMENT DE MOT DE PASSE</b></p>
                            </div>
                            <div class="flex items-center my-4 before:flex-1 before:border-t before:border-gray-300 before:mt-0.5 after:flex-1 after:border-t after:border-gray-300 after:mt-0.5">
                                <p class="font-semibold mb-0"><b style="color:#0a6879;font-size: 18px">Pour une première connexion vous devez changer votre mot de passe</b></p>
                            </div>

                            <!-- Email input -->
                            <div class="mb-6">
                                <input  type="text" v-model="form.email" :class="Errors.email?'is-invalid':''" @keyup="validateIputEmail" @blur="validateIputEmail"
                                       class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding  rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                       id="exampleFormControlInput2d" placeholder="Saisissez votre E-mail"/>
                                <strong v-if="Errors.email" class="text-red-600">{{Errors.email }}</strong>
                            </div>

                            <!-- New Password input -->
                            <div class="mb-6">
                                <input v-model="form.password" type="password" :class="Errors.password?'is-invalid':''" @keyup="validateInputPassword" @blur="validateInputPassword"
                                       class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                       id="exampleFormControlInput3" placeholder="Saissez votre nouveau mot de passe"/>
                                <strong v-if="Errors.password" class="text-red-600">{{Errors.password }}</strong>
                            </div>

                            <!-- Confirm New Password input -->
                            <div class="mb-6">
                                <input v-model="form.passwordConfirm" type="password" :class="Errors.passwordConfirm?'is-invalid':''" @keyup="validateInputPasswordConfirm" @blur="validateInputPasswordConfirm"
                                       class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                       id="exampleFormControlInput2" placeholder="Confirmer votre noveau mot de passe"/>
                                <strong v-if="Errors.passwordConfirm" class="text-red-600">{{Errors.passwordConfirm }}</strong>
                            </div>

                            <div>
                                <button :disabled="isFormButtonDisabled" :class="isFormButtonDisabled?'btn btn-default text-white':'py-2 px-20 text-white align-content-center'" type="submit" class="w-full py-2 btn-green text-white ">
                                    <b id="btn-reset" style="font-size: 18px">Changer mon mot de passe</b>
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

    export default {
        name: "Login",
        setup() {
            const store = useStore();
            const form = reactive({
                email: JSON.parse(store.state.user).email,
                password: '',
                passwordConfirm:''
            })
            const tabError = ref('')

            const router = useRouter();
            //const axios = axiosClient;
            const {Errors,validateEmailField,validateNameField,validateLength} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(form,Errors);

            const validateIputEmail = ()=>{
                validateEmailField('email',form.email)
            }
            const validateInputPasswordConfirm = ()=>{
                (form.passwordConfirm ===''?validateNameField('passwordConfirm',form.passwordConfirm):
                    validateLength('passwordConfirm',form.passwordConfirm,8,false))
            }
            const validateInputPassword = ()=>{
                (form.password ===''?validateNameField('password',form.password):
                    validateLength('password',form.password,8,false))
            }

            const resetPassword = async () => {
                tabError.value='';
                $('#btn-reset').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                if (form.password!==form.passwordConfirm){
                    tabError.value = 'Veuillez bien confirmé le nouveau mot de passe';
                    $('#btn-reset').html('Changer mon mot de passe')
                }
                axios.post('password/reset', {...form}).then((response) => {
                    if (parseInt(response.data.code)===201){
                        router.push({name: 'login',params:{message:'Mot de passe changé avec succès. Connectez vous à nouveau !'}});
                    }else {
                        tabError.value = response.data.message;
                    }
                    $('#btn-reset').html('Changer mon mot de passe')
                }).catch((error)=>{
                    let createCustomerErrors = error.response.data.errors;
                    if (error.response.status === 422) {
                        for (const key in createCustomerErrors) {
                            tabError.value += error.response.data.errors[key][0] + '| ';
                        }
                    }
                    $('#btn-reset').html('Changer mon mot de passe')
                })
            }
            return {
                store,
                form,
                Errors,
                tabError,
                isFormButtonDisabled,
                validateIputEmail,
                validateInputPassword,
                validateInputPasswordConfirm,
                resetPassword,
            }
        }
    }
</script>

<style scoped>

</style>
