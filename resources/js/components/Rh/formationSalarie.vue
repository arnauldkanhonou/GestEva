<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="EVALUATIONS SALARIE" small-title="Evaluations" :name-page="intitule"></titre-application>
            <div>
                <div v-if="messages!==''" class="alert alert-success">
                    <strong> {{ messages }} </strong>
                </div>
                <div class="mb-2">
                    <div class="row">
                        <div class="mt-1 col-md-4">
                            <br>
                            <router-link :to="{name:'employes.index'}"
                                         class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                                <i class="fa fa-backspace"></i> Retour
                            </router-link>
                        </div>

<!--                        <div class="col-md-3 offset-1">
                            <label for=""><strong>Filtre Par affichage</strong></label>
                            <select name="" class="form-select">
                                <option value="">10</option>
                                <option value="">20</option>
                                <option value="">50</option>
                                <option value="">75</option>
                                <option value="">100</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for=""><strong>Filtrer Par Années</strong></label>
                            <select name="" id="" class="form-select">
                                <option selected value="" disabled>Sélectionnez une annéé</option>
                                <option v-for="annee in annees" :key="annee.id" :value=annee.id>
                                    {{ annee.libelle }}
                                </option>
                            </select>
                        </div>-->
                    </div>
                </div>

                <div class="py-1">
                    <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                    <div v-else-if="formationsCollaboateur.length>0" class="shadow2">
                        <table class="min-w-full leading-normal table table-bordered table-striped"
                               style="background-color: #acb3c4">
                            <thead>
                            <tr>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Libellé</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Objectif</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Bilan</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>année</b>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="formation in formationsCollaboateur" :key="formation.id">
                                <tr>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="formation.libelle"></td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="formation.objectifVise"></td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="formation.appreciation"></td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="formation.annee"></td>

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
    import {ref} from "@vue/reactivity";
    import useCollaborateur from "../../services/collaborateurs";
    import {onMounted, reactive} from "vue";
    import TitreApplication from "../block/TitreApplication";
    import axios from "axios";

    export default {
        name: "formationSalarie",
        components: {TitreApplication},
        props: {
            messages: {
                required: false,
                default: ''
            },
            id: {
                required: true,
                type: String,
            },
            intitule: {
                required: false,
                type: String,
            }
        },
        setup(props) {
            const intitule = ref(props.intitule);
            let user = JSON.parse(sessionStorage.getItem('user'));
            const {loadRessource,annees,formationsCollaboateur,getAnnees,getFormationSuivieCollaborateur} = useCollaborateur();

            onMounted(getAnnees)

            onMounted(()=>getFormationSuivieCollaborateur(props.id))


            return {
                formationsCollaboateur,
                annees,
                loadRessource,
            }
        }
    }
</script>

<style scoped>

</style>
