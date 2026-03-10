<template>
    <titre-application big-title="CREATION MISSION" small-title="Formulaire-Création" name-page="Mission"></titre-application>
    <div class="container flex items-center justify-center p-12">
        <div class="container">
            <div class=" offset-1 col-md-10 ">
                <div class="alert alert-danger" v-if="tabError">
                    <strong >{{ tabError }}</strong>
                </div>
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
                <div v-if="tabMission.length>0">
                    <br>
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
                                    v-text="mission.libelle">
                                </td>
                                <td class="py-1 border-b border-gray-200 bg-white text-sm">
                                    <button @click="updateMission(index)"  class="float-end bg-green-600 hover:bg-green-600 text-white px-3 py-2 rounded">
                                        <i class="fa fa-edit"></i>&nbsp;&nbsp;
                                        Modifier
                                    </button>
                                    <button @click="deleteMission(index)"  class="float-end bg-red-600 hover:bg-red-600 text-white px-3 py-2 rounded">
                                        <i class="fa fa-trash"></i>&nbsp;&nbsp;
                                        Retirer
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <div class="row">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Sélectionnez l'année de vos missions<i class="text-red-600">*</i></b>
                        </label>
                        <div class="col-md-12">
                            <select v-model="form.annee" name="" id="annee" class="rounded-md form-control">
                                <option value="" disabled>Sélectionnez l'année</option>
                                <option v-for="annee in annees" :key="annee.id" :value="annee.id">
                                    <b>{{annee.libelle}}</b>
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <button @click="storeRessource" v-if="tabMission.length>0" :disabled="tabMission.length===0" :class="tabMission.length===0?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'" type="submit"  class="btn-green col-md-12">
                        <b id="btn-save">Enregistrer</b>
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import {ref, reactive, onMounted} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useMission from "../../services/missionservices";
    import useUpdateVariable from "../../services/cleanVariable";
    import TitreApplication from "../block/TitreApplication";
    import useCampagneObjectif from "../../services/campagneObjectif";
    import useCollaborateur from "../../services/collaborateurs";
    import Swal from "sweetalert2";

    export default {
        name: "MissionCreate",
        components:{TitreApplication},
        props: {
            idCol: {
                required: false,
                type:String,
                default:'0',
            },
            intituleCol: {
                required: false,
                type: String,
                default: ''
            }
        },
        setup(props){
            const form = reactive({
                libelle:'',
                annee:''
            })

            const tabMission = ref([]);
            const {validateNameField,Errors} = useFormValidation();

            const {cleanVar} = useUpdateVariable(Errors,form);
            onMounted(cleanVar);

            const {annees,getAnnes} = useCampagneObjectif();
            onMounted(getAnnes);

            const {isFormButtonDisabled} = confirmForm(form,Errors);

            const {tabError,createMission} = useMission();

            const validateInputlibelle = ()=>{
                validateNameField('libelle',form.libelle)
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

            const deleteMission = (index) =>{
                 tabMission.value.splice(index,1);
            }
            const updateMission = (index) =>{
                form.libelle=tabMission.value[index].libelle;
                tabMission.value.splice(index,1);
            }

            const storeRessource = async () =>{
                if (form.annee===''){
                    Swal.fire({
                        title: 'error',
                        text: 'Veuillez sélectionner l\'année',
                        icon: 'error',
                    });
                    return ;
                }
                let data = {
                    missions: tabMission.value,
                    annee: form.annee,
                    idColab: props.idCol,
                    intituleColab: props.intituleCol,
                }
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await createMission(data,tabMission.value.length)
            }

            return{
                form,
                tabError,
                Errors,
                tabMission,
                annees,
                isFormButtonDisabled,
                addMission,
                deleteMission,
                validateInputlibelle,
                storeRessource,
                updateMission,
            }
        }
    }
</script>

<style scoped>

</style>
