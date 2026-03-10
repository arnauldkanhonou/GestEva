<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="CONSULTATION DES EVALUATIONS MIS-PARCOURS" small-title="Validation"
                               name-page="Performances"></titre-application>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label class="uppercase mb-1 block text-base font-medium text-[#07074D]">
                    <b>Direction<i class="text-red-600">*</i></b>
                </label>
                <select @change="getServiceByDirction" v-model="form.direction" class="rounded-md form-control">
                    <option value="" readonly>Sélectionnez une direction</option>
                    <option v-for="direction in directions" :value="direction.id" :key="direction.id">
                        {{direction.libelle}}
                    </option>
                </select>
            </div>
            <!--            <div class="col-md-3">
                            <label class="uppercase mb-1 block text-base font-medium text-[#07074D]">
                                <b>Bloc/pole<i class="text-red-600">*</i></b>
                            </label>
                            <select v-model="form.bloc" @change="getserviceByBloc" class="rounded-md form-control">
                                <option value="" readonly>Sélectionnez un bloc d'usine</option>
                                <option v-for="bloc in form.blocs" :value="bloc.id" :key="bloc.id">
                                    {{bloc.libelle}}
                                </option>
                            </select>
                        </div>-->
            <div class="col-md-3">
                <label class="uppercase mb-1 block text-base font-medium text-[#07074D]">
                    <b>Services/Entités<i class="text-red-600">*</i></b>
                </label>
                <select @change="getEvaluationMiparcoursService"  v-model="form.service_id" class="rounded-md form-control">
                    <option value="" readonly>Choisissez un service</option>
                    <option v-for="service in form.services" :value="service.id" :key="service.id">
                        {{service.libelle}}
                    </option>
                </select>
            </div>
            <!--            <div class="col-md-3">
                            <label class="uppercase mb-1 block text-base font-medium text-[#07074D]">
                                <b>Années<i class="text-red-600">*</i></b>
                            </label>
                            <select @change="getPerformanceService" v-model="form.annee" class="rounded-md form-control">
                                <option value="" readonly>Choisissez une année</option>
                                <option v-for="annee in annees" :value="annee.id" :key="annee.id">
                                    {{annee.libelle}}
                                </option>
                            </select>
                        </div>-->
        </div>

        <div v-if="form.start" class="col-md-2 offset-5 mt-4"><br>
            <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
        </div>
        <div v-if="performances.length>0">
            <div class="mt-3 row">
                <div class="col-md-8">
                    <table style="border: white 2px solid; font-size: 13px;" class="table table-bordered table-striped">
                        <thead style="background-color: #057e72;color: white" class="uppercase">
                        <tr>
                            <td>Matricule</td>
                            <td>Nom</td>
                            <td>Prénoms</td>
                            <td>Service/Unité</td>
                            <td>Perf.</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="val in performances">
                            <tr>
                                <td><b v-text="val.matricule"></b></td>
                                <td><b>{{val.nom}}</b></td>
                                <td><b>{{val.prenoms}}</b></td>
                                <td><b v-text="val.unite"></b></td>
                                <td><b>-</b></td>
                                <td class="py-2 border-b border-gray-200 bg-white text-md">
                                    <button @click="showEvaluationMipacours(val.idEval)" type="button" title="Consulter L'evaluation"
                                            class="btn-sm bg-green-600 text-white px-2 py-1 rounded"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                        <i class="fa fa-eye-slash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div v-else class="mt-4">
            <div v-if="form.load" class="alert alert-info">
                <b>AUCUNE EVALUATION DISPPONIBLE POUR LE SERVICE SELECTIONNE</b>
            </div>
        </div>

        <!-- Modal evaluation mi-parcours -->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
             id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="examp">
                            <b><i class="fa fa-question-circle"></i> CONSULTATION EVALUATION MI-PARCOURS</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div v-if="!load" class="offset-5 mt-4">
                            <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                            <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                        </div>
                        <div v-else>
                            <div class="row">
                                <div style="border: #0a0a0a 2px solid" class="offset-4 col-md-4 text-center">
                                    <b>EVALUATION A MI-PARCOURS</b>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="offset-4 col-md-4 text-center">
                                    <b class="text-center">Année:{{form.annee.libelle}}</b>
                                </div>
                            </div>

                            <table style="border: #0a0a0a 2px solid" class="table table-bordered">
                                <tr style="border: #0a0a0a 2px solid">
                                    <td width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Nom :</b> <b class="text-red-600">{{form.employe.nom}}</b>&nbsp;&nbsp;&nbsp;<br>
                                            <b>Prenoms :</b> <b class="text-red-600">{{form.employe.prenoms}}</b>
                                        </div>
                                    </td>
                                    <td colspan="2" width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Date d'entretien:</b> <b class="text-red-600">{{form.evaluation.dateEntretien}}</b>
                                        </div>

                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Service :</b> <b class="text-red-600">{{form.service.libelle}}</b>
                                        </div>
                                    </td>
                                    <td colspan="3" width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b> Ancienneté au poste :</b> <b class="text-red-600">{{form.anciennete}}</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Fonction :</b> <b class="text-red-600">{{form.fonction.libelle}}</b>
                                        </div>
                                    </td>
                                    <td colspan="3" width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b> Nom de l'Evaluateur :</b> <b class="text-red-600">{{form.evaluation.evaluateur}}</b>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <table style="border: #0a0a0a 2px solid">
                                <tr style="background-color: #0a6779;color: white;">
                                    <td style="border: #0a0a0a 2px solid" colspan="3">
                                        <div class="offset-2 col-md-8 text-center">
                                            <b>POINT SUR LA PERIODE ECOULEE</b> <br>
                                            <b><small>Rappel des faits marquants qui ont pu influencer l'activité et
                                                les resultats</small></b>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td style="border: #0a0a0a 2px solid" align="center" width="50%">
                                        <b>Points positifs</b>
                                    </td>
                                    <td colspan="2" style="border: #0a0a0a 2px solid" align="center" width="50%">
                                        <b>Points négatifs</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: #0a0a0a 2px solid">
                                        <div>
                                            <small v-for="accomplissement in form.tabAccomplissement">
                                                - {{accomplissement}} <br>
                                            </small>
                                        </div>
                                    </td>
                                    <td colspan="2" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <small v-for="difficulte in form.tabDifficulte">
                                                - {{difficulte}} <br>
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color: #0a6779;color: white;border: #0a0a0a 2px solid">
                                    <td align="center" style="border: #0a0a0a 2px solid" colspan="3">
                                        <div>
                                            <b>PASSAGE EN REVUE DES OBJECTIFS</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td align="center" width="42%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Rappel des objectifs fixés en début d'année</b>
                                        </div>
                                    </td>
                                    <td align="center" width="25%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Niveau de réalisation atteint</b>
                                        </div>
                                    </td>
                                    <td align="center" width="33%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>
                                                Analyse et commentaire eventuel <br>
                                                <small>(Difficultés rencontrées)</small>
                                            </b>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="objectif in form.objectifs">
                                    <td style="border: #0a0a0a 2px solid">
                                        {{objectif.libelle}}
                                    </td>
                                    <td class="text-red-600" style="border: #0a0a0a 2px solid">
                                        <b>{{objectif.NivRealisationMPEvaluer}}</b>
                                    </td>
                                    <td style="border: #0a0a0a 2px solid">
                                        {{objectif.commentaireEvaluerMP}}
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <table style="border: #0a0a0a 2px solid" class="table table-bordered">
                                <tr style="background-color:#0a6779;color: white;">
                                    <td align="center" colspan="2" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b class="uppercase">Autres réalisations accomplies,ou difficultés soulignées à mi-parcours</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td align="center" width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Réalisations</b>
                                        </div>
                                    </td>
                                    <td align="center" width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Difficultés</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="realisation in form.autreRealisation">
                                    <td style="border: #0a0a0a 2px solid">
                                        {{realisation.realisation}}
                                    </td>
                                    <td style="border: #0a0a0a 2px solid">
                                        {{realisation.difficultes}}
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <table style="border: #0a0a0a 2px solid" class="table table-bordered">
                                <tr style="background-color:#0a6779;color: white;">
                                    <td align="center" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b class="uppercase">COMMENTAIRE DU SALARIE</b>
                                        </div>
                                    </td>
                                    <td align="center" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b class="uppercase">COMMENTAIRE EVALUATEUR</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>{{form.evaluation.commentaireEvaluer}}</b>
                                        </div>
                                    </td>
                                    <td style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>{{form.evaluation.commentaireResp}}</b>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermer-mipacours"
                                class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                data-bs-dismiss="modal">Fermer
                        </button>

                        <button v-if="!form.evaluation.clotureResp" @click="displayFormAutoEvaluation"
                                id="btn-edit-miparcours"
                                class="btn-sm btn-green text-white font-bold py-2 px-4"
                        ><i class="fa fa-edit"></i>Modifier
                        </button>

                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import TitreApplication from "../block/TitreApplication";
