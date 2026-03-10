<template>
    <titre-application big-title="CREATION COMPTE-UTILISATEUR" small-title="Formulaire"
                       name-page="Compte-Utilisateur"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-1 col-md-10 ">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <div class=" mb-4">
                    <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Intitulé du compte<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <input readonly :class="Errors.intitule?'is-invalid':''" @blur="validateInputlibelle"
                                       @keyup="validateInputlibelle" id="libelle" type="text" v-model="form.intitule"
                                       class="form-control"
                                       placeholder="Rechercher le salarié en cliquant sur l'icone de recherche"
                                       aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <button data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                        class="btn-green text-white input-group-text" id="basic-addon2"><i
                                        class="fa fa-search"></i></button>
                            </div>
                            <strong v-if="Errors.intitule" class="text-red-600">{{Errors.intitule }}</strong>
                        </div>
                    </div>
                </div>
                <div class=" mb-4">
                    <label for="email" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Email du compte<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <input readonly :class="Errors.email?'is-invalid':''" @blur="validateInputlibelle"
                                       @keyup="validateInputlibelle" id="email" type="email" v-model="form.email"
                                       class="form-control" placeholder="email employé">

                            </div>
                            <strong v-if="Errors.email" class="text-red-600">{{Errors.email }}</strong>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="role" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Attribuer rôles<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <select name="" id="role" multiple class="form-control select2">
                                <option v-for="role in roles" v-bind:key="role.id" :value="role.id">{{ role.name}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <strong v-if="Errors.tabRole" class="text-red-600">{{Errors.tabRole }}</strong>
                </div>
                <!--<div class="mb-4">
                    <label for="password" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Mot de passe<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <input :class="Errors.password?'is-invalid':''" @blur="validateInputPassword" @keyup="validateInputPassword"
                                   v-model="form.password" type="password"
                                   name="dateFormation" id="password" placeholder="saisir la date de la formation"
                                   class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                    </div>
                    <strong v-if="Errors.password" class="text-red-600">{{Errors.password }}</strong>
                </div>
                <div class="mb-4">
                    <label for="password_confirm" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Confirmer mot de passe<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-12">
                            <input :class="Errors.password_confirmation?'is-invalid':''" @blur="validateInputPasswordConfirm" @keyup="validateInputPasswordConfirm"
                                   v-model="form.password_confirmation" type="password"
                                   name="dateFormation" id="password_confirm" placeholder="saisir la date de la formation"
                                   class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                    </div>
                    <strong v-if="Errors.password_confirmation" class="text-red-600">{{Errors.password_confirmation }}</strong>
                </div>-->
                <div class="mb-4">
                    <div class="col-md-12">
                        <button @click="addCompte"
                                class="float-end btn-green text-white py-2 px-2 ' align-content-center">
                            <b><i class="fa fa-plus"></i> Ajouter</b>
                        </button>
                    </div>
                    <br>
                </div>
            </div>
            <br>
            <hr>
            <div>
                <form method="post" @submit.prevent="storeRessource">
                    <table v-if="tabCompte.length>0" class="min-w-full leading-normal table table-bordered table-striped"
                           style="background-color: #556e6e">
                        <thead>
                        <tr>
                            <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b class="text-white">Intitulé</b>
                            </th>

                            <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b class="text-white">Rôle</b>
                            </th>

                            <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b class="text-white">Action</b>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="(compte,index) in tabCompte" :key="compte.id">
                            <tr>
                                <td class="py-1 border-b border-gray-200 bg-white text-md"
                                    v-text="compte.intitule"></td>
                                <td class="py-1 border-b border-gray-200 bg-white text-md">
                                    <label v-for="role in compte.tabRole" :key="role"><b>{{ getRoleSelect(role)}}
                                        ;</b></label>
                                </td>
                                <td class=" py-1 border-b border-gray-200 bg-white text-sm">
                                    &nbsp;&nbsp;<button @click="deleteCompte(index)"
                                                        class="bg-red-600 hover:bg-red-600 text-white px-3 py-2 rounded"><i
                                        class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <div class="mt-5">
                        <button type="submit" v-if="tabCompte.length>0" :disabled="tabCompte.length===0"
                                :class="tabCompte.length===0?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                class="btn-green col-md-12">
                            <b id="btn-save">Enregistrer</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                            <b><i class="fa fa-question-circle"></i> RECHERCHER SALARIE</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <input v-model="searhVar" @keyup="handleSearch" type="text" class="form-control" placeholder="rechercher par son matricule">
                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermer" class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                data-bs-dismiss="modal">Fermer
                        </button>
                    </div>
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
    import useUsers from "../../services/usersServices";
    import useRoles from "../../services/rolesServices";
    import axios from "axios";

    export default {
        name: "userCreate",
        components: {TitreApplication},
        setup() {
            const form = reactive({
                intitule: '',
                email:'',
                tabRole: [],
            })

            const searhVar = ref('');
            const tabCompte = ref([]);

            const {validateNameField, Errors} = useFormValidation();

            const {cleanVar} = useUpdateVariable(Errors, form);
            onMounted(cleanVar);

            onMounted(() => {
                $(function () {
                    $('.select2').select2();
                });
            })

            const {isFormButtonDisabled} = confirmForm(form, Errors);

            const {tabError, createUser} = useUsers();

            const {roles, getRoles} = useRoles();
            onMounted(getRoles);

            const getRoleSelect = (id) => {
                let trouver = false;
                let object = '';
                for (let i = 0; i < roles.value.length; i++) {
                    if (parseInt(roles.value[i].id) === parseInt(id) && trouver === false) {
                        trouver = true;
                        object = roles.value[i];
                        break;
                    }
                }
                return object.name;
            }

            const validateInputlibelle = () => {
                validateNameField('intitule', form.intitule)
            }
            /* const validateInputPassword = ()=>{
                 (form.password ===''?validateNameField('password',form.password):
                     validateLength('password',form.password,8,false))
             }
             const validateInputPasswordConfirm = ()=>{
                 (form.password_confirmation ===''?validateNameField('password',form.password_confirmation):
                     validateLength('password_confirmation',form.password_confirmation,8,false))
             }*/

            const handleSearch = ()=>{
                if (searhVar.value.length>=3){
                    axios.get('search/employe/'+searhVar.value)
                        .then((response)=>{
                           let data = response.data.data.liste[0];
                            form.intitule = data.nom+' '+data.prenoms;
                            form.email = data.email;
                            searhVar.value ='';
                            $('#btn-fermer')[0].click();
                        })
                }

            }

            const addCompte = () => {
                if (form.intitule === '') {
                    erreur = true;
                    alert('Veuillez renseigné l\'intitulé! Merci')
                    return 0;
                }

                /*if (form.password!==form.password_confirmation ){
                    erreur = true;
                    alert('Veuillez bien confirmer le mot de passe saisir');
                    return 0;
                }*/

                form.tabRole = $('#role').val();
                if (form.tabRole.length === 0) {
                    alert('Veuillez choisir les rôles! Merci');
                    return 0;
                }

                let erreur = false;
                tabCompte.value.forEach(function (item, indexe) {
                    if (item.intitule === form.intitule && erreur === false) {
                        erreur = true;
                    } else {
                    }
                });

                if (!erreur) {
                    tabCompte.value.push({...form});
                    form.intitule = '';
                    form.email = '';
                    form.tabRole = [];
                    $('#role').val('');

                } else
                    alert('Ce compte existe déjà dans la liste. Veuillez saisir autre mission');
            }

            const deleteCompte = (index) => {
                tabCompte.value.splice(index, 1);
            }

            const storeRessource = async () => {
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await createUser(tabCompte.value);
            }

            return {
                form,
                roles,
                tabError,
                Errors,
                tabCompte,
                searhVar,
                isFormButtonDisabled,
                addCompte,
                deleteCompte,
                validateInputlibelle,
                storeRessource,
                getRoleSelect,
                handleSearch,
            }
        }
    }
</script>

<style scoped>

</style>