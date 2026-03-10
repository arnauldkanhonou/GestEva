<template>
    <titre-application big-title="CREATION FORMATIONS" small-title="Formulaire-Création" name-page="Formation"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-1 col-md-10 ">
                <div class="alert alert-danger" v-if="tabError">
                    <strong >{{ tabError }}</strong>
                </div>
                <div class=" mb-4">
                    <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Type de formation<i class="text-red-600">*</i></b>
                    </label>
                    <select v-model="form.typeFormation" name="" id="" class="rounded-md form-control form-select">
                        <option value="" readonly>Sélectionnez le type de formation</option>
                        <option v-for="type in  typeformations" :key="type.id" :value="type.id">
                            {{type.libelle}}
                        </option>
                    </select>
                </div>

                <div class=" mb-4">
                    <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Intitulé de la formation<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle" @blur="validateInputlibelle" v-model="form.libelle"  type="text" name="libelle" id="libelle" placeholder="saisir l'intitulé de la formation"
                                   class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                    </div>
                    <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                </div>

                <div class="mb-4">
                    <label for="objectif" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Objectif de la formation<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <textarea :class="Errors.objectifVise?'is-invalid':''" @keyup="validateInputobjectif" @blur="validateInputobjectif" v-model="form.objectifVise"  type="text" name="objectif" id="objectif" placeholder="Saisir l'objectif de la formation"
                                   class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="unite" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Employés concernées<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <select name="" id="unite" multiple  class="form-control select2">
                                <option v-for="employe in employes.data" v-bind:key="employe.id" :value="employe.id">({{employe.matricule}}) {{ employe.nom }} {{ employe.prenoms }}</option>
                            </select>
                        </div>
                    </div>
                    <strong v-if="Errors.tabEmploye" class="text-red-600">{{Errors.tabEmploye }}</strong>
                </div>

                <div class="mb-4">
                    <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Date de la formation<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <input :class="Errors.dateFormation?'is-invalid':''" @keyup="validateInputdate" @blur="validateInputdate" v-model="form.dateFormation"  type="date" name="dateFormation" id="dateFormation" placeholder="Saisir la date de la formation"
                                   class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                    </div>
                    <strong v-if="Errors.dateFormation" class="text-red-600">{{Errors.dateFormation }}</strong>
                </div>

                <div class="mb-4">
                     <div class="col-md-12">
                           <button @click="addFormation" class="float-end btn-green text-white py-2 px-2 ' align-content-center" >
                               <b><i class="fa fa-plus"></i> Ajouter</b>
                           </button>
                       </div>
                    <br>
                </div>
            </div>
            <br>
            <hr>
            <div>
                <table v-if="tabFormation.length>0" class="min-w-full leading-normal table table-bordered table-striped" style="background-color: #556e6e">
                    <thead>
                    <tr>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Libelle</b>
                        </th>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Objectif</b>
                        </th>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Employé</b>
                        </th>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Date</b>
                        </th>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Action</b>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(formation,index) in tabFormation" :key="formation.id">
                        <tr>
                            <td class="py-1 border-b border-gray-200 bg-white text-md"
                                v-text="formation.libelle"></td>
                            <td class="py-1 border-b border-gray-200 bg-white text-md"
                                v-text="formation.objectifVise"></td>
                            <td class="py-1 border-b border-gray-200 bg-white text-md">{{ formation.employe }}<b>;</b></td>
                            <td class="py-1 border-b border-gray-200 bg-white text-md"
                                v-text="formation.dateFormation"></td>
                            <td class=" py-1 border-b border-gray-200 bg-white text-sm">
                                &nbsp;&nbsp;<button @click="deleteFormation(index)"  class="bg-red-600 hover:bg-red-600 text-white px-3 py-2 rounded"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <div class="mt-5">
                    <button @click="storeRessource" v-if="tabFormation.length>0" :disabled="tabFormation.length===0" :class="tabFormation.length===0?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"   class="btn-green col-md-12">
                        <b id="btn-save">Enregistrer</b>
                    </button>
                </div>
            </div>
        </div>

    </div>

</template>


<script>

    import {onMounted, reactive, ref} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useFormation from "../../services/formationservices";
    import useUpdateVariable from "../../services/cleanVariable";
    import useUnite from "../../services/unites";
    import TitreApplication from "../block/TitreApplication";
    import employeservice from "../../services/employeservice";
    import useDepartement from "../../services/departements";


    export default {
        name: "FormationCreate",
        components:{TitreApplication},
        setup(){
            const form = reactive({
                typeFormation:'',
                libelle:'',
                employe:'',
                objectifVise:'',
                dateFormation:'',
                tabEmploye:[],
            })
            const tabFormation = ref([]);
            const myValue = ref([]);
            const {validateNameField,Errors} = useFormValidation();
            const {typeformations,getTypeFormations} = useFormation();

            const {cleanVar} = useUpdateVariable(Errors,form);
            onMounted(cleanVar);
            onMounted(getTypeFormations);

            onMounted(()=>{
                $(function () {
                    $('.select2').select2();
                });
            })

            const {isFormButtonDisabled} = confirmForm(form,Errors);

            const {tabError,createFormation} = useFormation();

            const {employes,getEmployes} = employeservice();
            onMounted(getEmployes);

            const getEmployeSelect = (id)=>{
                let trouver = false;
                let object = '';
                for (let i=0;i<employes.value.length;i++){
                    if (parseInt(employes.value[i].id) === parseInt(id) && trouver ===false){
                        trouver = true;
                        object = employes.value[i];
                        break;
                    }
                }
                return object.nom+' '+object.prenoms;
            }

            const validateInputlibelle = ()=>{
                validateNameField('libelle',form.libelle)
            }
            const validateInputobjectif = ()=>{
                validateNameField('objectifVise',form.objectifVise)
            }
            const validateInputdate = ()=>{
                validateNameField('dateFormation',form.dateFormation);
            }

            const addFormation = ()=>{
                form.tabEmploye = $('#unite').val();
                var e = document.getElementById("unite");
                var value = e.value;
                form.employe = e.options[e.selectedIndex].text;
                if (form.tabEmploye.length===0){
                    alert('Veuillez choisir les unités! Merci')
                    return 0;
                }
                if (form.libelle===''||form.objectifVise===''||form.dateFormation===''){
                    erreur = true;
                    alert('Veuillez saisir les informations de la formation! Merci')
                    return 0;
                }
                let erreur = false;
                tabFormation.value.forEach(function (item,indexe) {
                    if (item.libelle === form.libelle && erreur===false){
                        erreur = true;
                    }else {}
                });

                if (!erreur){
                    tabFormation.value.push({...form});
                    form.libelle='';
                    form.objectifVise='';
                    form.dateFormation='';
                    form.tabEmploye = [];
                    $('#unite').val('')

                }else
                    alert('Cette formation existe déjà dans la liste. Veuillez saisir autre mission');
            }

            const deleteFormation = (index) =>{
                tabFormation.value.splice(index,1);
            }

            const storeRessource = async () =>{
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await createFormation(tabFormation.value);
            }

            return{
                form,
                myValue,
                employes,
                tabError,
                Errors,
                tabFormation,
                isFormButtonDisabled,
                typeformations,
                addFormation,
                deleteFormation,
                validateInputlibelle,
                validateInputobjectif,
                validateInputdate,
                storeRessource,
                getEmployeSelect,
            }
        }
    }
</script>

<style scoped>

</style>
