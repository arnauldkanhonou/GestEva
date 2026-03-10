<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="SIMULATION BONUS" small-title="RH"
                               name-page="Calcul taux"></titre-application>
            <div>
                <form method="POST" @submit.prevent="storeRessource">
                    <div class="container offset-1 col-md-10">
                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="smt" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Somme total salaire de base<i class="text-red-600">*</i></b>
                                </label>
                                <input v-model="form.totalSalaireBase" type="number" name="totalsab" id="smt" placeholder="saisir total salaire de base"
                                       class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="smc" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Pourcentage SMC<i class="text-red-600">*</i></b>
                                </label>
                                <input v-model="form.percentSmc" type="number" name="percentsmc" id="smc" placeholder="code du pole"
                                       class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="smt" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Total smc performance A<i class="text-red-600">*</i></b>
                                </label>
                                <input v-model="form.totalSmcPerformanceA" type="number" name="totalPA" id="TsmcPA" placeholder="saisir total salaire de base"
                                       class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="smc" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Total smc performance B<i class="text-red-600">*</i></b>
                                </label>
                                <input v-model="form.totalSmcPerformanceB" type="number" name="totalPB" id="TsmcPB" placeholder="saisir total salaire de base "
                                       class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="smt" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Pourcentage applicable<i class="text-red-600">*</i></b>
                                </label>
                                <input v-model="form.percenteApplicable" type="number" name="percentA" id="prcA" placeholder="saisir pourcentage"
                                       class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="smc" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Montant Bonus Prévisionnel</b>
                                </label>
                                <input v-model="form.bonusPrevisionnel" type="number" name="bonusPrev" id="MbP" placeholder="saisir budget prévisionnel "
                                       class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                            </div>
                        </div>
                        <!--<div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="smt" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Montant Prime Exceptionnel</b>
                                </label>
                                <input v-model="form.primeExcept" type="text" name="primeExcep" id="MpE" placeholder="saisir montant de la prime exceptionnel"
                                       class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                />
                            </div>
                        </div>-->

                        <div class="mt-2 offset-1 col-md-10">
                            <button  class="btn-green col-md-12 rounded-md py-2 px-20 text-white align-content-center'" type="submit">
                                <b id="btn-simuler">Simuler</b>
                            </button>
                        </div>
                    </div>
                </form>
                <hr>
                <div v-if="tabSimulation.length>0">
                    <div v-for="simulation in tabSimulation">
                        <div style="background-color: #0a6779" class="alert alert-default-success">
                            <b class="text-white uppercase">Simulation de {{simulation.percenteApplicable}} %</b>
                        </div>
                        <table class="min-w-full leading-normal table table-bordered table-striped"
                               style="background-color: #acb3c4">
                            <thead>
                            <tr>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>montant Theorique Cagnotte</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>montant Cagnotte</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>taux Performance A</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>taux Performance B</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Total Bonus</b>
                                </th>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Prime Exceptionnelle</b>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="px-3 py-2 border-b border-gray-200 bg-white text-md">
                                    <b  class="text-red-600">{{simulation.montantTheoriqueCagnotte}}</b>
                                </td>

                                <td class="px-3 py-2 border-b border-gray-200 bg-white text-md">
                                    <b class="text-red-600">{{simulation.montantCagnotte}}</b>
                                </td>

                                <td class="px-3 py-2 border-b border-gray-200 bg-white text-md">
                                    <b class="text-red-600">{{simulation.tauxPerformanceA}}</b>
                                </td>

                                <td class="px-3 py-2 border-b border-gray-200 bg-white text-md">
                                    <b class="text-red-600">{{simulation.tauxPerformanceB}}</b>
                                </td>

                                <td class="px-3 py-2 border-b border-gray-200 bg-white text-md">
                                    <b class="text-red-600">{{simulation.bonusTotal}}</b>
                                </td>

                                <td class="px-3 py-2 border-b border-gray-200 bg-white text-md">
                                    <b class="text-red-600">{{simulation.primeExcept}}</b>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import TitreApplication from "../block/TitreApplication";
    import {reactive, ref} from "vue";
    import axios from "axios";
    import Swal from "sweetalert2";
    import  VueNumberFormat from  "vue-number-format"
    import appService from "../../services/appService";

    export default {
        name: "bonus",
        components: {TitreApplication},
        setup(){
            const form = reactive({
                totalSalaireBase: 0,
                percentSmc: 0,
                totalSmcPerformanceA: 0,
                totalSmcPerformanceB: 0,
                percenteApplicable: 0,
                bonusPrevisionnel: 0,
                primeExcept: 0,
                montantTheoriqueCagnotte:0,
                montantCagnotte:0,
                tauxPerformanceA:0,
                tauxPerformanceB:0,
                ecart:0,
                bonusTotal:0,
                load:false

            })
            const tabSimulation = ref([]);
            const sleep = (ms) => {
                return new Promise(resolve => setTimeout(resolve, ms));
            }
            const {catchErrors} = appService();
            const succesMessage = ref('');
            const storeRessource = async () => {
                $('#btn-simuler').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                sleep(400).then(() => {
                    axios.post('calcul/bonus', form)
                        .then((response)=>{
                            $('#btn-simuler').html('Simuler')
                            if (response.data.data.code===500){
                                Swal.fire({
                                    title: 'Erreur',
                                    text: response.data.data.message,
                                    icon: 'error',
                                });
                            }else {
                                let resp = response.data.data;
                                form.montantTheoriqueCagnotte = new Intl.NumberFormat().format(resp.montantTheoriqueCagnotte);
                                form.montantCagnotte =  new Intl.NumberFormat().format(resp.montantCagnotte);
                                form.tauxPerformanceA =  new Intl.NumberFormat().format(resp.tauxPerformanceA);
                                form.tauxPerformanceB =  new Intl.NumberFormat().format(resp.tauxPerformanceB);
                                //form.ecart =  new Intl.NumberFormat().format(resp.ecart) ;
                                form.primeExcept =  new Intl.NumberFormat().format(resp.primeExcept);
                                form.bonusTotal =  new Intl.NumberFormat().format(resp.bonusTotal);
                                form.load = true;
                                tabSimulation.value.push({
                                    montantTheoriqueCagnotte:form.montantTheoriqueCagnotte,
                                    montantCagnotte:form.montantCagnotte,
                                    tauxPerformanceA:form.tauxPerformanceA,
                                    tauxPerformanceB:form.tauxPerformanceB,
                                    primeExcept:form.primeExcept,
                                    bonusTotal:form.bonusTotal,
                                    percenteApplicable:form.percenteApplicable,
                                });
                                console.log(tabSimulation.value)
                            }
                        }).catch((error) => {
                        catchErrors(error, succesMessage);
                    })
                });
            }


            return {
                form,
                tabSimulation,
                storeRessource
            }
        }
    }
</script>

<style scoped>

</style>
