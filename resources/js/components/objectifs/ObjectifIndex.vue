<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="MES OBJECTIFS" small-title="Objectifs" name-page="Liste"></titre-application>
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
                            <router-link :to="{name:'objectifs.create'}"
                                         class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                                <i class="fa fa-plus"></i> Nouveau
                            </router-link>
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
                            <label for=""><b>Année</b></label>
                            <select @change="filtre" v-model="annee" class="rounded-md form-select">
                                <option value="">Sélectionnez l'année</option>
                                <option v-for="annee in annees" :key="annee.id" :value="annee.id">
                                    <b>{{ annee.libelle }}</b>
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for=""><b>Recherche</b></label>
                            <input v-model="req" @keyup="search" type="text" class="form-control"
                                   placeholder="Rechercher un objectif">
                        </div>
                    </div>

                </div>
                <div class="py-1">
                    <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                    <div v-else-if="objectifs.length>0" class="shadow2">
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
                                    <b>Echeance</b>
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
                            <template v-for="objectif in objectifs" :key="objectif.id">
                                <tr>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-md"
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
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-sm">
                                        <router-link v-if="!objectif.valider || verify_camapgne(objectif.annee.libelle)"
                                                     :to="{ name: 'objectifs.edit', params: { id: objectif.id } }"
                                                     class="btn-green text-decoration-none px-3 py-2 text-white"><i
                                            class="fa fa-edit"></i></router-link>
                                        <button v-else disabled type="button"
                                                class="text-decoration-none cursor-auto btn-green px-3 py-2 text-white">
                                            <i class="fa fa-edit"></i></button>

                                        &nbsp;&nbsp;<button :disabled="objectif.valider && !verify_camapgne(objectif.annee.libelle)"
                                                            @click="getIdRessource(objectif.id)"
                                                            :class="objectif.valider && !verify_camapgne(objectif.annee.libelle)?'uppercase text-decoration-none btn btn-danger px-3 py-2 text-white':'uppercase text-decoration-none bg-red-600 px-3 py-2 cursor-auto text-white'"
                                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i
                                        class="fa fa-trash"></i></button>
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
                                    class="bg-red-600 hover:bg-red-600 text-white font-bold py-2 px-4">OUI
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
import TitreApplication from "../block/TitreApplication";
import useObjectifs from "../../services/objectifservices";
import {reactive, ref} from "@vue/reactivity";
import Paginate from "vuejs-paginate-next"
import {onMounted} from "@vue/runtime-core";
import useCampagneObjectif from "../../services/campagneObjectif";

export default {
    name: "ObjectifIndex",
    components: {TitreApplication, Paginate},
    props: {
        messages: {
            required: false,
            default: ''
        }
    },

    setup() {
        const {
            loadRessource,
            objectifs,
            countPage,
            paginateData,
            deleteObjectif,
            searchRessource,
            getObjectifs,
            filtreObjectif,
            have_campagne,
            annee_sys,
        } = useObjectifs();
        let succesDelete = ref('');
        let annee = ref('');
        const page = ref(1);
        const req = ref('');
        const {annees, getAnnes} = useCampagneObjectif();
        onMounted(getAnnes);

        let ressource = reactive({id: ''});

        const getIdRessource = (id) => {
            ressource.id = id;
        }
        const sleep = (ms) => {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        const trashRessource = (id) => {
            $('#btn-confirm').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
            succesDelete.value = 'Supression effectuée avec succès.'
            deleteObjectif(id, 'btn-fermer')
            sleep(100).then(() => {
                getObjectifs()
            })
        }
        const onchangeNumberpage = () => {
            let nbre = $('#nbr-pagination').val();
            getObjectifs(nbre);
        }
        const getResults = (page) => {
            let nbre = $('#nbr-pagination').val();
            paginateData(nbre, page)
        }

        const filtre = () => {
            filtreObjectif(annee.value)
        }
        const getType = (item) => {
            console.log(item);
            alert(typeof item)
        }
        const search = () => {
            searchRessource(req.value);
        }

        const verify_camapgne = (annee) => {
            if (annee === annee_sys.value && have_campagne.value) {
                return true;
            }
            return false;
        }

        onMounted(getObjectifs);

        return {
            objectifs,
            ressource,
            annees,
            succesDelete,
            loadRessource,
            annee,
            countPage,
            page,
            req,
            have_campagne,
            annee_sys,
            getIdRessource,
            trashRessource,
            filtre,
            onchangeNumberpage,
            getResults,
            paginateData,
            search,
            getType,
            verify_camapgne,
        }
    }
}
</script>

<style scoped>

</style>
