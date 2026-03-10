<template>
    <titre-application big-title="CREATION NIVEAU-EXECUTION-OBJECTIF" small-title="Formulaire" name-page="Niveau-Objectif"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="storeRessource">
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="form.libelle" type="text" name="libelle"
                               id="libelle" placeholder="saisir le libelle"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="appreciation" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Appréciation<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputappreciation"
                               @blur="validateInputappreciation" v-model="form.appreciation" type="text" name="appréciation"
                               id="appreciation" placeholder="saisir l'appreciation"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.appreciation" class="text-red-600">{{Errors.appreciation }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Point<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.point?'is-invalid':''" @keyup="validateInputpoint"
                               @blur="validateInputpoint" v-model="form.point" type="number" name="code" id="code"
                               placeholder="saisir le point correspondant"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.point" class="text-red-600">{{ Errors.point }}</strong>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled"
                                :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                type="submit" class="btn-green col-md-12">
                            <!--<b><i v-if="tabError!==''||form.code==='yes'" class="fas fa-circle-notch fa-spin fa-2x"></i></b>-->
                            <b>Enregistrer</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {onMounted, reactive} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import usePonderationCritere from "../../services/ponderationcritere";
    import useNiveauExecutionObjectifs from "../../services/niveauExecutionObjectifs";
    import useUpdateVariable from "../../services/cleanVariable";
    import TitreApplication from "../block/TitreApplication";

    export default {
        name: "NiveauObjectifCreate",
        components:{TitreApplication},
        setup() {
            const form = reactive({
                point: '',
                libelle: '',
                appreciation: '',
            })
            const {validateNameField, Errors} = useFormValidation();

            const {cleanVar} = useUpdateVariable(Errors,form);
            onMounted(cleanVar);

            const {isFormButtonDisabled} = confirmForm(form, Errors);

            const {tabError, createNiveauObjectif} = useNiveauExecutionObjectifs();

            const validateInputlibelle = () => {
                validateNameField('libelle', form.libelle)
            }

            const validateInputpoint = () => {
                validateNameField('point', form.point)
            }
            const validateInputappreciation = () => {
                validateNameField('appreciation', form.appreciation)
            }


            const storeRessource = async () => {
                await createNiveauObjectif({...form})
                form.libelle = '';
            }

            return {
                form,
                tabError,
                Errors,
                isFormButtonDisabled,
                validateInputpoint,
                validateInputlibelle,
                validateInputappreciation,
                storeRessource,
            }
        }
    }
</script>

<style scoped>

</style>
