<template>
    <titre-application big-title="MODIFICATION CAMPAGNE OBJECTIFS" small-title="Formulaire" name-page="Campagne-objectif"></titre-application>
    <div class="container flex items-center justify-center p-8">
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
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="campagne.libelle" type="text" name="libelle"
                               id="libelle"
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
                                       @blur="validateIputplage1" v-model="campagne.plage1" type="date" name="libelle"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                                <strong v-if="Errors.plage1" class="text-red-600">{{Errors.plage1 }}</strong>

                            </div>
                            <div class="col-md-6">
                                <label class="form-label">à</label>
                                <input :class="Errors.plage2?'is-invalid':''" @keyup="validateIputplage2"
                                       @blur="validateIputplage2" v-model="campagne.plage2" type="date" name="libelle"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                                <strong v-if="Errors.plage2" class="text-red-600">{{Errors.plage2 }}</strong>
                            </div>
                        </div>
                        <br>
                    </fieldset>
                    <div class="mb-4">
                        <label for="annee" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Année<i class="text-red-600">*</i></b>
                        </label>
                        <select v-model="campagne.annee" name="" id="annee" class="rounded-md form-control">
                            <option value="" disabled>Sélectionnez l'année</option>
                            <option v-for="annee in annees" :key="annee.id" :value="annee.id" :selected="campagne.annee===annee.id">
                                {{annee.libelle}}
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <div class="block">
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input id="statut" :checked="campagne.cloturer" type="checkbox" class="w-6 h-6 text-green-600 focus:ring-0" />
                                    <span class="ml-2">Clôturer la campagne </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled"
                                :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                type="submit" class="btn-green col-md-12">
                            <b id="btn-submit">Modifier</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import TitreApplication from "../block/TitreApplication";
    import {onMounted} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useCampagneObjectif from "../../services/campagneObjectif";
    import appService from "../../services/appService";

    export default {
        name: "campagneObjectifEdit",
        components:{TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {
            const {tabError,annees,getAnnes,campagne,getCampagne,updateCampagne} = useCampagneObjectif();
            onMounted(() => getCampagne(props.id))
            onMounted(getAnnes)

            const {getDate} = appService();

            const {validateNameField, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(campagne, Errors);

            const validateIputplage1 = () => {
                validateNameField('plage1', campagne.plage1);
            }
            const validateIputplage2 = () => {
                validateNameField('plage2', campagne.plage2);
            }
            const validateInputlibelle = () => {
                validateNameField('libelle', campagne.libelle)
            }


            const editResource = async () => {
                if($('#statut').is(':checked')){
                    campagne.cloturer = true;
                }else {
                    campagne.cloturer = false;
                }
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateCampagne(props.id)
                campagne.code = '';
            }

            return {
                Errors,
                tabError,
                annees,
                isFormButtonDisabled,
                campagne,
                getDate,
                validateIputplage1,
                validateIputplage2,
                validateInputlibelle,
                editResource,
            }
        }
    }
</script>

<style scoped>

</style>