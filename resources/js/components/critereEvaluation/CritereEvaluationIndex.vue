<template>
    <div class="flex flex-col">
        <titre-application big-title="LISTE ELEMENT D'APPRECICATION CRITère" small-title="Formulaire" name-page="Liste"></titre-application>
        <div v-if="messages!==''" class="alert alert-success">
            <strong> {{ messages }} </strong>
        </div>
        <div>
            <div class="mb-2">
                <div class="row">
                    <div class="mt-1 col-md-2">
                        <router-link :to="{name:'cirteres.evaluation.create'}"
                                     class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white"><i
                                class="fa fa-plus"></i> Nouveau
                        </router-link>
                    </div>
                    <div class="col-md-1 offset-3">
                        <select @change="onchangeNumberpage" name="" id="nbr-pagination" class="form-select">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="75">75</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input v-model="req" @keyup="search" type="text" class="form-control"
                               placeholder="Rechercher un élement par son libelle ou code">
                    </div>
                </div>

            </div>
            <div class="py-1">
                <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                    <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                    <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                </div>
                <div v-else-if="countData.length>0" class="shadow2">
                <table class="min-w-full leading-normal table table-bordered table-striped"
                           style="background-color: #acb3be">
                        <thead>
                        <tr>
                            <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Code</b>
                            </th>
                            <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Libelle</b>
                            </th>
                            <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>cat.critère</b>
                            </th>
                            <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>cat employé</b>
                            </th>
                            <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Action</b>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="critere in critereEvaluations.data" :key="critere.id">
                            <tr>
                                <td class="px-5 py-1 border-b border-gray-200 bg-white text-md"
                                    v-text="critere.code"></td>
                                <td class="px-5 py-1 border-b border-gray-200 bg-white text-md"
                                    v-text="critere.libelle"></td>
                                <td class="px-5 py-1 border-b border-gray-200 bg-white text-md">
                                    {{critere.categorie_critere.code}}<b>({{critere.categorie_critere.libelle}})</b>
                                </td>
                                <td class="px-5 py-1 border-b border-gray-200 bg-white text-md">
                                    {{critere.categorie_employe.code}}<b>({{critere.categorie_employe.libelle}})</b>
                                </td>
                                <td class="px-3 py-1 border-b border-gray-200 bg-white text-sm">
                                    <router-link :to="{ name: 'criteres.evaluation.edit', params: { id: critere.id } }"
                                                 class="btn-green text-decoration-none px-3 py-2 text-white"><i
                                            class="fa fa-edit"></i></router-link>
                                    &nbsp;&nbsp;<button @click="getIdRessource(critere.id)"
                                                        class="bg-red-600 hover:bg-red-600 text-white px-3 py-2"
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
</template>

<script>
    import {onMounted, reactive, ref} from "vue";
    import useCritereEvaluation from "../../services/critereevaluations";
    import TitreApplication from "../block/TitreApplication";
    import Paginate from "vuejs-paginate-next"

    export default {
        name: "CritereEvaluationIndex",
        components: {TitreApplication,Paginate},
        props: {
            messages: {
                required: false,
                default: ''
            }
        },

        setup() {
            const req = ref('');
            const {loadRessource,critereEvaluations,countData,countPage,searchRessource,getRessources,paginateData, deleteCritereEvaluation, getCritereEvaluations} = useCritereEvaluation();

            let ressource = reactive({id: ''});

            const getIdRessource = (id) => {
                ressource.id = id;
            }
            const trashRessource = (id) => {
                $('#btn-confirm').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                deleteCritereEvaluation(id, 'btn-fermer')
            }
            const onchangeNumberpage = () => {
                let nbre = $('#nbr-pagination').val();
                getRessources(nbre);
            }
            const getResults = (page) => {
                let nbre = $('#nbr-pagination').val();
                paginateData(nbre,page)
            }

            const search = ()=>{
                searchRessource(req.value);
            }

            onMounted(getRessources);

            return {
                ressource,
                critereEvaluations,
                loadRessource,
                countData,
                countPage,
                req,
                getIdRessource,
                trashRessource,
                getResults,
                search,
                onchangeNumberpage
            }
        }
    }
</script>

<style scoped>

</style>
