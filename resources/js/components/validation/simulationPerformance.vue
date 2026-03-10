<template>
    <!--<b>Encours de réflexion. Il est prévu d'afficher la liste de tous les salriés regroupé par service
        avec la performance de chacun et en bas la performance du service afin<br>
    de permettre au codir de prendre des décisions pour validation des évaluations</b>-->
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="FICHE-DE-NOTE-PONDEREE" small-title="Simulation"
                               name-page="Performances"></titre-application>
            <div class="row">
                <div class="row">
                    <div class="offset-6 col-md-3 text-danger ">
                        <strong style="font-size: 18px">ANNEE PERFORMANCE : {{anneePerformance}}</strong>
                    </div>
                    <div class="col-md-3 text-danger">
                        <strong style="font-size: 18px">PERFORMANCE ENTRPRISE : {{ performanceUsine }}</strong>
                    </div>
                </div>
                <div v-show="load" class="mt-4 container">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#cadres" role="tab"
                               aria-controls="home" aria-selected="true">Cadres</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#maitrises" role="tab"
                               aria-controls="profile" aria-selected="false">Maitrises</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="messages-tab" data-toggle="tab" href="#executants" role="tab"
                               aria-controls="messages" aria-selected="false">Executants</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="cadres" role="tabpanel" aria-labelledby="home-tab">
                            <table style="border: white 2px solid; font-size: 13px;" class="">
                                <thead style="background-color: #057e72;color: white" class="uppercase">
                                <tr>
                                    <td style="width: 5%; border: 1px solid black;padding: 9px">Matr.</td>
                                    <td style="width: 18%; border: 1px solid black;padding: 9px">Salarié</td>
                                    <td style="width: 15%; border: 1px solid black;padding: 9px">Poste</td>
                                    <td style="width: 10%; border: 1px solid black;padding: 9px">Service</td>
                                    <td style="width: 5%; border: 1px solid black;padding: 9px">Cat.</td>
                                    <td style="width: 8%; border: 1px solid black;padding: 9px">Note Obt.</td>
                                    <td style="width: 8%; border: 1px solid black;padding: 9px">Perfor.</td>
                                    <td style="width: 8%; border: 1px solid black;padding: 9px">Note Pondérée</td>
                                    <td style="width: 15%; border: 1px solid black;padding: 9px">Perf.Apr.Pondé.</td>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(item,index) in form.cadres" :key="index">
                                    <template v-for="(val,cle) in item" :key="cle">
                                        <tr style="border: black 1px solid" :class="val.beneficiaire?'alert-primary':val.havePrimeExcept?'alert-warning':''" >
                                            <td style="width: 5%; border: 1px solid black;padding: 8px"><b v-text="val.matricule"></b></td>
                                            <td style="width: 18%; border: 1px solid black;padding: 8px"><b>{{ val.nom }} {{ val.prenoms }}</b></td>
                                            <td style="width: 15%; border: 1px solid black;padding: 8px"><b v-text="val.poste"></b></td>
                                            <td style="width: 10%; border: 1px solid black;padding: 8px"><b v-text="val.service"></b></td>
                                            <td style="width: 5%; border: 1px solid black;padding: 8px"><b v-text="val.cateprofe"></b></td>
                                            <td style="width: 8%; border: 1px solid black;padding: 8px"><b v-text="val.performanceRealiser"></b></td>
                                            <td style="width: 8%; border: 1px solid black;padding: 8px"><b v-text="val.niveauPerf"></b></td>
                                            <td style="width: 8%; border: 1px solid black;padding: 8px"><b :style="val.notePondere > 0 ? 'color: green':''">{{ val.performanceFinal }}</b>
                                            </td>
                                            <td style="width: 15%; border: 1px solid black;padding: 8px">
                                                <b v-text="val.niveauPerfApresPonde"></b>
                                               <template v-if="!listeBeneficiaireValider">
                                                   &nbsp;&nbsp;
                                                   <button v-if="!val.beneficiaire && !val.havePrimeExcept" @click="defineBeneficiairePrime(val)"
                                                           type="button" :id="'btnDefinePrimeExcept'+val.id"
                                                           style="background-color: green; color: white;padding: 2px;"
                                                           title="choisir pour prime exceptionnelle">
                                                       <i class="fa fa-plus-circle"></i>
                                                   </button>
                                                   <button @click="removeBeneficiairePrime(val)" :id="'btnRemovePrimeExcept'+val.id" type="button" v-if="val.havePrimeExcept"
                                                           style="background-color: red; color: white;padding: 2px;">
                                                       <i class="fa fa-minus-circle"></i>
                                                   </button>
                                                   <i hidden :id="'loadPrimeExcept'+val.id" class="fa fa-spinner fa-spin fa-2x"></i>
                                               </template>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr style="border: 1px solid black">
                                        <td style="border: 1px solid black;padding: 8px" align="right" colspan="3"><b style="font-size: 17px" class="text-red-600">Effectif evalué</b></td>
                                        <td style="border: 1px solid black;padding: 8px" align="right"><b class="text-red-600"><b style="font-size: 17px">{{ item.length }}</b></b></td>
                                        <td style="border: 1px solid black;padding: 8px" align="right"><b style="font-size: 17px" class="text-red-600">Moyenne</b></td>
                                        <td style="border: 1px solid black;padding: 8px" colspan="5"><b style="font-size: 17px" class="text-danger">{{ index }}</b></td>
                                    </tr>

                                </template>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="maitrises" role="tabpanel" aria-labelledby="profile-tab">
                            <table style="border: white 2px solid; font-size: 13px;" class="">
                                <thead style="background-color: #057e72;color: white" class="uppercase">
                                <tr>
                                    <td style="width: 5%; border: 1px solid black;padding: 9px">Matricule</td>
                                    <td style="width: 18%; border: 1px solid black;padding: 9px">Nom & Prénoms</td>
                                    <td style="width: 15%; border: 1px solid black;padding: 9px">Poste</td>
                                    <td style="width: 10%; border: 1px solid black;padding: 9px">Service</td>
                                    <td style="width: 5%; border: 1px solid black;padding: 9px">Categorie</td>
                                    <td style="width: 8%; border: 1px solid black;padding: 9px">Note Obt.</td>
                                    <td style="width: 8%; border: 1px solid black;padding: 9px">Perfor.</td>
                                    <td style="width: 8%; border: 1px solid black;padding: 9px">Note Pondérée</td>
                                    <td style="width: 15%; border: 1px solid black;padding: 9px">Perf.Apr.Pondé.</td>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(item,index) in form.maitrises" :key="index">
                                    <template v-for="(val,cle) in item" :key="cle">
                                        <tr style="border: black 1px solid" :class="val.beneficiaire?'alert-primary':val.havePrimeExcept?'alert-warning':''">
                                            <td style="width: 5%; border: 1px solid black;padding: 9px"><b v-text="val.matricule"></b></td>
                                            <td style="width: 18%; border: 1px solid black;padding: 9px"><b>{{ val.nom }} {{ val.prenoms }}</b></td>
                                            <td style="width: 15%; border: 1px solid black;padding: 9px"><b v-text="val.poste"></b></td>
                                            <td style="width: 10%; border: 1px solid black;padding: 9px"><b v-text="val.service"></b></td>
                                            <td style="width: 5%; border: 1px solid black;padding: 9px"><b v-text="val.cateprofe"></b></td>
                                            <td style="width: 8%; border: 1px solid black;padding: 9px"><b v-text="val.performanceRealiser"></b></td>
                                            <td style="width: 8%; border: 1px solid black;padding: 9px"><b v-text="val.niveauPerf"></b></td>
                                            <td style="width: 8%; border: 1px solid black;padding: 9px"><b :style="val.notePondere > 0 ? 'color: green':''">{{ val.performanceFinal }}</b></td>
                                            <td style="width: 15%; border: 1px solid black;padding: 9px">
                                                <b v-text="val.niveauPerfApresPonde"></b>
                                                <template v-if="!listeBeneficiaireValider">
                                                        &nbsp;&nbsp;
                                                        <button v-if="!val.beneficiaire && !val.havePrimeExcept" @click="defineBeneficiairePrime(val)"
                                                                type="button" :id="'btnDefinePrimeExcept'+val.id"
                                                                style="background-color: green; color: white;padding: 2px;"
                                                                title="choisir pour prime exceptionnelle">
                                                            <i class="fa fa-plus-circle"></i>
                                                        </button>
                                                        <button @click="removeBeneficiairePrime(val)" :id="'btnRemovePrimeExcept'+val.id" type="button" v-if="val.havePrimeExcept"
                                                                style="background-color: red; color: white;padding: 2px;">
                                                            <i class="fa fa-minus-circle"></i>
                                                        </button>
                                                        <i hidden :id="'loadPrimeExcept'+val.id" class="fa fa-spinner fa-spin fa-2x"></i>

                                                </template>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr style="border: 1px solid black">
                                        <td style="border: 1px solid black;padding: 8px" align="right" colspan="3"><b style="font-size: 17px" class="text-red-600">Effectif evalué</b></td>
                                        <td style="border: 1px solid black;padding: 8px" align="right"><b class="text-red-600"><b style="font-size: 17px">{{ item.length }}</b></b></td>
                                        <td style="border: 1px solid black;padding: 8px" align="right"><b style="font-size: 17px" class="text-red-600">Moyenne</b></td>
                                        <td style="border: 1px solid black;padding: 8px" colspan="5"><b style="font-size: 17px" class="text-danger">{{ index }}</b></td>
                                    </tr>

                                </template>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="executants" role="tabpanel" aria-labelledby="messages-tab">
                            <table style="border: white 2px solid; font-size: 13px;" class="table table-bordered table-striped">
                                <thead style="background-color: #057e72;color: white" class="uppercase">
                                <tr>
                                    <td style="width: 5%; border: 1px solid black;padding: 9px">Matricule</td>
                                    <td style="width: 18%; border: 1px solid black;padding: 9px">Nom & Prénoms</td>
                                    <td style="width: 15%; border: 1px solid black;padding: 9px">Poste</td>
                                    <td style="width: 10%; border: 1px solid black;padding: 9px">Service</td>
                                    <td style="width: 5%; border: 1px solid black;padding: 9px">Categorie</td>
                                    <td style="width: 8%; border: 1px solid black;padding: 9px">Note Obt.</td>
                                    <td style="width: 8%; border: 1px solid black;padding: 9px">Perfor.</td>
                                    <td style="width: 8%; border: 1px solid black;padding: 9px">Note Pondérée</td>
                                    <td style="width: 15%; border: 1px solid black;padding: 9px">Perf.Apr.Pondé.</td>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(item,index) in form.executants" :key="index">
                                    <template v-for="(val,cle) in item" :key="cle">
                                        <tr style="border: black 1px solid" :class="val.beneficiaire?'alert-primary':val.havePrimeExcept?'alert-warning':''">
                                            <td style="width: 5%; border: 1px solid black;padding: 9px"><b v-text="val.matricule"></b></td>
                                            <td style="width: 18%; border: 1px solid black;padding: 9px"><b>{{ val.nom }} {{ val.prenoms }}</b></td>
                                            <td style="width: 15%; border: 1px solid black;padding: 9px"><b v-text="val.poste"></b></td>
                                            <td style="width: 10%; border: 1px solid black;padding: 9px"><b v-text="val.service"></b></td>
                                            <td style="width: 5%; border: 1px solid black;padding: 9px"><b v-text="val.cateprofe"></b></td>
                                            <td style="width: 8%; border: 1px solid black;padding: 9px"><b v-text="val.performanceRealiser"></b></td>
                                            <td style="width: 8%; border: 1px solid black;padding: 9px"><b v-text="val.niveauPerf"></b></td>
                                            <td style="width: 8%; border: 1px solid black;padding: 9px"><b :style="val.notePondere > 0 ? 'color: green':''">{{ val.performanceFinal }}</b></td>
                                            <td style="width: 15%; border: 1px solid black;padding: 9px"><b v-text="val.niveauPerfApresPonde"></b>
                                                &nbsp;&nbsp;
                                               <template v-if="!listeBeneficiaireValider">
                                                   <button v-if="!val.beneficiaire && !val.havePrimeExcept" @click="defineBeneficiairePrime(val)"
                                                           type="button" :id="'btnDefinePrimeExcept'+val.id"
                                                           style="background-color: green; color: white;padding: 2px;"
                                                           title="choisir pour prime exceptionnelle">
                                                       <i class="fa fa-plus-circle"></i>
                                                   </button>
                                                   <button @click="removeBeneficiairePrime(val)" :id="'btnRemovePrimeExcept'+val.id" type="button" v-if="val.havePrimeExcept"
                                                           style="background-color: red; color: white;padding: 2px;">
                                                       <i class="fa fa-minus-circle"></i>
                                                   </button>
                                                   <i hidden :id="'loadPrimeExcept'+val.id" class="fa fa-spinner fa-spin fa-2x"></i>
                                               </template>

                                            </td>
                                        </tr>
                                    </template>
                                    <tr style="border: black 1px solid">
                                        <td style="border: 1px solid black;padding: 8px" align="right" colspan="3"><b style="font-size: 17px" class="text-red-600">Effectif evalué</b></td>
                                        <td style="border: 1px solid black;padding: 8px" align="right"><b class="text-red-600"><b style="font-size: 17px">{{ item.length }}</b></b></td>
                                        <td style="border: 1px solid black;padding: 8px" align="right"><b style="font-size: 17px" class="text-red-600">Moyenne</b></td>
                                        <td style="border: 1px solid black;padding: 8px" colspan="5"><b style="font-size: 17px" class="text-danger">{{ index }}</b></td>
                                    </tr>

                                </template>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-show="!load">
                    <div class="col-md-2 offset-5 mt-4">
                        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                </div>

            </div>
        </div>

        <!--
                &lt;!&ndash; Modal &ndash;&gt;
                <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                     id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                     aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                        <div
                            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                            <div style="background-color: #acb3c4"
                                 class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                                <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                                    <b><i class="fa fa-question-circle"></i> AUJUSTEMENT DE LA PONDERATION</b>
                                </h5>
                                &lt;!&ndash;<button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                                        data-bs-dismiss="modal" aria-label="Close"></button>&ndash;&gt;
                            </div>
                            <div class="modal-body">
                                <b><h5>Vous êtes sur le point d'ajuster la note d'évaluation de <b class="pl-1 pr-1" style="background-color: #0a6779;color: white">{{form.currentEval.nom}} {{form.currentEval.prenoms}}</b> </h5></b>
                               <div>
                                   <label for=""><b>Valeur à retrancher/ à ajouter</b></label>
                                   <input type="number" class="form-control">
                               </div>
                            </div>
                            <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                                <button id="btn-fermer" class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                        data-bs-dismiss="modal">FERMER
                                </button>
                                <button id="btn-confirm" @click="trashRessource(ressource.id)"
                                        class="bg-red-600 hover:bg-red-600 text-white font-bold py-2 px-4">VALIDER
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        -->

    </div>
