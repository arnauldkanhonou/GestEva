<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="MES MISSIONS" small-title="Missions" name-page="Liste"></titre-application>
            <div v-if="messages!==''" class="alert alert-success">
                <strong> {{ messages }} </strong>
            </div>
            <div v-if="succesDelete!==''" class="alert alert-success">
                <strong> {{ succesDelete }} </strong>
            </div>
            <div>
                <div class="mb-2">
                    <div class="row">
                        <div class="mt-4 col-md-2">
                            <router-link :to="{name:'missions.create'}"
                                         class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                                <i class="fa fa-plus"></i> Nouveau
                            </router-link>
                            <!-- &nbsp;&nbsp;
                             <button :disabled="tabId.length===0" data-bs-toggle="modal" data-bs-target="#validationFullModal"
                                     :class="tabId.length!==0?'uppercase  text-decoration-none bg-blue-600 px-3 py-2 text-white':'uppercase text-decoration-none bg-gray-400 px-3 py-2 cursor-auto text-white'">
                                 <i v-if="tabId.length!==0" class="fa fa-check"></i> Valider
                             </button>-->
                        </div>
                        <div class="col-md-2 offset-1">
                            <label for="nbr-pagination"><b>Affichage par page</b></label>
                            <select @change="onchangeNumberpage" name="" id="nbr-pagination" class="form-select">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="75">75</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="annee"><b>Année</b></label>
                            <select @change="filtre" v-model="annee" name="" id="annee" class="rounded-md form-select">
                                <option value="" disabled>Sélectionnez l'année</option>
                                <option v-for="annee in annees" :key="annee.id" :value="annee.id">
                                    <b>{{annee.libelle}}</b>
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for=""><b>Recherche</b></label>
                            <input v-model="req" @keyup="search" type="text" class="form-control" placeholder="Rechercher une mission">
                        </div>
                    </div>
                </div>
                <div class="py-1">
                    <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                    <div v-else-if="missions.data.length>0" class="shadow2">
                        <table class="min-w-full leading-normal table table-bordered table-striped"
                               style="background-color: #acb3c4">
                            <thead>
                            <tr>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>ID</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Libelle</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Annee</b>
                                </th>
                                <th class="px-8 py-2 text-center border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Valider</b>
                                </th>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Action</b>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="mission in missions.data" :key="mission.id">
                                <tr>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md">
                                        <input disabled v-if="!mission.valider" :id="'mission'+mission.id"
                                               @click="pushTabId(mission.id)" type="checkbox"
                                               class="w-6 h-6 text-green-600 focus:ring-0"/>
                                        <strong v-if="mission.valider" class="btn-green text-white"><i
                                            class="fa fa-check"></i></strong>
                                    </td>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md"
                                        v-text="mission.libelle"></td>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md"
                                        v-text="mission.annee===null?'':mission.annee.libelle"></td>
                                    <td class="px-3 py-1 border-b text-center border-gray-200 bg-white text-md">
                                   <span class="px-2  font-semibold rounded-full"
                                         v-bind:class="mission.valider ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                         v-if="mission.valider"> Oui
                                    </span>
                                        <span class="px-2 font-semibold rounded-full"
                                              v-bind:class="mission.valider ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                              v-else> Non
                                    </span>
                                    </td>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-sm">

                                        <router-link v-if="!mission.valider" title="Modifier la mission"
                                                     :to="{ name: 'missions.edit', params: { id: mission.id } }"
                                                     class="uppercase text-decoration-none btn-green px-3 py-2 cursor-auto text-white">
                                            <i class="fa fa-edit"></i>
                                        </router-link>

                                        <button v-else disabled type="button"
                                                class="text-decoration-none cursor-auto btn-green px-3 py-2 text-white">
                                            <i class="fa fa-edit"></i></button>


                                        <!-- &nbsp;&nbsp;<button v-if="!mission.valider" title="Valider la mission"
                                                             @click="getIdRessource(mission.id,true)"
                                                             class="bg-blue-600 text-white px-3 py-2"
                                                             data-bs-toggle="modal"
                                                             data-bs-target="#validationModal"><i
                                             class="fa fa-check"></i>
                                     </button>-->
                                        &nbsp;&nbsp;<button :disabled="mission.valider" title="Supprimer la mission"
                                                            @click="getIdRessource(mission.id)"
                                                            :class="mission.valider?'uppercase text-decoration-none btn btn-danger px-3 py-2 text-white':'uppercase text-decoration-none bg-red-600 px-3 py-2 cursor-auto text-white'"
                                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    </td>

                                </tr>
                            </template>
                            </tbody>
                        </table>
                        <div class="offset-5 col-md-4">
                            <paginate :page-count="countPage"
                                      :container-class="'pagination'"
                                      :prev-text="'Préc.'"
                                      :next-text="'Suiv.'"
                                      :page-range="5"
                                      :click-handler="getResults"
                                      v-model="page"
                            >
                            </paginate>
                        </div>
                    </div>
                    <div v-else class="alert alert-info">
                        <b class="text-red-600 uppercase">Aucune ressource disponible dans la base de donnée</b>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div
                class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                    <div
                        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                        <div style="background-color: #acb3c4"
                             class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                            <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                                <b><i class="fa fa-question-circle"></i> CONFIRMATION</b>
                            </h5>
                            <!--<button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                                    data-bs-dismiss="modal" aria-label="Close"></button>-->
                        </div>
                        <div class="modal-body">
                            <b><h5>Cette action va supprimer la ressource de la base de données. </h5></b>
                            <b class="text-red-600"><h5>Êtes-vous sûr de vouloir supprimer?</h5></b>
                        </div>
                        <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                            <button id="btn-fermer" class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                    data-bs-dismiss="modal">NON
                            </button>
                            <button id="btn-confirm" @click="trashRessource(ressource.id)"
                                    class="btn-green text-white font-bold py-2 px-4">OUI
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                id="validationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                    <div
                        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                        <div style="background-color: #acb3c4"
                             class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                            <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="validationModalLabel">
                                <b><i class="fa fa-question-circle"></i> CONFIRMATION</b>
                            </h5>

                        </div>
                        <div class="modal-body">
                            <b><h5>Cette action va valider la mission sélectionné de votre collaborateur. Aucune
                                modification ne serait plus possible dans le système </h5></b>
                            <b class="text-red-600"><h5>Êtes-vous sûr de vouloir valider?</h5></b>
                        </div>
                        <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                            <button id="btn-validation-fermer" @click="decocher(ressource.id,false)"
                                    class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                    data-bs-dismiss="modal">NON
                            </button>
                            <button id="btn-valider"
                                    @click="validateRessource(ressource.id,'btn-valider','btn-validation-fermer')"
                                    class="btn-green text-white font-bold py-2 px-4">OUI
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                id="validationFullModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                    <div
                        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                        <div style="background-color: #acb3c4"
                             class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                            <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="validationFull">
                                <b><i class="fa fa-question-circle"></i> CONFIRMATION</b>
                            </h5>

                        </div>
                        <div class="modal-body">
                            <b><h5>Cette action va valider toutes les missions de votre collaborateur sélectionné .
                                Aucune
                                modification ne serait plus possible dans le système après la validation </h5></b>
                            <b class="text-red-600"><h5>Êtes-vous sûr de vouloir valider cette opération?</h5></b>
                        </div>
                        <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                            <button @click="decocher(null,true)" id="btn-validation-full-fermer"
                                    class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                    data-bs-dismiss="modal">NON
                            </button>
                            <button id="btn-valider-full"
                                    @click="validateRessource(ressource.id,'btn-valider-full','btn-validation-full-fermer')"
                                    class="btn-green text-white font-bold py-2 px-4">OUI
                            </button>
                        </div>
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
    import Paginate from "vuejs-paginate-next"
    import useCampagneObjectif from "../../services/campagneObjectif";

    export default {
        name: "MissionIndex",
        components: {TitreApplication, Paginate},
        props: {
            messages: {
                required: false,
                default: ''
            },
        },

        setup(props) {
            const {loadRessource, missions, countPage, countData, paginateData,searchRessource,deleteMission, getMissions, validateMission, filtreMission} = useMission();
            const page = ref(1);
            const req = ref('');
            let succesDelete = ref('');
            const annee = ref('');
            let ressource = reactive({id: ''});
            const tabId = ref([]);
            const {annees, getAnnes} = useCampagneObjectif();

            const getIdRessource = (id, check) => {
                if (check) {
                    $('#mission' + id).prop('checked', true)
                }
                ressource.id = id;
            }

            const decocher = (id, all) => {
                if (!all) {
                    $('#mission' + id).prop('checked', false);
                    $('#btn-validation-fermer')[0].click();
                } else {
                    let data = tabId.value;
                    for (let i = 0; i < data.length; i++) {
                        $('#mission' + data[i]).prop('checked', false);
                    }
                    tabId.value = [];
                }


            }

            const pushTabId = (id) => {
                let trouver = false;
                let indexe = '';
                let data = tabId.value;
                for (let i = 0; i < data.length; i++) {
                    if (parseInt(data[i]) === parseInt(id) && trouver === false) {
                        trouver = true;
                        indexe = i;
                        break;
                    } else {
                    }
                }

                if (trouver)
                    tabId.value.splice(indexe, 1);
                else
                    tabId.value.push(id);
            }

            const sleep = (ms) => {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            const trashRessource = (id) => {
                $('#btn-confirm').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                deleteMission(id, 'btn-fermer')
                succesDelete.value = 'Supression effectuée avec succès.'
                sleep(100).then(() => {
                    getMissions()
                })
            }
            const onchangeNumberpage = () => {
                let nbre = $('#nbr-pagination').val();
                getMissions(nbre);
            }
            const getResults = (page) => {
                let nbre = $('#nbr-pagination').val();
                paginateData(nbre, page)
            }
            const validateRessource = (id, idbtnConfirm, idbtn) => {
                $('#' + idbtnConfirm).html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                let data = '';
                if (tabId.value.length > 0) {
                    data = {'full': true, 'tabId': {...tabId.value}};
                } else
                    data = {'full': false, 'id': id}
                validateMission(data, idbtnConfirm, idbtn);
            }

            const filtre = () => {
                filtreMission(annee.value)
            }
            const search = ()=>{
                searchRessource(req.value);
            }
            onMounted(() => {
                getAnnes();
                getMissions()
            });

            return {
                page,
                tabId,
                missions,
                ressource,
                succesDelete,
                loadRessource,
                annees,
                annee,
                req,
                countPage,
                countData,
                getIdRessource,
                trashRessource,
                validateRessource,
                pushTabId,
                decocher,
                filtre,
                onchangeNumberpage,
                getResults,
                search,
            }
        }
    }
</script>

<style scoped>

</style>
