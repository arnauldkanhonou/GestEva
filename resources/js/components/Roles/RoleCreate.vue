<template>
    <titre-application big-title="CREATION ROLES" small-title="Formulaire-Création" name-page="Roles"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-1 col-md-10 ">
                <div class="alert alert-danger" v-if="tabError">
                    <strong >{{ tabError }}</strong>
                </div>
                <div class=" mb-4">
                    <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Intitulé du role<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <input :class="Errors.name?'is-invalid':''" @keyup="validateInputlibelle" @blur="validateInputlibelle" v-model="form.name"  type="text" name="libelle" id="libelle" placeholder="saisir l'intitulé du role"
                                   class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                    </div>
                    <strong v-if="Errors.name" class="text-red-600">{{Errors.name }}</strong>
                </div>
                <div class="mb-4">
                    <label for="objectif" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Description<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <textarea :class="Errors.description?'is-invalid':''" @keyup="validateInputdescription" @blur="validateInputdescription" v-model="form.description"  type="text" name="objectif" id="objectif" placeholder="saisir la description"
                                      class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                    </div>
                    <strong v-if="Errors.description" class="text-red-600">{{Errors.description }}</strong>
                </div>

                <div class="mb-4">
                    <div class="col-md-12">
                        <button @click="addRole" class="float-end btn-green text-white py-2 px-2 ' align-content-center" >
                            <b><i class="fa fa-plus"></i> Ajouter</b>
                        </button>
                    </div>
                    <br>
                </div>
            </div>
            <br>
            <hr>
            <div>
                <table v-if="tabRole.length>0" class="min-w-full leading-normal table table-bordered table-striped" style="background-color: #556e6e">
                    <thead>
                    <tr>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Libelle</b>
                        </th>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">description</b>
                        </th>

                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Action</b>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(role,index) in tabRole" :key="role.id">
                        <tr>
                            <td class="py-1 border-b border-gray-200 bg-white text-md"
                                v-text="role.name"></td>
                            <td class="py-1 border-b border-gray-200 bg-white text-md"
                                v-text="role.description"></td>
                            <td class=" py-1 border-b border-gray-200 bg-white text-sm">
                                &nbsp;&nbsp;<button @click="deleteRole(index)"  class="bg-red-600 hover:bg-red-600 text-white px-3 py-2 rounded"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <div class="mt-5">
                    <button @click="storeRessource" v-if="tabRole.length>0" :disabled="tabRole.length===0" :class="tabRole.length===0?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"   class="btn-green col-md-12">
                        <b id="btn-save">Enregistrer</b>
                    </button>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import TitreApplication from "../block/TitreApplication";
    import {onMounted, reactive, ref} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import useUpdateVariable from "../../services/cleanVariable";
    import confirmForm from "../../validation/confirmForm";
    import useRoles from "../../services/rolesServices";

    export default {
        name: "RoleCreate",
        components:{TitreApplication},
        setup(){
            const form = reactive({
                name:'',
                description:'',
            })
            const tabRole = ref([]);

            const {validateNameField,Errors} = useFormValidation();

            const {cleanVar} = useUpdateVariable(Errors,form);
            onMounted(cleanVar);

            const {isFormButtonDisabled} = confirmForm(form,Errors);

            const {tabError,createRole} = useRoles();

            const validateInputlibelle = ()=>{
                validateNameField('name',form.name)
            }
            const validateInputdescription = ()=>{
                validateNameField('description',form.description)
            }

            const addRole = ()=>{
                if (form.name===''||form.description===''){
                    erreur = true;
                    alert('Veuillez saisir les informations du role! Merci')
                    return 0;
                }
                let erreur = false;
                tabRole.value.forEach(function (item,indexe) {
                    if (item.name === form.name && erreur===false){
                        erreur = true;
                    }else {}
                });

                if (!erreur){
                    tabRole.value.push({...form});
                    form.name='';
                    form.description='';

                }else
                    alert('Cet rôle existe déjà dans la liste. Veuillez saisir autre rôle');
            }

            const deleteRole = (index) =>{
                tabRole.value.splice(index,1);
            }

            const storeRessource = async () =>{
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await createRole(tabRole.value);
            }

            return{
                form,
                tabError,
                Errors,
                tabRole,
                isFormButtonDisabled,
                addRole,
                deleteRole,
                validateInputlibelle,
                validateInputdescription,
                storeRessource,
            }
        }
    }
</script>

<style scoped>

</style>