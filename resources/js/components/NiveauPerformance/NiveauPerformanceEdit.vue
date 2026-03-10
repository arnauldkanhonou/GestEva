<template>
    <titre-application big-title="MODIFICATION PERFORMANCE-EVALUATION" small-title="Formulaire" name-page="Performance"></titre-application>
    <div class="container flex items-center justify-center p-12">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editResource">
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle" @blur="validateInputlibelle" v-model="niveauPerformance.libelle"  type="text" name="libelle" id="libelle" placeholder="saisir le libelle"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Borne Inférieur<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.borneInf?'is-invalid':''" @keyup="validateInputbornInf" @blur="validateInputbornInf" v-model="niveauPerformance.borneInf"  type="number" name="libelle" id="bornInf" placeholder="saisir l'échelle de point"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.borneInf" class="text-red-600">{{Errors.borneInf }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Borne Supérieur<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.borneSup?'is-invalid':''" @keyup="validateInputbornSup" @blur="validateInputbornSup" v-model="niveauPerformance.borneSup"  type="number" name="libelle" id="bornSup" placeholder="saisir l'échelle de point"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.borneSup" class="text-red-600">{{Errors.borneSup }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Appréciation<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.appreciation?'is-invalid':''" @keyup="validateInputAppreciation" @blur="validateInputAppreciation" v-model="niveauPerformance.appreciation" type="text" name="code" id="code" placeholder="saisir l'appréciation"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.appreciation" class="text-red-600">{{ Errors.appreciation }}</strong>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled" :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'" type="submit"  class="btn-green col-md-12">
                            <b>Modifier</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>

<script>
    import {onMounted} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useNiveauPerformance from "../../services/niveauPerformance";
    import TitreApplication from "../block/TitreApplication";
    import useUpdateVariable from "../../services/cleanVariable";

    export default {
        name: "NiveauPerformanceEdit",
        components:{TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {

            const {niveauPerformance,tabError,getNiveauPerformance,updateNiveauPerformance} = useNiveauPerformance();
            onMounted(() => getNiveauPerformance(props.id))

            const {validateNameField, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(niveauPerformance, Errors);

            const {cleanVar} = useUpdateVariable(Errors,niveauPerformance);
            onMounted(cleanVar);

            const validateInputlibelle = ()=>{
                validateNameField('libelle',niveauPerformance.libelle)
            }
            const validateInputbornInf = ()=>{
                validateNameField('borneInf',niveauPerformance.borneInf)
            }
            const validateInputbornSup = ()=>{
                validateNameField('borneSup',niveauPerformance.borneSup)
            }
            const validateInputAppreciation = ()=>{
                validateNameField('appreciation',niveauPerformance.appreciation)
            }

            const editResource = async () => {
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateNiveauPerformance(props.id)
                niveauPerformance.appreciation = '';
            }

            return {
                Errors,
                tabError,
                niveauPerformance,
                isFormButtonDisabled,
                validateInputlibelle,
                editResource,
                validateInputbornInf,
                validateInputbornSup,
                validateInputAppreciation
            }
        }
    }
</script>

<style scoped>

</style>