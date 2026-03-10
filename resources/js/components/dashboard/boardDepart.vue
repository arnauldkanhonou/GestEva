<template>
    <div>
        <titre-application big-title="DASHBOARD" small-title="Dashboard" name-page="RESP.D"></titre-application>
        <div v-if="messages!==''" class="alert alert-success">
            <strong> {{ messages }} </strong>
        </div>
        <div style="margin-top: -18px" class="row">
            <div class="offset-9 col-md-3">
                <label class="uppercase block text-base font-medium text-[#07074D]">
                    <b>Campagne performance<i class="text-red-600">:</i></b>
                </label>
                <select @change="loardDataOnBoard(false)" v-model="form.annee" class="rounded-md form-select">
                    <option v-for="annee in annees" :value="annee.id" :key="annee.id">
                        {{annee.libelle}}
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner"><h3 v-if="load"><i class="fas fa-circle-notch fa-spin"></i></h3>
                        <h3 class="text-red-600" v-else>{{form.nbrEmploye}}</h3>
                        <p><b class="text-black">COLLABORATEURS</b></p></div>
                    <div class="icon"><i class="ion ion-pie-graph"></i></div>
                    <a href="#" class="small-box-footer"><i class="fas fa-arrow"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-green-600">
                    <div class="inner"><h3 v-if="load"><i class="fas fa-circle-notch fa-spin"></i></h3>
                        <h3 class="text-red-600" v-else>{{form.salarierEvaluer}}/{{form.nbrEmploye}}</h3>
                        <p><b class="uppercase">COLLABORATEURS évalués</b></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fas fa-arrow"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box btn-green">
                    <div class="inner"><h3 v-if="load"><i class="fas fa-circle-notch fa-spin"></i></h3>
                        <h3 class="text-red-600" v-else>{{form.performance}}/72</h3>
                        <p><b class="uppercase">Performance du bloc</b></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fas fa-arrow"></i></a>
                </div>
            </div>
        </div>
        <hr>
<!--        <div class="col-md-12">
            <h5><strong>RECHERCHER UN COLLABORATEURS</strong></h5>
            <input v-model="searcVal" @keyup="searchEmploye" type="text" class="form-control" placeholder="Rechercher un employé par son code">
            <div v-if="loadSearch" class="col-md-2 offset-4 mt-2">
                <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
            </div>
            <div v-else>
                <br>
                <table class="table table-bordered table-striped">
                    <thead class="bg-gray-600">
                    <tr>
                        <td class="text-white uppercase"><strong>Noms & Prénoms</strong></td>
                        <td class="text-white uppercase"><strong>Email</strong></td>
                        <td class="text-white uppercase"><strong>DATE EMBAUCHE</strong></td>
                        <td class="text-white uppercase"><strong>Actions</strong></td>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="collaborateur in employes" :key="collaborateur.id">
                        <tr>
                            <td class="py-1 border-b border-gray-200 bg-white text-md">
                                {{ collaborateur.nom }}  {{ collaborateur.prenoms }}
                            </td>
                            <td class="py-1 border-b border-gray-200 bg-white text-md">
                                {{ collaborateur.email }}
                            </td>
                            <td class="py-1 border-b border-gray-200 bg-white text-md">
                                {{ collaborateur.dateEmbauche }}
                            </td>
                            <td class="py-1 px-3 border-b border-gray-200 bg-white text-sm">
                                <div class="btn-group inline-flex">
                                    <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-3 rounded-l dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        ACTION
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><router-link :to="{ name: 'collaborateur.mission', params: { id: collaborateur.id, intitule:collaborateur.nom+' '+collaborateur.prenoms  } }" class="dropdown-item" > <i class="fa fa-user-edit"></i> Missions</router-link></li>
                                        <li><router-link :to="{ name: 'collaborateur.objectifs', params: { id: collaborateur.id, intitule:collaborateur.nom+' '+collaborateur.prenoms  } }" class="dropdown-item" > <i class="fa fa-user-edit"></i> Objectifs</router-link></li>
                                        <li><router-link :to="{ name: 'collaborateur.evaluations', params: { id: collaborateur.id, intitule:collaborateur.nom+' '+collaborateur.prenoms  } }" class="dropdown-item" > <i class="fa fa-user-edit"></i> Evaluations</router-link></li>
                                        <li><a  class="dropdown-item" href="#"><i class="fa fa-eye-slash"></i> Visualiser</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <h6><b>COURBE EVOLUTIVE DE VOS PERFORMANCE ANNUELLE</b></h6>
                </div>
                <br>
                <LineChart :chartData="testData"/>
            </div>
            <div class="col-md-6">
                <div class="alert alert-info">
                    <h6><b>COURBE EVOLUTIVE DES PERFORMANCES ANNUELLE DU DEPARTEMENT</b></h6>
                </div>
                <select name="" id="" class="form-select">
                    <option value="">Ressources humaines</option>
                    <option value="">20</option>
                    <option value="">50</option>
                    <option value="">75</option>
                    <option value="">100</option>
                </select>
                <LineChart :chartData="testData"/>
            </div>
        </div>-->
    </div>
