<template>
    <div>
        <titre-application big-title="LISTE COLLABORATEURS" small-title="Collaborateurs" name-page="Liste"></titre-application>
        <div v-if="messages!==''" class="alert alert-success">
            <strong> {{ messages }} </strong>
        </div>
        <div>

            <div class="mb-2">
                <div class="row">
                    <div class="mt-1 col-md-4">
                        <br>
                        <router-link :to="{name:'evaluateur.index'}"
                                     class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                            <i class="fa fa-backspace"></i> Retour
                        </router-link>
                    </div>

                </div>
                <!--style="background-color: #058069"-->
<!--                <div class="row">
                    <div class="col-md-1 offset-7">
                        <select name="" id="" class="form-select">
                            <option value="">10</option>
                            <option value="">20</option>
                            <option value="">50</option>
                            <option value="">75</option>
                            <option value="">100</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Rechercher un employé par son nom/prénom ou code">
                    </div>
                </div>-->

            </div>
            <div class="py-1">
                <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                    <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                    <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                </div>
                <div v-else-if="collaborateurs.length>0" class="shadow2">
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
                                <b>Email</b>
                            </th>
                             <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                 <b>date Emb.</b>
                             </th>
                            <th class="px-3 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b>Action</b>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="collaborateur in collaborateurs" :key="collaborateur.id">
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
                                    {{ collaborateur.email }}
                                </td>
                                 <td class="py-1 border-b border-gray-200 bg-white text-md">
                                     {{ collaborateur.dateEmbauche }}
                                 </td>
                                <td class="py-1 px-3 border-b border-gray-200 bg-white text-sm">
                                    <div v-if="idColab==='0'" class="btn-group inline-flex">
                                        <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-3 rounded-l dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            ACTION
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><router-link :to="{ name: 'collaborateur.mission', params: { id: collaborateur.id, intitule:collaborateur.nom+' '+collaborateur.prenoms  } }" class="dropdown-item" > <i class="fa fa-user-edit"></i> Missions</router-link></li>
                                            <li><router-link :to="{ name: 'collaborateur.objectifs', params: { id: collaborateur.id, intitule:collaborateur.nom+' '+collaborateur.prenoms  } }" class="dropdown-item" > <i class="fa fa-user-edit"></i> Objectifs</router-link></li>
                                            <li><router-link :to="{ name: 'auto.evaluation.salarie.by.responsable', params: { id: collaborateur.id, intitule:collaborateur.nom+' '+collaborateur.prenoms  } }" class="dropdown-item" > <i class="fa fa-user-edit"></i>Evaluation annuelle</router-link></li>
                                            <li><router-link :to="{ name: 'evaluation.miparcours.by.responsable', params: { id: collaborateur.id, intitule:collaborateur.nom+' '+collaborateur.prenoms  } }" class="dropdown-item" > <i class="fa fa-user-edit"></i>Evaluation mi-parcous</router-link></li>
                                            <li><router-link :to="{ name: 'entretien.evaluation', params: { id: collaborateur.id, intitule:collaborateur.nom+' '+collaborateur.prenoms  } }" class="dropdown-item" > <i class="fa fa-user-edit"></i>Entretien</router-link></li>
                                            <li><router-link :to="{ name: 'collaborateur.evaluations', params: { id: collaborateur.id, intitule:collaborateur.nom+' '+collaborateur.prenoms  } }" class="dropdown-item" > <i class="fa fa-user-edit"></i>Historique d'eval.</router-link></li>
<!--                                            <li><a  class="dropdown-item" href="#"><i class="fa fa-eye-slash"></i> Visualiser</a></li>-->
                                        </ul>
                                    </div>
                                    <div v-else class="btn-group inline-flex">
                                        <button
                                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-3 rounded-l dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            ACTION
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <router-link :to="{ name: 'employes.edit', params: { id: collaborateur.id } }"
                                                             class="dropdown-item"><i class="fa fa-user-edit"></i>
                                                    Profile du salarié
                                                </router-link>
                                            </li>
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
    </div>
</template>

<script>
    import useCollaborateur from "../../services/collaborateurs";
    import {onMounted} from "vue";
    import TitreApplication from "../block/TitreApplication";
    import axios from "axios";
    import Swal from "sweetalert2";

    export default {
        name: "CollaborateurIndex",
        components: {TitreApplication},
        props: {
            messages: {
                required: false,
                type: String,
                default: '',
            },
            idColab: {
                required: false,
                type: String,
                default: '0'
            }
        },
        setup(props){
            let user = JSON.parse(sessionStorage.getItem('user'));
            let user_id = user.id;
            let option = 'user'
            if (props.idColab!=='0'){
                user_id = props.idColab;
                option = 'rh'
            }

            const {loadRessource,collaborateurs,getCollaborateurs} = useCollaborateur();
            onMounted(()=>getCollaborateurs(user_id,option));
            return{
                collaborateurs,
                loadRessource
            }
        }
    }
</script>

<style scoped>

</style>
