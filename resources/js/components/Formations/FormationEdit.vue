<template>
    <titre-application big-title="MODIFICATION FORMATIONS" small-title="Formulaire-Modification" name-page="Formation"></titre-application>
    <div class="container flex items-center justify-center p-12">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editResource">
                    <div class=" mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Type de formation<i class="text-red-600">*</i></b>
                        </label>
                        <select v-model="formation.type_formation_id" name="" id="" class="rounded-md form-control form-select">
                            <option value="" readonly>Sélectionnez le type de formation</option>
                            <option v-for="type in  typeformations" :key="type.id" :value="type.id">
                                {{type.libelle}}
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="formation.libelle" type="text" name="libelle"
                               id="libelle" placeholder="saisir le libellé"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Objectifs<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.objectifVise?'is-invalid':''" @keyup="validateInputobjectif"
                               @blur="validateInputobjectif" v-model="formation.objectifVise" type="text" name="libelle"
                               id="objectifVise" placeholder="saisir le libellé"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.objectifVise" class="text-red-600">{{Errors.objectifVise }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="unite" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Employés concernés<i class="text-red-600">*</i></b>
                        </label>
                        <div class="row">
                            <div class="col-md-12">
                                <select name="" id="unite" multiple  class="form-control select2">
                                    <option v-for="employe in employes.data" v-bind:key="employe.id" :value="employe.id" :selected="selectItem(employe.id)">({{employe.matricule}}) {{ employe.nom }} {{ employe.prenoms }}</option>
<!--                                    <option v-for="unite in unites" v-bind:key="unite.id" :value="unite.id" >{{ unite.libelle }}</option>-->
                                </select>
                            </div>
                        </div>
                        <strong v-if="Errors.tabUnite" class="text-red-600">{{Errors.tabUnite }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Date<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.dateFormation?'is-invalid':''" @keyup="validateInputdate"
                               @blur="validateInputdate" v-model="formation.dateFormation" type="date" name="libelle"
                               id="dateFormation" class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.dateFormation" class="text-red-600">{{Errors.dateFormation }}</strong>
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
    import {onMounted} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useFormation from "../../services/formationservices";
    import useUpdateVariable from "../../services/cleanVariable";
    import useUnite from "../../services/unites";
    import TitreApplication from "../block/TitreApplication";
    import employeservice from "../../services/employeservice";

    export default {
        name: "FormationEdit",
        components:{TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {
            onMounted(()=>{
                $(function () {
                    $('.select2').select2();
                });
            })

            const {formation,typeformations,tabError,getFormation,updateFormation,getTypeFormations} = useFormation();
            onMounted(() => getFormation(props.id))

            /*const {unites,getUnites} = useUnite();
            onMounted(getUnites);*/
            onMounted(getTypeFormations)

            const {employes,getEmployes} = employeservice();
            onMounted(getEmployes);

            const selectItem = (id) => {
                let data = formation.unites;
                let trouver = false;
                for (let i=0;i<data.length;i++){
                    if(parseInt(data[i].id) === parseInt(id) && trouver===false) {
                        trouver = true
                    }
                }
                return trouver;
            }

            const {validateNameField, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(formation, Errors);

            const {cleanVar} = useUpdateVariable(Errors,formation);
            onMounted(cleanVar);

            const validateInputlibelle = ()=>{
                validateNameField('libelle',formation.libelle)
            }
            const validateInputobjectif = ()=>{
                validateNameField('objectifVise',formation.objectifVise)
            }
            const validateInputdate = ()=>{
                validateNameField('dateFormation',formation.dateFormation)
            }
            const editResource = async () => {
                formation.unites = $('#unite').val();
                if(formation.unites.length===0){
                    alert('Veuillez choisir les salariés concernés.');
                    return ;
                }
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateFormation(props.id)
                formation.libelle = '';
            }

            return {
                Errors,
                employes,
                tabError,
                formation,
                isFormButtonDisabled,
                typeformations,
                validateInputlibelle,
                validateInputobjectif,
                validateInputdate,
                editResource,
                selectItem,
            }
        }
    }
</script>

<style scoped>

</style>