</template>

<script>
import TitreApplication from "../block/TitreApplication";
import useCampagneObjectif from "../../services/campagneObjectif";
import {onMounted, reactive, ref} from "vue";
import axios from "axios";
import useDirection from "../../services/directionServices";
import appService from "../../services/appService";
import Swal from "sweetalert2";
import {useStore} from "vuex";

export default {
    name: "simulationPerformance",
    components: {TitreApplication},
    setup() {
        //const {annees, getAnnes} = useCampagneObjectif();
        const {getDirections, directions} = useDirection();
        const {catchErrors} = appService();
        const succesMessage = ref('')
        const store = useStore();
        // onMounted(getAnnes);
        // onMounted(getDirections);
        onMounted(() => {
            $(function () {
                $('#myTab a').on('click', function (e) {
                    e.preventDefault()
                    $(this).tab('show')
                })

                $('#myTab a[href="#cadres"]').tab('show') // Select tab by name
            })
            let baseUrl = store.state.MIX_API_URL;
            //window.location.href = baseUrl + 'codi/simulation/performance/';
            getPerformanceGlobale()
        })
        const form = reactive({
            annee: '',
            type: '',
            datas: [],
            direction_id: '',
            service_id: '',
            services: [],
            currentEval: '',
            cadres: [],
            maitrises: [],
            executants: [],
        })
        const load = ref(false)
        const listeBeneficiaireValider = ref(false)

        const performanceUsine = ref(0)
        const anneePerformance = ref(0)

        const getPerformanceGlobale = (from='') => {
            /*form.annee = $('#annee').val();
            if (form.type === '') {
                alert('Veuilez sélectionné le type d\'agent');
                return;
            }
            if (form.annee === '') {
                alert('Veuilez sélectionné la campagne');
                return;
            }*/
            if (from === '') {
                load.value = false
            }

            axios.get('get/liste/notepondere')
                .then((response) => {
                    load.value = true
                    form.cadres = response.data.liste.AC
                    form.maitrises = response.data.liste.AM
                    form.executants = response.data.liste.AE
                    // form.datas = response.data.liste;
                    //console.log(form)
                    performanceUsine.value = response.data.performanceUsine
                    anneePerformance.value = response.data.annee;
                    listeBeneficiaireValider.value = response.data.listeBeneficiaireValider
                })
        }
        const getPerformanceService = () => {
            load.value = false
            axios.get('get/notepondere/service/' + form.service_id)
                .then((response) => {
                    load.value = true
                    form.datas = response.data.liste;
                    performanceUsine.value = response.data.performanceUsine
                })
        }

        const getServiceByDirection = () => {
            axios.get('service/direction/' + form.direction_id + '/' + true)
                .then((response) => {
                    if (response.data.data.code === 500) {

                    } else {
                        form.services = response.data.data.services;

                    }

                }).catch((error) => {
                catchErrors(error, succesMessage);
            })
        }

        const defineBeneficiairePrime = (item) => {
            $('#btnDefinePrimeExcept'+item.id).attr('hidden','hidden');
            $('#loadPrimeExcept'+item.id).removeAttr('hidden');

            axios.post('definir/beneficiaire/primeexcept', item)
                .then((response) => {
                    getPerformanceGlobale('defineprime')
                    $('#loadPrimeExcept'+item.id).attr('hidden','hidden');
                }).catch((error) => {
                catchErrors(error, succesMessage);
            })
        }

        const removeBeneficiairePrime = (item) => {
            $('#btnRemovePrimeExcept'+item.id).attr('hidden','hidden');
            $('#loadPrimeExcept'+item.id).removeAttr('hidden');

            axios.post('remove/beneficiaire/primeexcept', item)
                .then((response) => {
                    getPerformanceGlobale('removeprime')
                    $('#loadPrimeExcept'+item.id).attr('hidden','hidden');
                }).catch((error) => {
                catchErrors(error, succesMessage);
            })
        }

        const updatePonderation = (item) => {
            form.currentEval = item
        }

        return {
            directions,
            form,
            load,
            performanceUsine,
            listeBeneficiaireValider,
            anneePerformance,
            getPerformanceGlobale,
            getServiceByDirection,
            getPerformanceService,
            updatePonderation,
            defineBeneficiairePrime,
            removeBeneficiairePrime
        }
    }
}
</script>

<style scoped>

</style>
