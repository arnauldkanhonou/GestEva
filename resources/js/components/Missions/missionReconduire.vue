<template>
    <titre-application big-title="RECONDUIRE MISSION" small-title="Formulaire-Création" name-page="Mission"></titre-application>
    <div class="alert alert-warning">
        <strong class="text-uppercase"> Veuillez reconduire les missions de l'an passé ou modifier au besoin. </strong>
    </div>
    <div class="col-md-10 offset-1">
        <div class="mb-4">
            <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                <b> Intitulé de la mission<i class="text-red-600">*</i></b>
            </label>
            <div class="row">
                <div class="col-md-9">
                    <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle" @blur="validateInputlibelle" v-model="form.libelle"  type="text" name="libelle" id="libelle" placeholder="saisir l'intitulé de la mission"
                           class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                    <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                </div>
                <div class="col-md-3">
                    <button @click="addMission" class="btn-green py-2 px-2 text-white align-content-center" type="submit">
                        <b id="btn-add"><i class="fa fa-plus"></i> Ajouter</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="tabMission.length>0">
        <div class="row">
            <div class="col-md-10 offset-1">
                <table class="min-w-full leading-normal table table-bordered table-striped" style="background-color: #556e6e">
                    <thead>
                    <tr>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Libelle</b>
                        </th>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Action</b>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(mission,index) in tabMission" :key="mission.id">
                        <tr>
                            <td class="py-1 border-b border-gray-200 bg-white text-md"
                                v-text="mission.libelle"></td>
                            <td class="py-1 border-b border-gray-200 bg-white text-sm">
                                &nbsp;&nbsp;<button @click="getMissionForUpdate(index)" title="MODIFIER LA MISSION" class="float-end bg-green-400 hover:bg-green-400 text-white px-3 py-2 rounded"><i class="fa fa-edit"></i></button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <div class="mt-5">
                    <button @click="storeRessource" v-if="tabMission.length>0" :disabled="tabMission.length===0" :class="tabMission.length===0?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'" type="submit"  class="btn-green col-md-12">
                        <b id="btn-save">J'ENREGISTRE MES MISSIONS DE L'ANNEE EN COURS</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="col-md-2 offset-5 mt-4">
        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
    </div>
</template>

<script>
    import TitreApplication from "../block/TitreApplication";
    import useMission from "../../services/missionservices";
    import {onMounted, reactive, ref} from "vue";
    import axios from "axios";
    import useFormValidation from "../../validation/useFormValidation";
    import useUpdateVariable from "../../services/cleanVariable";
    import Swal from "sweetalert2";

    export default {
        name: "missionReconduire",
        components:{TitreApplication},
        props: {
            annee_id: {
                required: true,
                type:String,
            }
        },
        setup(props){
            const form = reactive({
                libelle:'',
                annee:''
            })
            const tabMission = ref([]);
            let user = JSON.parse(sessionStorage.getItem('user'));

            const {validateNameField,Errors} = useFormValidation();
            const {cleanVar} = useUpdateVariable(Errors,form);
            onMounted(cleanVar);
            const {tabError,missions,createMission} = useMission();

            onMounted(()=>{
                axios.post('missions/areconduire',{user_id:user.id,annne_id:props.annee_id})
                    .then((response)=>{
                        missions.value = response.data;
                        missions.value.forEach((item)=>{
                            tabMission.value.push({
                                id:item.id,
                                libelle:item.libelle,
                                annee:item.annee_id
                            })
                        })
                    })
            });

            const validateInputlibelle = ()=>{
                validateNameField('libelle',form.libelle)
            }

            const getMissionForUpdate = (index_send) =>{
                tabMission.value.forEach(function (item,index) {
                    if (index === index_send &&  form.libelle===''){
                        form.libelle = item.libelle;
                        deleteMission(index)
                    }
                });

            }

            const deleteMission = (index) =>{
                tabMission.value.splice(index,1);
            }

            const addMission = ()=>{
                if (form.libelle===''){
                    erreur = true;
                    Swal.fire({
                        title: 'error',
                        text: 'Veuillez saisir votre mission! Merci\'',
                        icon: 'error',
                    });
                    return 0;
                }

                let erreur = false;
                tabMission.value.forEach(function (item,indexe) {
                    if (item.libelle === form.libelle && erreur===false){
                        erreur = true;
                    }
                });
                if (!erreur){
                    tabMission.value.push({...form});
                    form.libelle='';
                }else{
                    Swal.fire({
                        title: 'error',
                        text: 'Cette mission existe déjà dans la liste. Veuillez saisir autre mission',
                        icon: 'error',
                    });
                }
            }

            const storeRessource = async () =>{
                let data = {
                    missions: tabMission.value,
                    annee: props.annee_id,
                    idColab: '0',
                }
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await createMission(data,tabMission.value.length,true)
            }

            return {
                tabError,
                tabMission,
                Errors,
                form,
                validateInputlibelle,
                getMissionForUpdate,
                addMission,
                storeRessource,
            }
        }
    }
</script>

<style scoped>

</style>
