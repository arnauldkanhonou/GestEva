<template>
    <div>
        <titre-application big-title="LISTE SALARIES" small-title="Salariés" name-page="Liste"></titre-application>
        <div v-if="messages!==''" class="alert alert-success">
            <strong> {{ messages }} </strong>
        </div>
        <div>
            <div class="mb-2">
                <!--style="background-color: #058069"-->
                <div class="row">
                    <div class="mt-1 col-md-2">
                        <router-link :to="{name:'employes.create'}"
                                     class="uppercase btn-green text-decoration-none px-3 py-2 text-white">
                            <i class="fa fa-plus"></i> Nouveau
                        </router-link>
                    </div>
                    <div class="col-md-1">
                        <select @change="onchangeNumberpage" name="" id="nbr-pagination" class="form-select">
                            <option selected value="20">25</option>
                            <option value="50">50</option>
                            <option value="45">75</option>
                            <option value="100">100</option>
                            <option value="300">300</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select v-model="service_id" @change="onchangeService" id="service" class="rounded-md form-select">
                            <option value="" readonly>Sélectionnez le service</option>
                            <option  v-for="service in services" :key="service.id" :value="service.id">
                                {{service.libelle}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input v-model="req" type="text" class="form-control" @keyup="search"
                               placeholder="Rechercher un employé par son nom/prénom ou code">
                    </div>
                </div>

            </div>
            <div class="py-1"><!--#556e6e-->
                <div v-if="countData.length===0" class="col-md-2 offset-5 mt-4">
                    <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                    <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                </div>
                <div v-else class="shadow ">
                    <table class="min-w-full leading-normal table table-bordered table-striped"
                           style="background-color:#acb3c4 ">
                        <thead>
                        <tr>
                            <th class="px-2 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Matr.</b>
                            </th>
                            <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Nom</b>
                            </th>
                            <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Prénoms</b>
                            </th>
                            <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Email</b>
                            </th>
                            <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Service</b>
                            </th>
                            <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Poste</b>
                            </th>
                            <!-- <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                 <b class="text-white">date Emb.</b>
                             </th>-->
                            <th class="px-3 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Resp.U/Eq</b>
                            </th>
                            <th class="px-3 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Resp.S</b>
                            </th>
<!--                            <th class="px-2 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">-->
<!--                                <b>Resp.D</b>-->
<!--                            </th>-->
<!--                            <th class="px-2 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">-->
<!--                                <b>Est Dir.</b>-->
<!--                            </th>-->
                            <th class="px-3 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Action</b>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="employe in employes.data" :key="employe.id">
                            <tr>
                                <td class="py-1 border-b text-center border-gray-200 bg-white text-md font-bold"
                                    v-text="employe.matricule"></td>
                                <td class="py-1 border-b border-gray-200 bg-white text-md">
                                    {{ employe.nom }}
                                </td>
                                <td class="py-1 border-b border-gray-200 bg-white text-md">
                                    {{ employe.prenoms }}
                                </td>
                                <td class="py-1 border-b border-gray-200 bg-white text-md">
                                    {{ employe.email }}
                                </td>
                                <td class="py-1 border-b border-gray-200 bg-white text-md">
                                     {{ employe.service }}
                                 </td>
                                <td class="py-1 border-b border-gray-200 bg-white text-md">
                                     {{ employe.poste }}
                                 </td>
                                <td class="py-1 border-b text-center border-gray-200 bg-white text-md">
                                    <span class="px-2  font-semibold rounded-full"
                                          v-bind:class="employe.respUnite ==='1' ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                          v-if="employe.respUnite"> Oui
                                    </span>
                                    <span class="px-2  font-semibold rounded-full"
                                          v-bind:class="employe.respUnite ==='1' ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                          v-else> Non
                                    </span>
                                </td>
                                <td class=" py-1 border-b text-center border-gray-200 bg-white text-md">
                                   <span class="px-2  font-semibold rounded-full"
                                         v-bind:class="employe.respService ==='1' ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                         v-if="employe.respService">Oui
                                    </span>
                                    <span class="px-2  font-semibold rounded-full"
                                          v-bind:class="employe.respService ==='1' ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                          v-else> Non
                                    </span>
                                </td>
<!--                                <td class=" py-1 border-b text-center border-gray-200 bg-white text-md">-->
<!--                                    <span class="px-2  font-semibold rounded-full"-->
<!--                                          v-bind:class="employe.respDepartement ==='1' ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"-->
<!--                                          v-if="employe.respDepartement"> Oui-->
<!--                                    </span>-->
<!--                                    <span class="px-2 font-semibold rounded-full"-->
<!--                                          v-bind:class="employe.respDepartement ==='1' ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"-->
<!--                                          v-else> Non-->
<!--                                    </span>-->
<!--                                </td>-->
<!--                                <td class=" py-1 border-b text-center border-gray-200 bg-white text-md">-->
<!--                                    <span class="px-2  font-semibold rounded-full"-->
<!--                                          v-bind:class="employe.isDirecteur ==='1' ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"-->
<!--                                          v-if="employe.isDirecteur"> Oui-->
<!--                                    </span>-->
<!--                                    <span class="px-2 font-semibold rounded-full"-->
<!--                                          v-bind:class="employe.isDirecteur ==='1' ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"-->
<!--                                          v-else> Non-->
<!--                                    </span>-->
<!--                                </td>-->

                                <td class="py-1 px-3 border-b border-gray-200 bg-white text-sm">
                                    <div class="btn-group inline-flex">
                                        <button
                                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-3 rounded-l dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            ACTION
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <router-link :to="{ name: 'employes.edit', params: { id: employe.id } }"
                                                             class="dropdown-item"><i class="fa fa-user-edit"></i>
                                                    Modifier
                                                </router-link>
                                            </li>
<!--                                            <li><a class="dropdown-item" href="#"><i class="fa fa-eye-slash"></i>
                                                Apperçu</a>
                                            </li>-->
                                            <li>
                                                <router-link
                                                    :to="{ name: 'missions.salarie.rh', params: { id: employe.id, intitule:employe.nom+' '+employe.prenoms  } }"
                                                    class="dropdown-item"><i class="fa fa-user-edit"></i> Missions
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link
                                                    :to="{ name: 'objectifs.salarie.rh', params: { id: employe.id, intitule:employe.nom+' '+employe.prenoms  } }"
                                                    class="dropdown-item"><i class="fa fa-user-edit"></i> Objectifs
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link
                                                    :to="{ name: 'evaluations.salarie.rh', params: { id: employe.id, intitule:employe.nom+' '+employe.prenoms  } }"
                                                    class="dropdown-item"><i class="fa fa-user-edit"></i> Evaluations
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link
                                                    :to="{ name: 'evaluations.formations.rh', params: { id: employe.id, intitule:employe.nom+' '+employe.prenoms  } }"
                                                    class="dropdown-item"><i class="fa fa-user-edit"></i> Formations
                                                </router-link>
                                            </li>
                                            <li><a @click="getIdRessource(employe.id)" class="dropdown-item" href="#"
                                                   data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i
                                                class="fa fa-trash-alt"></i> Supprimer</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                  <div class="offset-5 col-md-4">
                      <paginate style="cursor: pointer" :page-count="countPage"
                                :container-class="'pagination'"
                                :prev-text="'Préc.'"
                                :next-text="'Suiv.'"
                                :page-range="5"
                                :click-handler="getResults"
                                v-model="page"
                      >
                      </paginate>
                  </div>
                    <!--<Bootstrap5Pagination :data="employes" align="center" @pagination-change-page="getResults">
                    </Bootstrap5Pagination>-->
                    <br>

                    <!-- <Pagination  v-model="page" :records="employes.length" :per-page="3"></Pagination>-->

                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
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
</template>

<script>
    import {ref, onMounted, reactive} from "vue";
    import useEmploye from "../../services/employeservice";
    import DirectionCreate from "../directions/DirectionCreate";
    import TitreApplication from "../block/TitreApplication";
    import NavbarApp from "../block/NavbarApp";
    import MenuApp from "../block/MenuApp";
    import Paginate from "vuejs-paginate-next"
    import {Bootstrap5Pagination} from 'laravel-vue-pagination';
    import useServices from "../../services/services";
    //import { range } from "lodash";
    export default {
        name: "EmployeIndex",
        components: {TitreApplication,Paginate, Bootstrap5Pagination, NavbarApp, MenuApp},
        props: {
            messages: {
                required: false,
                type: String,
                default: '',
            }
        },
        setup() {
            const page = ref(1);
            const req = ref('');
            const service_id = ref('');
            const clickCallback = (pageNum) => {
                alert(pageNum)
            }
            const {employes, countData,countPage, searchRessource,
                paginateData, getEmployes, deleteEmploye,getEmployeByService} = useEmploye();

            const {services, getServices} = useServices();
            let ressource = reactive({id: ''});

            const getIdRessource = (id) => {
                ressource.id = id;
            }
            const trashRessource = (id) => {
                $('#btn-confirm').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                deleteEmploye(id, 'btn-fermer')
            }
            const onchangeNumberpage = () => {
                let nbre = $('#nbr-pagination').val();
                getEmployes(nbre);
            }

            const onchangeService = () => {
                let nbre = $('#nbr-pagination').val();
                getEmployeByService(service_id.value,nbre);
            }
            const getResults = (page) => {
                let nbre = $('#nbr-pagination').val();
                paginateData(nbre,page)
            }

            const search = ()=>{
                searchRessource(req.value);
            }
            onMounted(getEmployes);
            onMounted(getServices);

            return {
                page,
                service_id,
                employes,
                services,
                ressource,
                countData,
                countPage,
                req,
                clickCallback,
                getIdRessource,
                trashRessource,
                getResults,
                onchangeNumberpage,
                search,
                onchangeService,
            }
        }
    }
</script>

<style scoped>
.pagination >li{
    background-color: green;
}
</style>
