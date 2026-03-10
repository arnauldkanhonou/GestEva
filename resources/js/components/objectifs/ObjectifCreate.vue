<template>
    <titre-application big-title="CREATION OBJECTIFS" small-title="Formulaire-creation"
                       name-page="Objectif"></titre-application>
    <div class="container-fluid p-12">
        <div class="container">
            <div class=" offset-1 col-md-10 ">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <div class="mb-4">
                    <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Intitulé de l'objectif<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-9">
                            <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                                   @blur="validateInputlibelle" v-model="form.libelle" type="text" name="libelle"
                                   id="libelle" placeholder="saisir l'objectif"
                                   class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                    </div>
                    <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                </div>
                <div class="mb-4">
                    <label for="actions" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Actions clées<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-8">
                            <input :class="Errors.action?'is-invalid':''" @keyup="validateInputAction"
                                   @blur="validateInputAction" v-model="form.action" type="text" name="libelle"
                                   id="actions" placeholder="Définissez les actions clées qui participent à la réalisation de cet objectifs"
                                   class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                        <div class="col-md-1">
                            <button @click="addActions(form)" class="bg-red-600 py-2 px-4 text-white align-content-center"
                                    type="submit">
                                <b id="btn-add-action"><i class="fa fa-plus"></i></b>
                            </button>
                        </div>
                    </div>
                    <strong v-if="Errors.action" class="text-red-600">{{Errors.action }}</strong>
                    <br>
                    <div class="row">
                        <div v-if="form.tabAction.length>0" class="col-md-9 alert-success">
                            <br>
                            <strong v-for="(action,index) in form.tabAction" :key="index">
                                <i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;{{action}} &nbsp;&nbsp;&nbsp;&nbsp;
                                <button  @click="updateActions(form,index)" class="bg-green-600 hover:bg-green-600 text-white px-1 py-0 rounded">
                                    <i class="fa fa-edit"></i>
                                </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                <button  @click="removeAction(form,index)" class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded">
                                    <i class="fa fa-minus-circle"></i>
                                </button><br><br>
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="resultat" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Résultats attendus<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-8">
                            <input :class="Errors.resultat?'is-invalid':''" @keyup="validateInputResultat"
                                   @blur="validateInputResultat" v-model="form.resultat" type="text" name="libelle"
                                   id="resultat" placeholder="saisir le résultat attendu"
                                   class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                        <div class="col-md-1">
                            <button @click="addResultats(form)" class="bg-red-600 py-2 px-4 text-white align-content-center"
                                    type="submit">
                                <b id="btn-add-action"><i class="fa fa-plus"></i></b>
                            </button>
                        </div>
                    </div>
                    <strong v-if="Errors.resultat" class="text-red-600">{{Errors.resultat }}</strong>
                    <br>
                    <div class="row">
                        <div v-if="form.tabResults.length>0" class="col-md-9 alert-success">
                            <br>
                            <strong v-for="(resultat,index) in form.tabResults" :key="index"><i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;{{resultat}} &nbsp;&nbsp;&nbsp;&nbsp; <button  @click="updateResultat(form,index)" class="bg-green-600 hover:bg-green-600 text-white px-1 py-0 rounded"><i class="fa fa-edit"></i></button>&nbsp;&nbsp;&nbsp;&nbsp; <button  @click="removeResultat(form,index)" class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded"><i class="fa fa-minus-circle"></i></button><br><br></strong>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="echeance" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b> Echeance<i class="text-red-600">*</i></b>
                    </label>
                    <div class="row">
                        <div class="col-md-9">
                            <input :class="Errors.echeance?'is-invalid':''" @keyup="validateInputEcheance"
                                   @blur="validateInputEcheance" v-model="form.echeance" type="date" name="libelle"
                                   id="echeance" placeholder="saisir l'objectif"
                                   class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                    </div>
                    <strong v-if="Errors.echeance" class="text-red-600">{{Errors.echeance }}</strong>
                </div>

                <div class="mb-2">
                    <div class="col-md-3">
                        <button @click="addObjectif" class="btn-green col-md-12 py-2 px-9 text-white align-content-center" type="submit">
                            <b id="btn-add"><i class="fa fa-plus"></i> Ajouter</b>
                        </button>
                    </div>
                </div>
                <br>

            </div>
        </div>
        <div class="row">
            <table v-if="tabObjectif.length>0" class="min-w-full leading-normal table table-bordered table-striped"
                   style="background-color: #556e6e">
                <thead>
                <tr>
                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                        <b class="text-white">Libelle</b>
                    </th>
                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                        <b class="text-white">Action clées</b>
                    </th>
                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                        <b class="text-white">Resultat attendu</b>
                    </th>
                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                        <b class="text-white">Echeance</b>
                    </th>
                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                        <b class="text-white">Action</b>
                    </th>
                </tr>
                </thead>
                <tbody>
                <template v-for="(objectif,index) in tabObjectif" :key="objectif.id">
                    <tr>
                        <td class="py-1 border-b border-gray-200 bg-white text-md"
                            v-text="objectif.libelle"></td>
                        <td class="py-1 border-b border-gray-200 bg-white text-md">
                            <div v-if="objectif.actions.length>0" class="col-md-9">
                                <span v-for="(item,index) in objectif.actions" :key="index">
                                    {{ index + 1 }}&nbsp;-&nbsp;&nbsp;{{ item }}
                                    <br>
                                </span>
                            </div>
                        </td>
                        <td class="py-1 border-b border-gray-200 bg-white text-md">
                            <div v-if="objectif.resultats.length>0" class="col-md-9">
                               <span v-for="(item,index) in objectif.resultats" :key="index">
                                   {{ index + 1 }}&nbsp;-&nbsp;&nbsp;{{ item }}
                                   <br>
                               </span>
                            </div>
                        </td>
                        <td class="py-1 border-b border-gray-200 bg-white text-md"
                            v-text="objectif.echeance"></td>
                        <td class=" py-1 border-b border-gray-200 bg-white text-sm">
                            &nbsp;&nbsp;<button @click="updateObjectif(index)" class="bg-green-600 hover:bg-green-600 text-white px-3 py-2 rounded"><i
                                class="fa fa-edit"></i></button>
                            &nbsp;&nbsp;<button @click="deleteObjectif(index)"
                                                class="bg-red-600 hover:bg-red-600 text-white px-3 py-2 rounded"><i
                                class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
            <div class="mt-5">
                <button @click="storeRessource" v-if="tabObjectif.length>0" :disabled="tabObjectif.length===0"
                        :class="tabObjectif.length===0?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                        type="submit" class="btn-green col-md-12">
                    <b id="btn-save">Enregistrer</b>
                </button>
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
    import useObjectifs from "../../services/objectifservices";
    import Swal from "sweetalert2";

    export default {
        name: "ObjectifCreate",
        components: {TitreApplication},
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
        setup(props) {
            const form = reactive({
                libelle: '',
                resultat: '',
                echeance: '',
                action: '',
                actions: [],
                tabAction: [],
                resultats: [],
                tabResults: [],
            })
            const tabObjectif = ref([]);
            const {validateNameField, Errors} = useFormValidation();

            const {cleanVar} = useUpdateVariable(Errors, form);
            onMounted(cleanVar);

            const {isFormButtonDisabled} = confirmForm(form, Errors);

            const {tabError, createObjectif,addActions,updateActions,removeAction,
                  addResultats,updateResultat,removeResultat}
                = useObjectifs();

            const validateInputlibelle = () => {
                validateNameField('libelle', form.libelle)
            }
            const validateInputResultat = () => {
                validateNameField('resultat', form.resultat)
            }
            const validateInputEcheance = () => {
                validateNameField('echeance', form.echeance)
            }
            const validateInputAction = () => {
                validateNameField('action', form.action)
            }
            const addObjectif = () => {
                if (form.libelle === ''||form.tabAction.length===0||form.tabResults===0||form.echeance==='') {
                    erreur = true;
                    Swal.fire({
                        title: 'erreur',
                        text:  'Veuillez saisir votre toutes les informations de votre objectif! Merci',
                        icon: 'warning',
                    });
                    return 0;
                }

                let erreur = false;
                tabObjectif.value.forEach(function (item, indexe) {
                    if (item.libelle === form.libelle && erreur === false) {
                        erreur = true;
                    }

                });
                if (!erreur) {
                    tabObjectif.value.push({
                        libelle: form.libelle,
                        actions: form.tabAction,
                        resultats: form.tabResults,
                        echeance: form.echeance,
                    });
                    form.libelle = '';
                    form.resultat = '';
                    form.tabAction = [];
                    form.tabResults = [];
                    form.action = '';
                } else{
                    Swal.fire({
                        title: 'error',
                        text: 'Cet objectif existe déjà dans la liste. Veuillez saisir autre objectif',
                        icon: 'error',
                    });
                }
            }

            const updateObjectif = (index) => {
                let objectif = tabObjectif.value[index];
                form.tabResults = objectif.resultats;
                form.tabAction = objectif.actions;
                form.libelle = objectif.libelle;
                form.echeance = objectif.echeance;
                deleteObjectif(index);
            }

            const deleteObjectif = (index) => {
                tabObjectif.value.splice(index, 1);
            }

            const storeRessource = async () => {
                /*if (tabObjectif.value.length < 5) {
                    alert('Vos objectifs n\'atteignent pas 5');
                    return;
                }*/
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                let data = reactive({
                    'tabObjectif':tabObjectif.value,
                    'type':'formObjectif',
                    idColab: props.idCol,
                    intituleColab: props.intituleCol,
                })
                await createObjectif({...data},tabObjectif.value.length)
            }

            return {
                form,
                tabError,
                Errors,
                tabObjectif,
                isFormButtonDisabled,
                addActions,
                updateActions,
                removeAction,
                addResultats,
                updateResultat,
                removeResultat,
                addObjectif,
                updateObjectif,
                deleteObjectif,
                validateInputlibelle,
                validateInputResultat,
                validateInputAction,
                validateInputEcheance,
                storeRessource,
            }
        }
    }
</script>

<style scoped>

</style>
