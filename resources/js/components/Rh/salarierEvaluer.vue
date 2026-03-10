<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="SALARIés EVALUés" small-title="Salariés"
                               name-page="évalués"></titre-application>
            <div>
                <div class="mb-2">
                    <div class="row">
                        <div class="mb-2 col-md-4">
                            <br>
                            <router-link :to="{name:'dashboard.admin'}"
                                         class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                                <i class="fa fa-backspace"></i> Retour
                            </router-link> &nbsp;&nbsp;&nbsp;
<!--                            <button title="Télécharger" class="btn-sm bg-blue-600 text-white px-3 py-2 rounded">
                                <i class="fa fa-file-pdf"></i>&nbsp;&nbsp;EXTRAIRE
                            </button>-->
                        </div>
                        <div class="col-md-2">
                            <label for=""><strong class="uppercase">Filtre Par affichage</strong></label>
                            <select name="" id="" class="form-select">
                                <option value="">10</option>
                                <option value="">20</option>
                                <option value="">50</option>
                                <option value="">75</option>
                                <option value="">100</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for=""><strong class="uppercase">Type</strong></label>
                            <select @change="loadSalarieEvaluer" v-model="form.type" name=""  class="form-select">
                                <option value="evaluer">Evalué</option>
                                <option value="nonEvaluer">Non évalué</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for=""><strong>CAMPAGNE PERFORMANCE</strong></label>
                            <select @change="loadSalarieEvaluer" v-model="form.annee" name="" class="form-select">
                                <option selected value="" disabled>Sélectionnez une annéé</option>
                                <option v-for="annee in annees" :key="annee.id" :value=annee.id>
                                    {{ annee.libelle }}
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="py-1">
                    <div class="alert alert-success">
                        <strong class="uppercase" v-if="form.type==='evaluer'"> Liste des salariés <b class="text-red-600">évalués</b> </strong>
                        <strong class="uppercase" v-else> Liste des salariés <b class="text-red-600">non évalués</b> </strong>
                    </div>
                    <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                    <div v-else-if="form.salarieEvaluer.length>0">
                        <table class="mt-4 min-w-full leading-normal table table-bordered table-striped"
                               style="background-color:#acb3c4 ">
                            <thead>
                            <tr>
                                <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b class="text-white">Code</b>
                                </th>
                                <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b class="text-white">Nom</b>
                                </th>
                                <th class="py-2 px-6 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b class="text-white">Prénoms </b>
                                </th>
                                <th class="py-2 px-6 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b class="text-white">Unité </b>
                                </th>
                                <th class="py-2 px-6 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b class="text-white">Performance </b>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="salarie in form.salarieEvaluer">
                                <tr>
                                    <td class="py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="salarie.matricule"></td>
                                    <td class="py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="salarie.nom"></td>
                                    <td class="py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="salarie.prenoms">
                                    </td>
                                    <td class="py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="salarie.unite">
                                    </td>
                                    <td align="right" class="py-2 border-b border-gray-200 bg-white text-md">
                                        <b v-text="salarie.performance"></b>
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
        </div>
    </div>
</template>

<script>
    import useCampagneObjectif from "../../services/campagneObjectif";
    import {onMounted, ref} from "vue";
    import TitreApplication from "../block/TitreApplication";
    import {reactive} from "@vue/reactivity";
    import axios from "axios";
    import Swal from "sweetalert2";

    export default {
        name: "salarierEvaluer",
        components: {TitreApplication},
        props: {
            annee: {
                required: true,
                type: String,
            }
        },
        setup(props) {
            const form = reactive({
                annee: '',
                salarieEvaluer:[],
                type:'evaluer',
            })
            const {annees, getAnnes} = useCampagneObjectif();
            const loadRessource = ref(false)
            onMounted(getAnnes);
            onMounted(() => {
                form.annee = props.annee;
                loadSalarieEvaluer();
            })

            const loadSalarieEvaluer = () => {
                form.salarieEvaluer = [];
                axios.get('liste/salarier/' + form.annee+'/'+form.type)
                    .then((response) => {
                        if (response.data.data.code === 500){
                            Swal.fire({
                                title: 'ERROR',
                                text: response.data.data.message,
                                icon: 'error',
                            });
                        }else {
                            if (response.data.data.listes.length>0){
                                form.salarieEvaluer = response.data.data.listes;
                            }
                        }
                        loadRessource.value = true;
                    })
            }

            return {
                annees,
                form,
                loadRessource,
                loadSalarieEvaluer
            }
        }
    }
</script>

<style scoped>

</style>
