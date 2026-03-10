<template>
    <titre-application big-title="MODIFICATION MISSIONS" small-title="Formulaire-Modification" name-page="Mission"></titre-application>
    <div class="container flex items-center justify-center p-12">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editResource">
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="mission.libelle" type="text" name="libelle"
                               id="libelle" placeholder="saisir le libellé"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Sélectionnez l'année de la mission<i class="text-red-600">*</i></b>
                        </label>
                        <div class="col-md-12">
                            <select v-model="mission.annee_id" name="" id="annee" class="rounded-md form-select">
                                <option v-for="annee in annees" :key="annee.id" :value="annee.id">
                                    {{annee.libelle}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled"
                                :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                type="submit"  class="btn-green col-md-12">
                            <b id="btn-submit">Modifier</b>
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
    import useMission from "../../services/missionservices";
    import TitreApplication from "../block/TitreApplication";
    import useCampagneObjectif from "../../services/campagneObjectif";
    import {ref} from "@vue/reactivity";

    export default {
        name: "MissionEdit",
        components:{TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            },
            idCol: {
                required: false,
                type:String,
                default:'0',
            },
            intituleCol: {
                required: false,
                type: String,
                default: ''
            }
        },

        setup(props) {
            const anneeEdit = ref('');
            const {mission,tabError,getMission,updateMission} = useMission();
            const {annees,getAnnes} = useCampagneObjectif();
            onMounted(getAnnes);

            onMounted(() => {
                getMission(props.id);
                getAnnes();
                anneeEdit.value=mission.annnee_id;
            })

            const {validateNameField, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(mission, Errors);

            const validateInputlibelle = ()=>{
                validateNameField('libelle',mission.libelle)
            }

            const editResource = async () => {
                mission.intitule = props.intituleCol;
                mission.idCol = props.idCol;
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateMission(props.id)
                mission.libelle = '';
            }

            return {
                Errors,
                tabError,
                mission,
                annees,
                anneeEdit,
                isFormButtonDisabled,
                validateInputlibelle,
                editResource,
            }
        }
    }
</script>

<style scoped>

</style>