import useDirection from "../../services/directionServices";
import {onMounted,reactive,ref} from "vue";
import axios from "axios";
import appService from "../../services/appService";
import useCampagneObjectif from "../../services/campagneObjectif";
import Swal from "sweetalert2";
export default {
    name: "validationCodi",
    components: {TitreApplication},
    setup(){
        const form = reactive({
            direction:'',
            services:'',
            blocs:'',
            start:false,
            load:false,
            service_id:'',
            annee:'',
            commentaire:'R.A.S',
            bonus:0,
            commentaireUnitaire: '',
            bonusUnitaire: 0,
            bloc:'',
            anciennete: '',
            autreRealisation: '',
            evaluation: '',
            objectifs: '',
            tabAccomplissement: '',
            tabDifficulte: '',
            employe: '',
            fonction: '',
            service: '',
        })
        let user = JSON.parse(sessionStorage.getItem('user'));
        const {load,form1,displayRapport,validationBySalirie,sleep,catchErrors} = appService();
        const succesMessage = ref('')
        const performances = ref([])
        const currentEval = ref('')
        const  {getDirections,directions} = useDirection();
        const {annees, getAnnes} = useCampagneObjectif();
        onMounted(getAnnes);

        onMounted(getDirections);

        const getServiceByDirction = ()=>{
            if (form.direction ===''){
                return ;
            }
            performances.value = [];
            form.load = false;
            form.start = true;
            axios.get('service/direction/'+form.direction+'/'+true)
                .then((response)=>{
                    if (response.data.data.code ===500){

                    }else {
                        form.services = response.data.data.services;
                        form.blocs = response.data.data.blocs;
                        form.start = false;
                    }

                }).catch((error)=>{
                catchErrors(error,succesMessage);
            })
        }

        const getserviceByBloc = () =>{
            if (form.bloc ===''){
                return ;
            }
            performances.value = [];
            form.start = true;
            axios.get('service/direction/'+form.bloc+'/'+false)
                .then((response)=>{
                    if (response.data.data.code ===500){

                    }else {
                        form.services = response.data.data.services;
                        form.start = false;
                    }

                }).catch((error)=>{
                catchErrors(error,succesMessage);
            })
        }

        const getEvaluationMiparcoursService = () => {
            if (form.service_id === '') {
                Swal.fire({
                    text: 'Veuillez sélectionnez un service.',
                });
                return;
            }
            performances.value = [];
            form.start = true;
            form.load = false;
            axios.get('get/evaluation/miparcours/service/'+form.service_id)
                .then((response) => {
                    if (response.data.code===500){
                        form.start = false;
                        form.load = false;
                        Swal.fire({
                            title: 'Erreur serveur',
                            text: response.data.data.message,
                            icon: 'error',
                        });
                    }else {
                        performances.value = response.data.evaluations;
                        form.load = true;
                        form.start = false;
                    }

                })
                .catch((error) => {
                    catchErrors(error, succesMessage);
                })
        }

        const showEvaluationMipacours = (idEvaluation) => {
            load.value = false;
            axios.get('show/evaluation/miparcours/' + idEvaluation)
                .then((response) => {
                    if (response.data.data.code === 200) {
                        let resp = response.data.data;
                        form.anciennete = resp.anciennete;
                        form.objectifs = resp.objectifs
                        form.autreRealisation = resp.autreRealisations;
                        form.evaluation = resp.evaluation;
                        form.tabAccomplissement = resp.tabAccomplissement;
                        form.tabDifficulte = resp.tabDifficulte;
                        form.employe = resp.employe;
                        form.fonction = resp.fonction;
                        form.service = resp.service;
                        //form.annee = resp.annee;
                        load.value = true;
                    }

                })
        }

        return {
            form,
            directions,
            annees,
            performances,
            load,
            form1,
            currentEval,
            getserviceByBloc,
            getServiceByDirction,
            getEvaluationMiparcoursService,
            showEvaluationMipacours
        }
    }
}
</script>

<style scoped>
.vl {
    border-left: 3px solid #057e72;
    height: auto;
}
.modal-lg {
    max-width: 65% !important;
}

.ulFaireMarquant {
    list-style-type: none;
    margin-left: -42px
}
h5{
    color:red;
}
h6{
    text-decoration: underline;
}
</style>
