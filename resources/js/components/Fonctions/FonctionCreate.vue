<template>
    <titre-application big-title="CREATION POSTE" small-title="Formulaire-Création" name-page="Poste"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="storeRessource">
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Service<i class="text-red-600">*</i></b>
                        </label>
                        <select v-model="form.service" name="" id="" class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez le service</option>
                            <option v-for="service in services" :key="service.id" :value="service.id">
                                {{service.libelle}}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Code<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.code?'is-invalid':''" @keyup="validateIputcode" @blur="validateIputcode"
                               v-model="form.code" type="text" name="code" id="code" placeholder="code de la direction"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.code" class="text-red-600">{{ Errors.code }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="form.libelle" type="text" name="libelle"
                               id="libelle" placeholder="intiutlé de la direction"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled"
                                :class="isFormButtonDisabled?'btn btn-default text-white':' py-2 px-20 text-white align-content-center'"
                                type="submit"  class="btn-green col-md-12">
                            <!--<b><i v-if="tabError!==''||form.code==='yes'" class="fas fa-circle-notch fa-spin fa-2x"></i></b>-->
                            <b id="btn-save">Enregistrer</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import useFonctions from "../../services/fonction";
    import {reactive} from "@vue/reactivity";
    import useFormValidation from "../../validation/useFormValidation";
    import useSubmitButtonForm from "../../validation/confirmForm";
    import useServices from "../../services/services";
    import {onMounted} from "vue";
    import useUpdateVariable from "../../services/cleanVariable";
    import TitreApplication from "../block/TitreApplication";
    import {useStore} from "vuex";

    export default {
        name: "FonctionCreate",
        components:{TitreApplication},
        setup() {
            const form = reactive({
                code: '',
                libelle: '',
                service: '',
            });
            const store = useStore();
            const {Errors, validateNameField, validateLength} = useFormValidation();

            const {cleanVar} = useUpdateVariable(Errors,form);
            onMounted(cleanVar);

            const {isFormButtonDisabled} = useSubmitButtonForm(form, Errors);

            const validateIputcode = () => {
                (form.code === '' ? validateNameField('code', form.code) :
                    validateLength('code', form.code, store.state.MAX_LENGTH))
            }
            const validateInputlibelle = () => {
                validateNameField('libelle', form.libelle)
            }

            const {services, getServices} = useServices();
            onMounted(getServices);
            const {tabError, createFonction} = useFonctions();

            const storeRessource = async () => {
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                await createFonction({...form})
                form.code = '';
            }

            return {
                form,
                tabError,
                Errors,
                isFormButtonDisabled,
                services,
                storeRessource,
                validateInputlibelle,
                validateIputcode,
            }
        }
    }
</script>

<style scoped>

</style>
