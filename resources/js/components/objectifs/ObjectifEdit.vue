<template>
    <titre-application big-title="MODIFICATION OBJECTIFS" small-title="Formulaire-Modification" name-page="Objectif"></titre-application>
    <div class="container flex items-center justify-center p-12">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editResource">
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="objectif.libelle" type="text" name="libelle"
                               id="libelle" placeholder="saisir le libellé"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="resultat" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Résultats attendus<i class="text-red-600">*</i></b>
                        </label>
                        <div class="row">
                            <div class="col-md-11">
                                <input :class="Errors.resultat?'is-invalid':''" v-model="form.resultat" type="text" name="libelle"
                                       id="resultat" placeholder="saisir l'objectif"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>
                            <div class="col-md-1">
                                <button @click="addResultats(form)" class="bg-red-600 py-2 px-3 text-white align-content-center"
                                        type="button">
                                    <b id="btn-add-action"><i class="fa fa-plus"></i></b>
                                </button>
                            </div>
                        </div>
                        <strong v-if="Errors.resultat" class="text-red-600">{{Errors.resultat }}</strong>
                        <br>
                        <div class="row">
                            <div v-if="form.tabResults.length>0" class="col-md-12 alert-success">
                                <br>
                                <strong v-for="(action,index) in form.tabResults" :key="index">
                                    {{ index+1 }}&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;{{action}}  &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button"  @click="updateResultat(form,index)" class="bg-green-600 hover:bg-green-600 text-white px-1 py-0 rounded">
                                        <i class="fa fa-edit"></i>
                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button"  @click="removeResultat(form,index)" class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                    <br><br>
                                </strong>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="echeance" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Echeance<i class="text-red-600">*</i></b>
                        </label>
                        <div class="row">
                            <div class="col-md-12">
                                <input :class="Errors.echeance?'is-invalid':''" @keyup="validateInputEcheance"
                                       @blur="validateInputEcheance" v-model="objectif.echeance" type="date" name="libelle"
                                       id="echeance" placeholder="saisir l'objectif"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>
                        </div>
                        <strong v-if="Errors.echeance" class="text-red-600">{{Errors.echeance }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="actions" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Actions clées<i class="text-red-600">*</i></b>
                        </label>
                        <div class="row">
                            <div class="col-md-11">
                                <input :class="Errors.action?'is-invalid':''" v-model="form.action" type="text" name="libelle"
                                       id="actions" placeholder="Définissez les actions clées qui constituent cet objectif"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>
                            <div class="col-md-1">
                                <button @click="addActions(form)" class="bg-red-600 py-2 px-3 text-white align-content-center"
                                        type="button">
                                    <b id="btn-add-action"><i class="fa fa-plus"></i></b>
                                </button>
                            </div>
                        </div>
                        <strong v-if="Errors.action" class="text-red-600">{{Errors.action }}</strong>
                        <br>
                        <div class="row">
                            <div v-if="form.tabAction.length>0" class="col-md-12 alert-success">
                               <br>
                                <strong v-for="(action,index) in form.tabAction" :key="index">
                                    {{ index+1 }}&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;{{action}} &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button"  @click="updateActions(form,index)" class="bg-green-600 hover:bg-green-600 text-white px-1 py-0 rounded">
                                        <i class="fa fa-edit"></i>
                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button  @click="removeAction(form,index)" class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                    <br><br>
                                </strong>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled"
                                :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                type="submit"  class="btn-green col-md-12">
                            <b id="btn-submit">Modifier</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {onMounted,reactive} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useObjectifs from "../../services/objectifservices";
    import TitreApplication from "../block/TitreApplication";

    export default {
        name: "ObjectifEdit",
        components:{TitreApplication},
        props: {
            idCol: {
                required: false,
                type:String,
                default:'0',
            },
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {
            // const form = reactive({
            //     action:'',
            //     actions:'',
            //     tabAction:[],
            //     increment:1
            // })
            const  sleep = (ms)=> {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            const {objectif,tabError,getObjectif,updateObjectif,addActions,updateActions,
                removeAction,addResultats,removeResultat,updateResultat,form} = useObjectifs();
            onMounted(() => getObjectif(props.id))

            const {validateNameField, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(objectif, Errors);

            const validateInputlibelle = ()=>{
                validateNameField('libelle',objectif.libelle)
            }

            const displayActions = ()=>{
                let tab = objectif.actions.split(';');
                for(let i=0;i<tab.length;++i){
                    form.tabAction.push(tab[i]);
                }
            }

            // const editActions = ()=>{
            //     if (form.increment===1){
            //         form.actions = objectif.actions;
            //         form.increment=2;
            //     }
            //     addActions(form);
            // }

            const editResource = async () => {
                objectif.actions = form.tabAction;
                objectif.resultats = form.tabResults;
                objectif.idCol = props.idCol;
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateObjectif(props.id)
                objectif.libelle = '';
            }

            return {
                Errors,
                form,
                tabError,
                objectif,
                isFormButtonDisabled,
                addActions,
                updateActions,
                removeAction,
                addResultats,
                updateResultat,
                removeResultat,
                displayActions,
                validateInputlibelle,
                editResource,
            }
        }
    }
</script>

<style scoped>

</style>
