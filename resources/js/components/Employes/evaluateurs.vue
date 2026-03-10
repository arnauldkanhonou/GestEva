<template>
    <div>
        <titre-application big-title="LISTE DES EVALUATEURS" small-title="Evaluateurs"
                           name-page="Liste"></titre-application>
    </div>
    <div>
        <div class="mb-2">
            <div class="row">
                <div class="offset-3 col-md-3">
                    <select v-model="service_id" @change="onchangeService" id="service" class="rounded-md form-select">
                        <option value="" readonly>Sélectionnez le service</option>
                        <option  v-for="service in services" :key="service.id" :value="service.id">
                            {{service.libelle}}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input v-model="req" type="text" class="form-control" @keyup="search"
                           placeholder="Rechercher un évaluateur par son nom/prénom ou code">
                </div>
            </div>
        </div>
        <div class="mb-2">
            <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
            </div>
            <div v-else-if="evaluateurs.length>0" class="shadow2">
                <table class="min-w-full leading-normal table table-bordered table-striped"
                       style="background-color:#acb3c4 ">
                    <thead>
                    <tr>
                        <th class="px-2 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b>code</b>
                        </th>
                        <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b>Nom</b>
                        </th>
                        <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b>Prénoms</b>
                        </th>
                        <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b>Service/unite</b>
                        </th>
                        <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b>Poste</b>
                        </th>
                        <th class="px-3 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b>Action</b>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="collaborateur in evaluateurs" :key="collaborateur.id">
                        <tr>
                            <td class="py-1 border-b text-center border-gray-200 bg-white text-md font-bold"
                                v-text="collaborateur.matricule"></td>
                            <td class="py-1 border-b border-gray-200 bg-white text-md">
                                {{ collaborateur.nom }}
                            </td>
                            <td class="py-1 border-b border-gray-200 bg-white text-md">
                                {{ collaborateur.prenoms }}
                            </td>
                            <td class="py-1 border-b border-gray-200 bg-white text-md">
                                {{ collaborateur.service }}
                            </td>
                            <td class="py-1 border-b border-gray-200 bg-white text-md">
                                {{ collaborateur.poste }}
                            </td>
                            <td class="py-1 px-3 border-b border-gray-200 bg-white text-sm">
                                <div class="btn-group inline-flex">
                                    <button
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-3 rounded-l dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        ACTION
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <router-link
                                                :to="{ name: 'collaborateurs.index', params: { idColab: collaborateur.id} }"
                                                class="dropdown-item"><i class="fa fa-user-edit"></i> collaborateurs
                                            </router-link>
                                        </li>
                                        <!--                                            <li><a  class="dropdown-item" href="#"><i class="fa fa-eye-slash"></i> Visualiser</a></li>-->
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <br>
                <br>
                <br>
            </div>
            <div v-else class="alert alert-info">
                <b class="text-red-600 uppercase">Aucune ressource disponible dans la base de donnée</b>
            </div>
        </div>
    </div>
</template>

<script>
    import TitreApplication from "../block/TitreApplication.vue";
    import useCollaborateur from "../../services/collaborateurs";
    import {onMounted, ref} from "vue";
    import useServices from "../../services/services";

    export default {
        name: "evaluateurs",
        components: {TitreApplication},
        setup(){
            const service_id = ref('');
            const req = ref('');
            const {loadRessource,evaluateurs,getEvaluateurs,getEvaluateursByService,searchEvaluateur} = useCollaborateur();
            const {services, getServices} = useServices();
            onMounted(getEvaluateurs);
            onMounted(getServices);

            const onchangeService = () => {
                getEvaluateursByService(service_id.value);
            }

            const search = ()=>{
                searchEvaluateur(req.value);
            }

            return{
                req,
                service_id,
                services,
                evaluateurs,
                loadRessource,
                onchangeService,
                search,
            }
        }
    }
</script>

<style scoped>

</style>
