<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="DETERMINATION LAUREAT AU BONUS" small-title="RH"
                               name-page="LAUREAT BONUS"></titre-application>
            <div v-if="!listDejaValide">
                <section class="mb-3">
                    <h5 class="alert alert-info">I-CRITERE</h5>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h5><b>Taux résultat 2025 par rapport au budget</b></h5>
                        </div>
                        <div class="col-md-4">
                            <input v-model="form.tauxResultat" type="number" min="1" max="100" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h5><b>Somme des salaires de base</b></h5>
                        </div>
                        <div class="col-md-4">
                            <input v-model="form.sommeSalaireBaseFormater"
                                   @blur="numberFormat(form.sommeSalaireBaseFormater)" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h5><b>Budget du bonus prévisionel</b></h5>
                        </div>
                        <div class="col-md-4">
                            <input v-model="form.budgetBonusFormater" id
                                   @blur="numberFormat(form.budgetBonusFormater,'budget')" type="text"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h5><b>Taux applicable à la performance A</b></h5>
                        </div>
                        <div class="col-md-4">
                            <input v-model="form.tauxPerformanceA" type="number" class="form-control">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h5><b>Taux applicable à la performance B</b></h5>
                        </div>
                        <div class="col-md-4">
                            <input v-model="form.tauxPerformanceB" type="number" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div v-if="!sendReq" class="offset-4 col-md-4">
                            <button @click="calculCagnote" type="button" class="btn btn-danger"><i
                                class="fa fa-search"></i> <b class="text-uppercase">Déterminer les bénéficiaires</b>
                            </button>
                        </div>
                        <div v-if="!btnSubmit" class="col-md-2 offset-5"><br>
                            <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                        </div>
                    </div>

                </section>

                <section v-if="load">
                    <section class="mb-3">
                        <h5 class="alert alert-info">II-CAGNOTTE DE BASE</h5>
                        <hr>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5><b>3% de la Somme des salaires de base</b></h5>
                            </div>
                            <div class="col-md-4">
                                <p><b>{{ formatMonetaire(form.troisPercentSB, 'XOF') }}</b></p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5><b>Cagnotte de base</b></h5>
                            </div>
                            <div class="col-md-4">
                                <p><b>{{ formatMonetaire(form.cagnotteBase, 'XOF') }}</b></p>
                            </div>
                        </div>
                    </section>

                    <section class="mb-3">
                        <h5 class="alert alert-info">III-DETERMINATION DES TAUX APPLICABLE</h5>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5><b>Total des smc des salariés ayant eu la performance A</b></h5>
                            </div>
                            <div class="col-md-4">
                                <p><b>{{ formatMonetaire(form.totalSCMperformanceA, 'XOF') }}</b></p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5><b>Total des smc des salariés ayant eu la performance B</b></h5>
                            </div>
                            <div class="col-md-4">
                                <p><b>{{ formatMonetaire(form.totalSMCperformanceB, 'XOF') }}</b></p>
                            </div>
                        </div>
                    </section>

                    <section class="mb-3">
                        <h5 class="alert alert-info">4-LISTE DES BENEFICIAIRES DU BONUS</h5>
                        <table style="border: white 2px solid; font-size: 13px;"
                               class="table table-bordered table-striped">
                            <thead style="background-color: #057e72;color: white" class="uppercase">
                            <tr>
                                <td class="text-uppercase">Nom & Prénoms</td>
                                <td class="text-uppercase">Poste</td>
                                <td class="text-uppercase">Categorie</td>
                                <td class="text-uppercase">Note Pondé.</td>
                                <td class="text-uppercase">Perf.Apr.Pondé.</td>
                                <td class="text-uppercase">SMC</td>
                                <td class="text-uppercase">Montant taux appliqué</td>
                                <td class="text-uppercase">Montant brut bonus</td>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(val,index) in beneficiaires" :key="index">
                                <tr style="border: black 1px solid" :class="!val.isBonus?'alert alert-warning':''">
                                    <td><b v-text="val.salarie"></b></td>
                                    <td><b v-text="val.poste"></b></td>
                                    <td><b v-text="val.categorie"></b></td>
                                    <td><b>{{ val.performanceFinal }}</b></td>
                                    <td><b v-text="val.niveauPerformanceApresPonderation"></b></td>
                                    <td align="right"><b>{{ formatMonetaire(val.smc, 'XOF') }}</b></td>
                                    <td v-if="val.isBonus" align="right">
                                        <b>{{ formatMonetaire(val.tauxAppliqueSmc, 'XOF') }}</b>
                                    </td>
                                    <td v-else align="right">
                                        <b>{{ formatMonetaire(val.montantPrime, 'XOF') }}</b>
                                    </td>
                                    <td v-if="val.isBonus" align="right">
                                        <b>
                                            {{ formatMonetaire(val.montantBonus, 'XOF') }}
                                        </b>
                                    </td>
                                    <td v-else>
                                        <input :id="'input'+val.matricule" type="number"
                                               style="border: 1px solid black;padding: 1px">
                                        &nbsp;
                                        <button @click="setValuePrimeExceptForSalarie(val)"
                                                :id="'btnSetPrime'+val.matricule"
                                                style="background-color: green;color: white;padding: 2px"><i
                                            class="fa fa-check-circle"></i></button>
                                        <button hidden :id="'loadSetPrime'+val.matricule"><i
                                            class="fa fa-spin fa-spinner fa-2x"></i></button>
                                    </td>
                                </tr>
                            </template>
                            <tr style="border: black 1px solid">
                                <td align="right" colspan="7"><b style="font-size: 17px;" class="text-red-600">Total
                                    Bonus</b></td>
                                <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px"
                                                     class="text-black">{{
                                        formatMonetaire(form.sommeBonusBenificiaire, 'XOF')
                                    }}</b></td>
                            </tr>
                            <tr style="border: black 1px solid">
                                <td align="right" colspan="7"><b style="font-size: 17px;" class="text-red-600">Prime
                                    Exceptionnelle</b></td>
                                <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px"
                                                     class="text-black">{{ formatMonetaire(primeExcept, 'XOF') }}</b>
                                </td>
                            </tr>
                            <tr style="border: black 1px solid">
                                <td align="right" colspan="7"><b style="font-size: 17px;"
                                                                 class="text-red-600">Budget</b></td>
                                <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px"
                                                     class="text-black">{{
                                        formatMonetaire(form.budgetBonus - 0, 'XOF')
                                    }}</b></td>
                            </tr>
                            <tr style="border: black 1px solid">
                                <td align="right" colspan="7"><b style="font-size: 17px;" class="text-red-600">Ecart</b>
                                </td>
                                <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px"
                                                     class="text-black">{{
                                        formatMonetaire(form.budgetBonus - form.sommeBonusBenificiaire - primeExcept, 'XOF')
                                    }}</b></td>
                            </tr>
                            </tbody>
                        </table>
                        <div align="right">
                            <button class="btn btn-green text-white col-md-3" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdropValid">VALIDER ET CLOTURER
                            </button>
                        </div>
                    </section>
                </section>
            </div>
            <div v-else>
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="alert alert-info">LISTE DES BENEFICIAIRES DU BONUS ET PRIME EXECPTIONNELLE</h5>
                        <table style="border: white 2px solid; font-size: 13px;"
                               class="table table-bordered table-striped">
                            <thead style="background-color: #057e72;color: white" class="uppercase">
                            <tr>
                                <td class="text-uppercase">Nom & Prénoms</td>
                                <td class="text-uppercase">Poste</td>
                                <td class="text-uppercase">Categorie</td>
                                <td class="text-uppercase">Note Pondé.</td>
                                <td class="text-uppercase">Perf.Apr.Pondé.</td>
                                <td class="text-uppercase">SMC</td>
                                <td class="text-uppercase">Montant taux appliqué</td>
                                <td class="text-uppercase">Montant brut bonus</td>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(val,index) in beneficiaires" :key="index">
                                <tr style="border: black 1px solid" :class="!val.isBonus?'alert alert-warning':''">
                                    <td><b v-text="val.salarie"></b></td>
                                    <td><b v-text="val.poste"></b></td>
                                    <td><b v-text="val.categorie"></b></td>
                                    <td><b>{{ val.performanceFinal }}</b></td>
                                    <td><b v-text="val.niveauPerformanceApresPonderation"></b></td>
                                    <td align="right"><b>{{ formatMonetaire(val.smc, 'XOF') }}</b></td>
                                    <td v-if="val.isBonus" align="right">
                                        <b>{{ formatMonetaire(val.tauxAppliqueSmc, 'XOF') }}</b>
                                    </td>
                                    <td v-else align="right">
                                        <b>-</b>
                                    </td>
                                    <td v-if="val.isBonus" align="right">
                                        <b>
                                            {{ formatMonetaire(val.montantBonus, 'XOF') }}
                                        </b>
                                    </td>
                                    <td v-else align="right">
                                        <b>{{ formatMonetaire(val.montantPrime, 'XOF') }}</b>
                                    </td>
                                </tr>
                            </template>
                            <tr style="border: black 1px solid">
                                <td align="right" colspan="7"><b style="font-size: 17px;" class="text-red-600">Total
                                    Bonus</b></td>
                                <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px"
                                                     class="text-black">{{
                                        formatMonetaire(form.sommeBonusBenificiaire, 'XOF')
                                    }}</b></td>
                            </tr>
                            <tr style="border: black 1px solid">
                                <td align="right" colspan="7"><b style="font-size: 17px;" class="text-red-600">Prime
                                    Exceptionnelle</b></td>
                                <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px"
                                                     class="text-black">{{ formatMonetaire(primeExcept, 'XOF') }}</b>
                                </td>
                            </tr>
                            <tr style="border: black 1px solid">
                                <td align="right" colspan="7"><b style="font-size: 17px;"
                                                                 class="text-red-600">Budget</b></td>
                                <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px"
                                                     class="text-black">{{
                                        formatMonetaire(form.budgetBonus - 0, 'XOF')
                                    }}</b></td>
                            </tr>
                            <tr style="border: black 1px solid">
                                <td align="right" colspan="7"><b style="font-size: 17px;" class="text-red-600">Ecart</b>
                                </td>
                                <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px"
                                                     class="text-black">{{
                                        formatMonetaire(form.budgetBonus - form.sommeBonusBenificiaire - primeExcept, 'XOF')
                                    }}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4 vl">
                        <h5 class="alert alert-info">RESUME</h5>
                        <table style="border: white 2px solid;" class="table table-bordered table-striped">
                            <tbody>
                            <tr style="border: white 2px solid;">
                                <td style="border: white 2px solid;">Taux Résultat</td>
                                <td style="border: white 2px solid;">{{ dataCagnotte.tautResultat }} %</td>
                            </tr>
                            <tr style="border: white 2px solid;">
                                <td style="border: white 2px solid;">Somme SB</td>
                                <td style="border: white 2px solid;">
                                    {{ formatMonetaire(dataCagnotte.sommeSB, 'XOF') }}
                                </td>
                            </tr>
                            <tr style="border: white 2px solid;">
                                <td style="border: white 2px solid;">Budget</td>
                                <td style="border: white 2px solid;">{{ formatMonetaire(dataCagnotte.budget, 'XOF') }}
                                </td>
                            </tr>
                            <tr style="border: white 2px solid;">
                                <td style="border: white 2px solid;">3% Somme SB</td>
                                <td style="border: white 2px solid;">
                                    {{ formatMonetaire(dataCagnotte.montantTroisPercent, 'XOF') }}
                                </td>
                            </tr>
                            <tr style="border: white 2px solid;">
                                <td style="border: white 2px solid;">Cagnotte</td>
                                <td style="border: white 2px solid;">
                                    {{ formatMonetaire(dataCagnotte.montantCagnotte, 'XOF') }}
                                </td>
                            </tr>
                            <tr style="border: white 2px solid;">
                                <td style="border: white 2px solid;">Taux Perf A</td>
                                <td style="border: white 2px solid;">{{ dataCagnotte.tauxPerfA }} %</td>
                            </tr>
                            <tr style="border: white 2px solid;">
                                <td style="border: white 2px solid;">Taux perf B</td>
                                <td style="border: white 2px solid;">{{ dataCagnotte.tauxPerfB }} %</td>
                            </tr>
                            <tr style="border: white 2px solid;">
                                <td style="border: white 2px solid;">Total SMC Perf A</td>
                                <td style="border: white 2px solid;">
                                    {{ formatMonetaire(dataCagnotte.totalSMCperfA, 'XOF') }}
                                </td>
                            </tr>
                            <tr style="border: white 2px solid;">
                                <td style="border: white 2px solid;">Total SMC perf B</td>
                                <td style="border: white 2px solid;">
                                    {{ formatMonetaire(dataCagnotte.totalSMCperfB, 'XOF') }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div align="right">
                            <button @click="downloadliste" title="EXTRAIRE LA LISTE EN EXCEL"
                                    class="btn btn-green text-white"><b>EXTRAIRE LA LISTE EN EXCEL</b></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
         id="staticBackdropValid" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                <div style="background-color: #acb3c4"
                     class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                    <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                        <b><i class="fa fa-question-circle"></i> CONFIRMATION</b>
                    </h5>
                </div>
                <div class="modal-body">
                    <h5>Cette action est irréversible. Voulez-vous vraiment valider cette liste?</h5>
                </div>
                <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                    <button id="btn-fermer" class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                            data-bs-dismiss="modal">NON
                    </button>
                    <button v-if="!btnSendvalidRequest" id="btn-confirm-valider" @click="validerListe"
                            class="bg-red-600 hover:bg-red-600 text-white font-bold py-2 px-4">OUI
                    </button>
                    <button v-else class="bg-red-600 hover:bg-red-600 text-white font-bold py-2 px-4">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TitreApplication from "../block/TitreApplication";
import {onMounted, reactive, ref} from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import appService from "../../services/appService";
import {useStore} from "vuex";

export default {
    name: "laureatBonus",
    components: {TitreApplication},
    setup() {
        let load = ref(false);
        let listDejaValide = ref(false);
        let sendReq = ref(false);
        let btnSendvalidRequest = ref(false);
        let btnSubmit = ref(true);
        let beneficiaires = ref([]);
        let dataCagnotte = ref([]);
        let primeExcept = ref(0);
        const store = useStore();
        const form = reactive({
            tauxResultat: '',
            budgetBonus: '',
            budgetBonusFormater: '',
            sommeSalaireBase: '',
            sommeSalaireBaseFormater: '',
            troisPercentSB: '',
            cagnotteBase: 0,
            totalSCMperformanceA: 0,
            totalSMCperformanceB: 0,
            tauxPerformanceA: 0,
            tauxPerformanceB: 0,
            sommeBonusBenificiaire: 0,
        })
        onMounted(() => checkListe())

        const checkListe = () => {
            axios.get('check/liste/beneficiaire')
                .then((response) => {
                    if (response.data.listeValider === true) {
                        listDejaValide.value = true;
                        beneficiaires.value = response.data.beneficiaires;
                        form.sommeBonusBenificiaire = response.data.dataCagnotte.sommeBonus;
                        primeExcept.value = response.data.dataCagnotte.sommePrime;
                        form.budgetBonus = response.data.dataCagnotte.budget;
                        dataCagnotte.value = response.data.dataCagnotte;
                    }
                }).catch((error) => {
                if (error.response.status === 500) {
                    Swal.fire({
                        title: 'Erreur',
                        text: error.response.data.message,
                        icon: 'error',
                    });
                }
            })
        }
        const calculCagnote = async (from = '') => {
            if (from !== '') {
                form.tauxPerformanceB = 0;
            }
            if (form.tauxResultat < 0) {
                await Swal.fire({
                    title: 'Erreur',
                    text: 'Le taux du résultat annuel doit être supérieur à 0',
                    icon: 'error',
                });
            }
            if (form.tauxResultat > 100) {
                await Swal.fire({
                    title: 'Erreur',
                    text: 'Le taux du résultat annuel doit être inférieur ou égal à 100',
                    icon: 'error',
                });
            }

            if (form.tauxPerformanceA !== 0 && form.tauxPerformanceB !== 0) {
                await Swal.fire({
                    title: 'Erreur',
                    text: 'Vous devez saisir un seul taux et le système se chargera du reste',
                    icon: 'error',
                });
            }
            if (from !== '') {
                btnSubmit.value = false;
                sendReq.value = true;
            }


            let data = {
                taux: form.tauxResultat,
                salaireBase: form.sommeSalaireBase,
                BudgetBonus: form.budgetBonus,
                tauxPerformanceA: form.tauxPerformanceA,
                tauxPerformanceB: form.tauxPerformanceB,
            }

            axios.post('calcul/cagnotte', data)
                .then((response) => {
                    form.troisPercentSB = response.data.troispercentSB;
                    form.cagnotteBase = response.data.cagnotteBase;
                    form.totalSCMperformanceA = response.data.totalSMCPerfA;
                    form.totalSMCperformanceB = response.data.totalSMCPerfB;
                    form.tauxPerformanceB = response.data.tauxPerformanceB;
                    form.sommeBonusBenificiaire = response.data.sommeBonusBeneficiaire;
                    primeExcept.value = response.data.sommePrimeExcept;
                    beneficiaires.value = response.data.beneficiaires;
                    load.value = true;
                    btnSubmit.value = true;
                    sendReq.value = false;
                }).catch((error) => {
                Swal.fire({
                    title: 'Erreur serveur',
                    text: error.response.data.message,
                    icon: 'error',
                });
                btnSubmit.value = true;
                sendReq.value = false;
            })
        }

        const sommePrimeExcpt = (val) => {
            if (!val.isBonus) {
                primeExcept.value += val.montantBonus;
            }
        }

        const setValuePrimeExceptForSalarie = (val) => {
            $('#btnSetPrime' + val.matricule).attr('hidden', 'hidden');
            $('#loadSetPrime' + val.matricule).removeAttr('hidden');
            let valuePrimeExcept = $('#input' + val.matricule).val();

            axios.post('set/value/primeexcept/salarie', {valeurPrime: valuePrimeExcept, matricule: val.matricule})
                .then((response) => {
                    calculCagnote('setvalueprime')
                    $('#loadSetPrime' + val.matricule).attr('hidden', 'hidden');
                    $('#btnSetPrime' + val.matricule).removeAttr('hidden');
                }).catch((error) => {

            })
        }

        const validerListe = () => {
            btnSendvalidRequest.value = true;
            axios.get('valider/liste/beneficiaire')
                .then((response) => {
                    btnSendvalidRequest.value = false;
                    checkListe();
                }).catch((error) => {
                if (error.response.status === 500) {
                    Swal.fire({
                        title: 'Erreur',
                        text: error.response.data.message,
                        icon: 'error',
                    });
                }
            })
        }

        const formatMonetaire = (valeur, devise = 'XOF') => {
            return valeur.toLocaleString('fr-FR', {
                style: 'currency',
                currency: devise
            });
        };

        const numberFormat = (nbre, depart = 'sommeSalaire') => {
            let bcp47 = 'fr-FR', style = 'decimal', currency = 'EUR'
            let n = parseFloat(nbre)
            n = new Intl.NumberFormat(bcp47, {style: style, currency: currency}).format(n)
            if (depart === 'sommeSalaire') {
                form.sommeSalaireBase = nbre;
                form.sommeSalaireBaseFormater = n;
            } else {
                form.budgetBonus = nbre;
                form.budgetBonusFormater = n;
            }

            return n
        }
        const downloadliste = async () => {
            window.open(store.state.MIX_API_URL + 'download/liste/beneficiaire','_blank');
        }

        return {
            form,
            load,
            beneficiaires,
            btnSubmit,
            sendReq,
            btnSendvalidRequest,
            listDejaValide,
            dataCagnotte,
            calculCagnote,
            formatMonetaire,
            numberFormat,
            sommePrimeExcpt,
            primeExcept,
            setValuePrimeExceptForSalarie,
            validerListe,
            downloadliste,
        }
    }
}
</script>

<style scoped>

</style>