</template>

<script>
    import {onMounted, reactive, ref} from "vue";
    import {DoughnutChart, LineChart, BarChart} from 'vue-chart-3';
    import {Chart, registerables} from "chart.js";
    import TitreApplication from "../block/TitreApplication";
    import axios from "axios";
    import appService from "../../services/appService";
    import useCampagneObjectif from "../../services/campagneObjectif";
    import Swal from "sweetalert2";

    Chart.register(...registerables);
    export default {
        name: "boardAdmin",
        components: {LineChart, TitreApplication},
        props: {
            messages: {
                required: false,
                type: String,
                default: '',
            }
        },
        setup() {
           /* onMounted(() => {
                load.value = true;
                let form = reactive({
                    nbrService: 0,
                    nbrEvaluations: 0,
                    nbrEmploye: 0,
                    salarierEvaluer: 0,
                    performance: 0,
                });

                let searcVal = ref('');
                let load = ref(false);
                let loadSearch = ref(false);
                let succesMessage = ref('');
                let employes = ref('');
                const {catchErrors} = appService();
                const {annees, getAnnes} = useCampagneObjectif();
                onMounted(getAnnes);
                const testData = {
                    labels: ['0', '2021', '2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033',],
                    datasets: [
                        {
                            data: [0, 30, 40, 60, 70, 95, 83, 55, 53],
                            backgroundColor: ['#77CEFF', '#0079AF', '#123E6B', '#97B0C4', '#A5C8ED'],
                        },
                    ],
                };

                const searchEmploye = ()=>{
                    if (searcVal.value.length>=3){
                        loadSearch.value = true;
                        axios.get('search/employe/'+searcVal.value)
                            .then((response)=>{
                                employes.value = response.data.data.liste;
                                loadSearch.value = false;
                            })
                            .catch((error)=>{
                                catchErrors(error,succesMessage);
                            })
                    }

                }
                axios.get('statistique/number')
                    .then((response) => {
                        form.nbrEmploye = response.data.data.employes;
                        form.nbrEvaluations = response.data.data.evaluations;
                        form.nbrService = response.data.data.services;
                        form.salarierEvaluer = response.data.data.salarierEvaluer;
                        form.performance = Math.round(response.data.data.performance*100)/100;
                        load.value = false;
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            })
           */

            onMounted(() => {
                loardDataOnBoard(true)
            })
            let form = reactive({
                nbrService: 0,
                nbrUnite: 0,
                nbrdepartement: 0,
                nbrEmploye: 0,
                salarierEvaluer: 0,
                serviceEvaluer: 0,
                performance: 0,
                annee: 0,
            })
            let searcVal = ref('');
            let load = ref(false);
            let loadSearch = ref(false);
            let succesMessage = ref('');
            let employes = ref('');
            const {catchErrors} = appService();

            const testData = {
                labels: ['0', '2021', '2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033',],
                datasets: [
                    {
                        data: [0, 30, 40, 60, 70, 95, 83, 55, 53],
                        backgroundColor: ['#77CEFF', '#0079AF', '#123E6B', '#97B0C4', '#A5C8ED'],
                    },
                ],
            };
            const searchEmploye = ()=>{
                if (searcVal.value.length>=3){
                    loadSearch.value = true;
                    axios.get('search/employe/'+searcVal.value)
                        .then((response)=>{
                            employes.value = response.data.data.liste;
                            loadSearch.value = false;
                        })
                        .catch((error)=>{
                            catchErrors(error,succesMessage);
                        })
                }

            }

            const loardDataOnBoard = (first) => {
                let annee = form.annee
                if (first) {
                    annee = 0;
                }
                load.value = true;
                axios.get('statistique/number/' + annee)
                    .then((response) => {
                        form.salarierEvaluer = 0;
                        form.serviceEvaluer = 0;
                        form.performance = 0;
                        if (response.data.data.code === 500) {
                           /* Swal.fire({
                                title: 'FELICITATION',
                                text: response.data.data.message,
                                icon: 'success',
                            });*/
                        }else {
                            form.nbrdepartement = response.data.data.departements;
                            form.nbrEmploye = response.data.data.employes;
                            form.nbrService = response.data.data.services;
                            form.nbrUnite = response.data.data.unites;
                            form.salarierEvaluer = response.data.data.salarierEvaluer;
                            form.serviceEvaluer = response.data.data.serviceEvaluer;
                            if (first){
                                form.annee = response.data.data.campagne.annee_id;
                            }
                            form.performance = Math.round(response.data.data.performance*100)/100;

                        }
                        load.value = false;
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }
            const {annees, getAnnes} = useCampagneObjectif();
            onMounted(getAnnes);


            return {
                searcVal,
                testData,
                load,
                loadSearch,
                employes,
                form,
                annees,
                searchEmploye,
                loardDataOnBoard
            };

        }
    }
</script>

<style scoped>

</style>
