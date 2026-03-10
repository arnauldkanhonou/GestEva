<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="OBJECTIFS SALARIE" small-title="Objectifs" :name-page="intitule"></titre-application>
            <div>
                <div class="mb-2">
                    <div class="row">
                        <div class="mb-2 col-md-4">
                            <br>
                            <router-link :to="{name:'employes.index'}"
                                         class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                                <i class="fa fa-backspace"></i> Retour
                            </router-link> &nbsp;&nbsp;&nbsp;
<!--                            <button title="Télécharger" class="btn-sm bg-blue-600 text-white px-3 py-2 rounded">
                                <i class="fa fa-file-pdf"></i>&nbsp;&nbsp;EXTRAIRE
                            </button>-->
                        </div>
<!--                        <div class="col-md-2">
                            <label for=""><strong>Filtre Par affichage</strong></label>
                            <select name="" id="" class="form-select">
                                <option value="">10</option>
                                <option value="">20</option>
                                <option value="">50</option>
                                <option value="">75</option>
                                <option value="">100</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for=""><strong>Filtrer Par Années</strong></label>
                            <select name=""  class="form-select">
                                <option selected value="" disabled>Sélectionnez une annéé</option>
                                <option v-for="annee in annees" :key="annee.id" :value=annee.id>
                                    {{ annee.libelle }}
                                </option>
                            </select>
                        </div>-->
                    </div>

                </div>
                <div class="py-1">
                    <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                    <div v-else-if="objectifs.length>0" class="shadow  overflow-hidden">
                        <table class="min-w-full leading-normal table table-bordered table-striped"
                               style="background-color: #acb3c4">
                            <thead>
                            <tr>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Libelle</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Act.clées</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Résul.attendu</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Année</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>écheance</b>
                                </th>
                                <th class="px-8 py-2 text-center border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Valider</b>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="objectif in objectifs" :key="objectif.id">
                                <tr>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md"
                                        v-text="objectif.libelle"></td>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md"
                                        v-text="objectif.actionCle"></td>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md"
                                        v-text="objectif.resultatAttendu"></td>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md"
                                        v-text="objectif.annee===null?'':objectif.annee.libelle"></td>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md"
                                        v-text="objectif.echeance"></td>
                                    <td class="px-3 py-1 border-b text-center border-gray-200 bg-white text-md">
                                   <span class="px-2  font-semibold rounded-full"
                                         v-bind:class="objectif.valider ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                         v-if="objectif.valider"> Oui
                                    </span>
                                        <span class="px-2 font-semibold rounded-full"
                                              v-bind:class="objectif.valider ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                              v-else> Non
                                    </span>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>

                    </div>
                    <div v-else class="alert alert-info">
                        <b class="text-red-600 uppercase">Aucune ressource disponible dans la base de donnée</b>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {computed, onMounted, reactive} from "vue";
    import useMission from "../../services/missionservices";
    import TitreApplication from "../block/TitreApplication";
    import {ref} from "@vue/reactivity";
    import useCollaborateur from "../../services/collaborateurs";
    import useObjectifs from "../../services/objectifservices";

    export default {
        name: "ValidationObjectif",
        components: {TitreApplication},
        props: {
            messages: {
                required: false,
                default: ''
            },
            id: {
                required: true,
                type: String,
            },
            intitule: {
                required: false,
                type: String,
            }
        },

        setup(props) {
            const intitule = ref(props.intitule);
            const {succesMessage, validateObjectif} = useObjectifs();
            const {loadRessource,objectifs, getObjectifCollaborateur} = useCollaborateur();
            const {annees,getAnnees} = useCollaborateur();

            onMounted(getAnnees)

            onMounted(()=>{getObjectifCollaborateur(props.id)});

            return {
                annees,
                objectifs,
                loadRessource,
                succesMessage,
            }
        }
    }
</script>

<style scoped>

</style>
