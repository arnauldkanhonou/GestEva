<template>
    <titre-application big-title="CREATION CAMPAGNE PERFORMANCE" small-title="Formulaire" name-page="Campagne-performance"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="storeRessource">

                    <div class=" mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Type évaluation<i class="text-red-600">*</i></b>
                        </label>
                        <select v-model="form.typeEvaluation" name="" id="" class="rounded-md form-control form-select">
                            <option value="" readonly>Sélectionnez le type évaluation</option>
                            <option v-for="type in  typeevaluations" :key="type.id" :value="type.id">
                                {{type.libelle}}
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="form.libelle" type="text" name="libelle"
                               id="libelle" placeholder="Saisir l'intiutlé de la campagne"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <fieldset class="form-group border p-3 mb-4">
                        <legend class="w-auto px-2"><strong>Plage de démarrage</strong></legend>
                        <div class=" ml-2 mr-1 row">
                            <div class="col-md-6">
                                <label class="form-label">de</label>
                                <input :class="Errors.plage1?'is-invalid':''" @keyup="validateIputplage1"
                                       @blur="validateIputplage1" v-model="form.plage1" type="date" name="libelle"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                                <strong v-if="Errors.plage1" class="text-red-600">{{Errors.plage1 }}</strong>

                            </div>
                            <div class="col-md-6">
                                <label class="form-label">à</label>
                                <input :class="Errors.plage2?'is-invalid':''" @keyup="validateIputplage2"
                                       @blur="validateIputplage2" v-model="form.plage2" type="date" name="libelle"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                                <strong v-if="Errors.plage2" class="text-red-600">{{Errors.plage2 }}</strong>
                            </div>
                        </div>
                        <br>
                    </fieldset>
                    <div class="mb-4">
                        <label for="annee" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Les objectifs de quelle année veut-on évalués?<i class="text-red-600">*</i></b>
                        </label>
                        <select v-model="form.annee" name="" id="annee" class="rounded-md form-select">
                            <option value="" disabled>Sélectionnez l'année</option>
                            <option v-for="annee in annees" :key="annee.id" :value="annee.id">
                                <b>{{annee.libelle}}</b>
                            </option>
                        </select>
                    </div>


                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled"
                                :class="isFormButtonDisabled?'btn btn-default text-white':' py-2 px-20 text-white align-content-center'"
                                type="submit"  class="btn-green col-md-12">
                            <b id="btn-save">Enregistrer</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import TitreApplication from "../block/TitreApplication";
    import {reactive} from "@vue/reactivity";
    import useFormValidation from "../../validation/useFormValidation";
    import useUpdateVariable from "../../services/cleanVariable";
    import {onMounted} from "vue";
    import useSubmitButtonForm from "../../validation/confirmForm";
    import useCampagneObjectif from "../../services/campagneObjectif";
    import useCampagnePerformance from "../../services/campagnePerformance";

    export default {
        name: "campagneObjectifCreate",
        components:{TitreApplication},
        setup() {
            const form = reactive({
                typeEvaluation: '',
                annee: '',
                libelle: '',
                plage1: '',
                plage2: '',
            });

            const {Errors, validateNameField, validateLength} = useFormValidation();

            const {cleanVar} = useUpdateVariable(Errors,form);
            onMounted(cleanVar);

            const {isFormButtonDisabled} = useSubmitButtonForm(form, Errors);

            const validateIputplage1 = () => {
                validateNameField('plage1', form.plage1);
            }
            const validateIputplage2 = () => {
                validateNameField('plage2', form.plage2);
            }
            const validateInputlibelle = () => {
                validateNameField('libelle', form.libelle)
            }


            const {tabError,createCampagne} = useCampagnePerformance();
            const {annees,typeevaluations,getAnnes,getTypeEvaluations} = useCampagneObjectif();
            onMounted(getAnnes);
            onMounted(getTypeEvaluations);

            const storeRessource = async () => {
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                await createCampagne({...form})
                form.code = '';
            }

            return {
                form,
                tabError,
                Errors,
                isFormButtonDisabled,
                annees,
                typeevaluations,
                storeRessource,
                validateInputlibelle,
                validateIputplage1,
                validateIputplage2,
            }
        }
    }
</script>

<style scoped>

</style>
