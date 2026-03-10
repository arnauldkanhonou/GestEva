<template>
    <!-- component -->
    <div class="container flex items-center justify-center p-12">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <!--<div v-if="errors">
                    <div v-for="(error,index) in errors" :key="index">
                        <strong class="text-red-600">{{ error[0] }}</strong>
                    </div>
                </div>-->
                <div class="alert alert-danger" v-if="errors">
                    <strong >{{ errors }}</strong>
                </div>
                <form method="POST" @submit.prevent="customerStore">
                    <div class="mb-2">
                        <label for="name" class="mb-1 block text-base font-medium text-[#07074D]">
                            Nom du client
                        </label>
                        <input :class="Errors.name?'is-invalid':''" @keyup="validateIputName" @blur="validateIputName" v-model="form.name" type="text" name="name" id="name" placeholder="Full Name"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
<!--                        <div v-if="errors" v-for="(error,index) in errors" :key="index">-->
<!--                            <strong class="text-red-600" v-if="index==='name'">{{ error[0] }}</strong>-->
<!--                        </div>-->
                        <strong v-if="Errors.name" class="text-red-600">{{ Errors.name }}</strong>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="mb-1 block text-base font-medium text-[#07074D]">
                            Téléphone
                        </label>
                        <input :class="Errors.tel?'is-invalid':''" @keyup="validateInputTel" @blur="validateInputTel" v-model="form.tel"  type="text" name="tel" id="email" placeholder="example@domain.com"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
<!--                        <div v-if="errors" v-for="(error,index) in errors" :key="index">-->
<!--                            <strong class="text-red-600" v-if="index==='tel'">{{ error[0] }}</strong>-->
<!--                        </div>-->
                        <strong v-if="Errors.tel" class="text-red-600">{{ Errors.tel }}</strong>
                    </div>
                    <div class="mb-2">
                        <label class="mb-3 block text-base font-medium text-[#07074D]">
                            Fovour ?
                        </label>
                        <input v-model="form.is_favourite" type="checkbox" class="rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium"/>
                    </div>
                    <div>
                        <!-- <button type="button" @click="getError('name')" class="btn btn-primary mr-3">essaie</button>-->
                        <button id="btn-save" :disabled="isFormButtonDisabled" :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'" type="submit" style="background-color: #058069" class="col-md-12">
                            <b id="btn-submit">Enregistrer</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {reactive} from 'vue';
    import {ref} from 'vue';
    import useCustomers from "../../services/customerservices";
    import { useStore } from "vuex";
    import {computed} from "vue"
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";

    export default {
        setup(){
            const form = reactive({
                name:'',
                tel:'',
                is_favourite:false,
            });

            const store = useStore();

            const {Errors, validateNameField } = useFormValidation()
            const validateIputName = ()=> {
                validateNameField('name',form.name);
            };
            const validateInputTel = ()=> {
                validateNameField('tel',form.tel);
            };
            const {isFormButtonDisabled} =  confirmForm(form,Errors);

            const { createCustomer,errors } = useCustomers();
            const customerStore = async () => {
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await createCustomer({...form});
                form.name = ''
            }

             /*const hasError = async (fieldName) => {
                // await store.dispatch('hasError', fieldName);
                if (errors.value.length>0){
                    console.log('putain',errors.value)
                    return (fieldName in errors.value);
                }

            }*/
            // const hasError = computed(()=>{
            //     return (typeof errors.value['name'] !=='undefined');
            // });
            /*const getErrors = async (fieldName) => {
                await store.dispatch('getError', fieldName);
            }*/
            // const getErrors = computed( ()=>{
            //     return errors.value['name'][0]
            // })
            /*const clearError = (event) => {
                this.delete(errors.value, event.target.name);
            }*/
            return {
                form,
                Errors,
                errors,
                validateInputTel,
                validateIputName,
                confirmForm,
                isFormButtonDisabled,
                createCustomer,
                customerStore
            }
        }
    }

</script>

<style scoped>

</style>
