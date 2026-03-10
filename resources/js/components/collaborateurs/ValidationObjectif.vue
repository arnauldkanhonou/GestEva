<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="OBJECTIFS COLLABORATEURS" small-title="Objectifs" :name-page="intitule"></titre-application>
            <div v-if="succesMessage!==''" class="alert alert-success">
                <strong> {{ succesMessage }} </strong>
            </div>
            <div v-if="messages!==''" class="alert alert-success">
                <strong> {{ messages }} </strong>
            </div>
            <div>
                <div class="mb-2">
                    <div class="row">
                        <div class="mt-1 col-md-4">
                            <br>
                            <router-link :to="{name:'collaborateurs.index',params: { idColab: 0}}"
                                         class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                                <i class="fa fa-backspace"></i> Retour
                            </router-link>
                            &nbsp;&nbsp;
                            <router-link :to="{name:'objectifs.create',params: {idCol: id,intituleCol: intitule}}"
                                         class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                                <i class="fa fa-plus"></i> AJOUTER
                            </router-link>
                            &nbsp;&nbsp;
                            <button :disabled="tabId.length===0" data-bs-toggle="modal" data-bs-target="#validationFullModal"
                                    :class="tabId.length!==0?'uppercase  text-decoration-none bg-blue-600 px-3 py-2 text-white':'uppercase text-decoration-none bg-gray-400 px-3 py-2 cursor-auto text-white'">
                                <i v-if="tabId.length!==0" class="fa fa-check"></i> Valider
                            </button>
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
                        <!--<div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Rechercher une mission">
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
                                    <b>ID</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Libelle</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Action clé</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Resulat attendu</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Echéance</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Année</b>
                                </th>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Action</b>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="objectif in objectifs" :key="objectif.id">
                                <tr>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md">
                                        <input v-if="!objectif.valider" :id="'mission'+objectif.id"
                                               @click="pushTabId(objectif.id)" type="checkbox"
                                               class="w-6 h-6 text-green-600 focus:ring-0"/>
                                        <strong v-if="objectif.valider" class="btn-green text-white"><i
                                                class="fa fa-check"></i></strong>
                                    </td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="objectif.libelle"></td>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md">
                                       <span v-if="objectif.isjson">
                                            <span v-for="(item,index) in JSON.parse(objectif.actionCle)" :key="index">
                                                {{ index + 1 }}&nbsp;-&nbsp;&nbsp;{{ item }}
                                             <br>
                                            </span>
                                       </span>
                                        <span v-else>
                                           {{objectif.actionCle}}
                                       </span>
                                    </td>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md">
                                      <span v-if="objectif.isjson">
                                            <span v-for="(item,index) in JSON.parse(objectif.resultatAttendu)" :key="index">
                                                {{ index + 1 }}&nbsp;-&nbsp;&nbsp;{{ item }}
                                             <br>
                                            </span>
                                       </span>
                                        <span v-else>
                                           {{objectif.resultatAttendu}}
                                       </span>
                                    </td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="objectif.echeance"></td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="objectif.annee.libelle"></td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-sm">
                                        <router-link v-if="!objectif.valider"
                                                     :to="{ name: 'objectifs.edit', params: { id: objectif.id, idCol: objectif.employe_id } }"
                                                     class="btn-green text-decoration-none px-3 py-2 text-white"><i
                                                class="fa fa-edit"></i></router-link>
                                        <button v-else disabled type="button"
                                                class="text-decoration-none cursor-auto btn-green px-3 py-2 text-white">
                                            <i class="fa fa-edit"></i></button>
                                        &nbsp;&nbsp;<button v-if="!objectif.valider" title="Valider l'objectif"
                                                            @click="getIdRessource(objectif.id,true)"
                                                            class="bg-blue-600 text-white px-3 py-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#validationModal"><i
                                            class="fa fa-check"></i>
                                         </button>
                                        &nbsp;&nbsp;<button :disabled="objectif.valider" @click="getIdRessource(objectif.id)"
                                                            :class="objectif.valider?'uppercase text-decoration-none btn btn-danger px-3 py-2 text-white':'uppercase text-decoration-none bg-red-600 px-3 py-2 cursor-auto text-white'"
                                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i
                                        class="fa fa-trash"></i></button>

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

            <!-- Modal -->
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                 id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                 aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                        <div style="background-color: #acb3c4"
                             class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                            <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                                <b><i class="fa fa-question-circle"></i> CONFIRMATION</b>
                            </h5>
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
                                    class="bg-red-600 hover:bg-red-600 text-white font-bold py-2 px-4">OUI
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                 id="validationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                 aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                        <div style="background-color: #acb3c4"
                             class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                            <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="validationModalLabel">
                                <b><i class="fa fa-question-circle"></i> CONFIRMATION</b>
                            </h5>

                        </div>
                        <div class="modal-body">
                            <b><h5>Cette action va valider l'objectif sélectionné de votre collaborateur. Aucune
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

            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                 id="validationFullModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                 aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                        <div style="background-color: #acb3c4"
                             class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                            <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="validationFull">
                                <b><i class="fa fa-question-circle"></i> CONFIRMATION</b>
                            </h5>

                        </div>
                        <div class="modal-body">
                            <b><h5>Cette action va valider tous les objectifs de votre collaborateur sélectionné .
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
            const {succesMessage, deleteObjectif,validateObjectif} = useObjectifs();
            const {loadRessource,objectifs, getObjectifCollaborateur} = useCollaborateur();
            const {annees,getAnnees} = useCollaborateur();
            const sleep = (ms) => {
                return new Promise(resolve => setTimeout(resolve, ms));
            }
            onMounted(getAnnees)
            let ressource = reactive({id: ''});
            const tabId = ref([]);

            const getIdRessource = (id, check) => {
                if (check) {
                    $('#mission' + id).prop('checked', true)
                }
                ressource.id = id;
            }

            const trashRessource = (id) => {
                $('#btn-confirm').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                deleteObjectif(id, 'btn-fermer')
                sleep(100).then(() => {
                    getObjectifCollaborateur(props.id)
                })
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

            let idCollaborateur = props.id;
            const validateRessource = (id, idbtnConfirm, idbtn) => {
                $('#' + idbtnConfirm).html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                let data = '';
                if (tabId.value.length > 0) {
                    data = {'full': true, 'tabId': {...tabId.value}};
                } else
                    data = {'full': false, 'id': id}
                validateObjectif(data, idbtnConfirm, idbtn,idCollaborateur,intitule);
            }

            onMounted(()=>{getObjectifCollaborateur(props.id)});

            return {
                tabId,
                annees,
                objectifs,
                succesMessage,
                ressource,
                loadRessource,
                getIdRessource,
                validateRessource,
                pushTabId,
                decocher,
                trashRessource,
            }
        }
    }
</script>

<style scoped>

</style>
