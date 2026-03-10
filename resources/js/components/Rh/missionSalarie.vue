<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="MISSIONS SALARIE" small-title="Missions" :name-page="intitule"></titre-application>
            <div>
                <div class="mb-2">
                    <router-link :to="{name:'employes.index'}"
                                 class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                        <i class="fa fa-backspace"></i> Retour
                    </router-link> &nbsp;&nbsp;&nbsp;
<!--                    <button title="Télécharger" class="btn-sm bg-blue-600 text-white px-3 py-2 rounded">
                        <i class="fa fa-file-pdf"></i>&nbsp;&nbsp;EXTRAIRE
                    </button>-->
                </div>
                <div class="py-1">
                    <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                    <div v-else-if="missions.length>0" class="shadow  overflow-hidden">
                        <table class="min-w-full leading-normal table table-bordered table-striped"
                               style="background-color: #acb3c4">
                            <thead>
                            <tr>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>ID</b>
                                </th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                    <b>Libelle</b>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="mission in missions" :key="mission.id">
                                <tr>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md">
                                        <strong v-if="mission.valider" class="btn-green text-white"><i
                                                class="fa fa-check"></i></strong>
                                    </td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="mission.libelle"></td>
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
    import {computed, onMounted, reactive} from "vue";
    import useMission from "../../services/missionservices";
    import TitreApplication from "../block/TitreApplication";
    import {ref} from "@vue/reactivity";
    import useCollaborateur from "../../services/collaborateurs";

    export default {
        name: "ValidationMission",
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
            const {succesMessage,deleteMission, validateMission} = useMission();
            const {loadRessource,missions, getMissionCollaborateur} = useCollaborateur();

            onMounted(()=>{getMissionCollaborateur(props.id)});

            return {
                missions,
                loadRessource,
                succesMessage,
            }
        }
    }
</script>

<style scoped>

</style>
