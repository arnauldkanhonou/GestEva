<template>
    <div>
        <titre-application big-title="DASHBOARD" small-title="Dashboard" name-page="RESP.S"></titre-application>
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
        <div class="row mt-1">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner"><h3 v-if="load"><i class="fas fa-circle-notch fa-spin"></i></h3> <h3 class="text-red-600" v-else>{{form.nbrEmploye}}</h3>
                        <p><b class="uppercase text-black">COLLABORATEURS</b></p></div>
                    <div class="icon"> <i class="ion ion-pie-graph"></i></div>
                    <a href="#" class="small-box-footer"><i class="fas fa-arrow"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-green-600">
                    <div class="inner"><h3 v-if="load"><i class="fas fa-circle-notch fa-spin"></i></h3> <h3 class="text-red-600" v-else>{{form.salarierEvaluer}}/{{form.nbrEmploye}}</h3>
                        <p><b class="uppercase">Collaborateurs évalués</b></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fas fa-arrow"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box btn-green">
                    <div class="inner"><h3 v-if="load"><i class="fas fa-circle-notch fa-spin"></i></h3> <h3 class="text-red-600" v-else>{{form.performance}}/72</h3>
                        <p v-if="(JSON.parse(store.state.salarie)).respService===true"><b class="uppercase">Performance Service</b></p>
                        <p v-else><b class="uppercase">Performance Unité</b></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fas fa-arrow"></i></a>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h5><strong>EVALUATIONS EN ATTENTE D'ENTRETIEN</strong></h5>
                <div v-if="loadEval" class="col-md-2 offset-4 mt-2">
                    <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                </div>
               <div v-else>
                   <table class="table table-bordered table-striped">
                       <thead class="bg-gray-600">
                       <tr>
                           <td class="text-white uppercase"><strong>Noms & Prénoms</strong></td>
                           <td class="text-white uppercase"><strong>DATE EVAL.</strong></td>
                           <td class="text-white uppercase"><strong>PERFOR. EVALUE</strong></td>
                           <td class="text-white uppercase"><strong>STATUT</strong></td>
                       </tr>
                       </thead>
                       <tbody>
                       <template v-if="evaluations.length>0" v-for="evaluation in evaluations" :key="evaluation.id">
                           <tr>
                               <td class="py-1 border-b border-gray-200 bg-white text-md">
                                   {{ evaluation.nom }}  {{ evaluation.prenoms }}
                               </td>

                               <td class="py-1 border-b border-gray-200 bg-white text-md">
                                   {{ getDate(evaluation.dateEvaluation) }}

                               </td> <td class="py-1 border-b border-gray-200 bg-white text-md">
                               {{ evaluation.performance }}
                           </td>
                               <td class="py-1 px-3 border-b border-gray-200 bg-white text-sm">
                                   <span class="badge bg-danger" ><b>En attente</b></span>&nbsp;
                               </td>
                           </tr>
                       </template>
                       <template v-else>
                           {{message}}
                       </template>
                       </tbody>
                   </table>
               </div>
            </div>
<!--            <div class="col-md-6">
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
                            <td class="text-white uppercase"><strong>DATE EMB.</strong></td>
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
            </div>-->
        </div>
        <hr>
<!--        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <h6><b>COURBE EVOLUTIVE DE VOS PERFORMANCE ANNUELLE</b></h6>
                </div>
                <LineChart :chartData="testData"/>
            </div>
            <div class="col-md-6">
                <div class="alert alert-info">
                    <h6><b>COURBE EVOLUTIVE DES PERFORMANCES ANNUELLE DU SERVICE</b></h6>
                </div>
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
    import appService from "../../services/appService";
    import axios from "axios";
    import Swal from "sweetalert2";
    import useCampagneObjectif from "../../services/campagneObjectif";
    import {useStore} from "vuex";

    Chart.register(...registerables);
    export default {
        name: "boardAdmin",
        components: {LineChart,TitreApplication},
        props: {
            messages: {
                required: false,
                type: String,
                default: '',
            }
        },
        setup() {
            const sleep = (ms) => {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

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
            let loadEval = ref(false);
            let succesMessage = ref('');
            let employes = ref('');
            let evaluations = ref('');
            let message = ref('');
            const {catchErrors,getDate} = appService();
            const store = useStore();
            const testData = {
                labels: ['0', '2021', '2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033',],
                datasets: [
                    {
                        data: [0, 30, 40, 60, 70, 95, 83, 55,53],
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
                        sleep(800).then(() => {
                            getPerformanceService()
                        })
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            const {annees, getAnnes} = useCampagneObjectif();
            onMounted(getAnnes);

            const getPerformanceService = () => {
                evaluations.value = [];
                loadEval.value = true;
                if (form.annee === '') {
                    alert('Veuillez sélectionnez une année.')
                    return;
                }
                form.start = true;
                axios.get('performance/service/' + 0)
                    .then((response) => {
                        if (response.data.data.code===500){
                            loadEval.value = false;
                            evaluations.value = [];
                            message.value = response.data.data.message;
                        }else {
                            evaluations.value = response.data.data.salaries;
                            loadEval.value = false;
                            if(evaluations.value.length===0){
                                message.value ='Aucune évaluations non validée disponible pour les collaborateurs';
                            }
                        }

                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            return {
                searcVal,
                testData,
                load,
                loadSearch,
                loadEval,
                employes,
                form,
                annees,
                evaluations,
                message,
                store,
                searchEmploye,
                loardDataOnBoard,
                getPerformanceService,
                getDate,
            };

        }
    }
</script>

<style scoped>

</style>
