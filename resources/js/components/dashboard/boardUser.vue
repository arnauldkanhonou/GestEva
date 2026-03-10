<template>
    <div>
        <titre-application big-title="DASHBOARD" small-title="Dashboard" name-page="SALARIE"></titre-application>
        <div v-if="messages!==''" class="alert alert-success">
            <strong> {{ messages }} </strong>
        </div>
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner"><h3 v-if="load"><i class="fas fa-circle-notch fa-spin"></i></h3>
                        <h3 v-else>{{form.nbrEvaluations}}</h3>
                        <p><b>EVALUATIONS</b></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer"> <i class="fas fa-arrow"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-danger">
                    <div class="inner"><h3 v-if="load"><i class="fas fa-circle-notch fa-spin"></i></h3>
                        <h3 v-else>{{form.nbrEmploye}}</h3>
                        <p><b>COLLEGUES</b></p>
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
                        <h3 v-else>{{form.nbrMission}}</h3>
                        <p><b>MISSIONS</b></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fas fa-arrow"></i></a>
                </div>
            </div>
        </div>
        <hr>
<!--        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <h6><b>COURBE EVOLUTIVE DE VOS PERFORMANCE ANNUELLE</b></h6>
                </div>
                <br>
                <LineChart :chartData="testData"/>
            </div>
            <div class="col-md-6">
                <div class="alert alert-info">
                    <h6><b>TOP 5 DES EVALUATIONS RECENTE</b></h6>
                </div>
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

    Chart.register(...registerables);
    export default {
        name: "boardAdmin",
        components: {LineChart, TitreApplication},
        props: {
            messages: {
                type: String,
                required: false,
                default: '',
            }
        },
        setup() {
            let form = reactive({
                nbrMission: 0,
                nbrEvaluations: 0,
                nbrEmploye: 0,
            })
            onMounted(() => {
                load.value = true;
                axios.get('statistique/number/'+0)
                    .then((response) => {
                        form.nbrEvaluations = response.data.data.evaluations;
                        form.nbrEmploye = response.data.data.collaborateurs;
                        form.nbrMission = response.data.data.missions;
                        load.value = false;
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
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

            return {
                searcVal,
                testData,
                load,
                employes,
                form,
                loadSearch,
            };
        }
    }
</script>

<style scoped>

</style>
