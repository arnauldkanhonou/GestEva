<template>
    <titre-application big-title="CREATION DIRECTION" small-title="Formulaire-Création" name-page="Direction"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong >{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="storeDirection">
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Code<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.code?'is-invalid':''" @keyup="validateIputcode" @blur="validateIputcode" v-model="form.code" type="text" name="code" id="code" placeholder="Code de la direction"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.code" class="text-red-600">{{ Errors.code }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle" @blur="validateInputlibelle" v-model="form.libelle"  type="text" name="libelle" id="libelle" placeholder="Libellé de la direction"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled" :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'" type="submit" class="btn-green col-md-12">
                            <b id="btn-save">Enregistrer</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {onMounted, reactive} from "vue";
    import confirmForm from "../../validation/confirmForm";
    import useFormValidation from "../../validation/useFormValidation";
    import useDirection from "../../services/directionServices";
    import useUpdateVariable from "../../services/cleanVariable";
    import TitreApplication from "../block/TitreApplication";
    import {ref} from "@vue/reactivity";
    import store from "../../store";
    import {useStore} from "vuex";

    export default {
        name: "DirectionCreate",
        components:{TitreApplication},

        setup(){
            const form = reactive({
                code:'',
                libelle:''
            })
            const store = useStore();

            const {validateNameField,validateLength,Errors} = useFormValidation();

            const {cleanVar} = useUpdateVariable(Errors,form);
            onMounted(cleanVar);

            const {isFormButtonDisabled} = confirmForm(form,Errors);

            const validateIputcode = ()=>{
                (form.code ===''?validateNameField('code',form.code):
                validateLength('code',form.code,store.state.MAX_LENGTH))
            }

            const validateInputlibelle = ()=>{
                validateNameField('libelle',form.libelle)
            }

            const {tabError,createDirection} = useDirection();
            const storeDirection = async () =>{
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await createDirection({...form})
                form.code = '';
            }

            return{
                form,
                tabError,
                Errors,
                isFormButtonDisabled,
                validateIputcode,
                validateInputlibelle,
                storeDirection
            }
        }
    }
</script>

<style scoped>

</style>
