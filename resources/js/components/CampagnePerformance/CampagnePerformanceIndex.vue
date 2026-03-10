<template>
    <div>
        <div class="flex flex-col">
            <titre-application big-title="LISTE CAMPAGNE PERFORMANCE" small-title="Campagne-performancece"
                               name-page="Liste"></titre-application>
            <div v-if="messages!==''" class="alert alert-success">
                <strong> {{ messages }} </strong>
            </div>
            <div>
                <div class="mb-2">
                    <div class="row">
                        <div class="mt-1 col-md-2">
                            <router-link :to="{name:'campagne.performance.create'}"
                                         class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                                <i class="fa fa-plus"></i> Nouveau
                            </router-link>
                        </div>
                        <div class="col-md-1 offset-3">
                            <select name="" id="" class="form-select">
                                <option value="">10</option>
                                <option value="">20</option>
                                <option value="">50</option>
                                <option value="">75</option>
                                <option value="">100</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control"
                                   placeholder="Rechercher une campagne par son libellé">
                        </div>
                    </div>
                </div>
                <div class="py-1">
                    <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                    <div v-else-if="campagnes.length>0" class="shadow  overflow-hidden">
                        <table class="min-w-full leading-normal table table-bordered table-striped"
                               style="background-color: #acb3c4">
                            <thead>
                            <tr>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Libelle</b>
                                </th>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Date début</b>
                                </th>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Date fin</b>
                                </th>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Année</b>
                                </th>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Cloturer</b>
                                </th>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Type Evaluation</b>
                                </th>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Action</b>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="campagne in campagnes" :key="campagne.id">
                                <tr>
                                    <td class="px-5 py-1 border-b border-gray-200 bg-white text-md"
                                        v-text="campagne.libelle"></td>
                                    <td class="px-5 py-1 border-b border-gray-200 bg-white text-md">
                                        {{ getDate(campagne.plage1) }}
                                    </td>
                                    <td class="px-5 py-1 border-b border-gray-200 bg-white text-md">
                                        {{ getDate(campagne.plage2) }}
                                    </td>
                                    <td class="px-5 py-1 border-b border-gray-200 bg-white text-md"
                                        v-text="campagne.annee.libelle"></td>
                                    <td class=" py-1 border-b text-center border-gray-200 bg-white text-md">
                                   <span class="px-2  font-semibold rounded-full"
                                         v-bind:class="campagne.cloturer ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                         v-if="campagne.cloturer"> Oui
                                    </span>
                                        <span class="px-2 font-semibold rounded-full"
                                              v-bind:class="campagne.cloturer ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                              v-else> Non
                                    </span>
                                    </td>
                                    <td class="px-5 py-1 border-b border-gray-200 bg-white text-md">
                                        {{campagne.type_evaluation.libelle}}
                                    </td>
                                    <td class="px-3 py-1 border-b border-gray-200 bg-white text-sm">
                                        <router-link :to="{ name: 'campagne.performance.edit', params: { id: campagne.id } }"
                                                     class="btn-green text-decoration-none px-3 py-2 text-white "><i
                                                class="fa fa-edit"></i></router-link>
                                        &nbsp;&nbsp;<button @click="getIdRessource(campagne.id)"
                                                            class="bg-red-600 hover:bg-red-600 text-white px-3 py-2 "
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
                            <b class="text-red-600"><h5>Etes-vous sûr de vouloir supprimer?</h5></b>
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
    import useFonctions from "../../services/fonction";
    import {onMounted, reactive} from "vue";
    import useCampagneObjectif from "../../services/campagneObjectif";
    import appService from "../../services/appService";
    import useCampagnePerformance from "../../services/campagnePerformance";

    export default {
        name: "campagneObjectifIndex",
        components: {TitreApplication},
        props: {
            messages: {
                required: false,
                type: String,
                default: '',
            }
        },
        setup() {
            const {loadRessource,campagnes, getCampagnes, deleteCampagne} = useCampagnePerformance();

            const { getDate } = appService();

            let ressource = reactive({id: ''});

            const getIdRessource = (id) => {
                ressource.id = id;
            }
            const trashRessource = (id) => {
                $('#btn-confirm').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                deleteCampagne(id, 'btn-fermer')
            }

            onMounted(getCampagnes);


            return {
                campagnes,
                ressource,
                loadRessource,
                getDate,
                getIdRessource,
                trashRessource,
            }
        }
    }
</script>

<style scoped>

</style>
