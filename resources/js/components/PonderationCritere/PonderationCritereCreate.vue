<template>
    <titre-application big-title="CREATION PONDERATION-CRITERE" small-title="Formulaire" name-page="Pondération"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong >{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="storeRessource">
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle" @blur="validateInputlibelle" v-model="form.libelle"  type="text" name="libelle" id="libelle" placeholder="saisir le libelle"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Point<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.point?'is-invalid':''" @keyup="validateInputpoint" @blur="validateInputpoint" v-model="form.point" type="number" name="code" id="code" placeholder="saisir le point correspondant"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.point" class="text-red-600">{{ Errors.point }}</strong>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled" :class="isFormButtonDisabled?'btn btn-default text-white':'py-2 px-20 text-white align-content-center'" type="submit"  class="btn-green col-md-12">
                            <b id="btn-save">Enregistrer</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {onMounted, reactive} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import usePonderationCritere from "../../services/ponderationcritere";
    import useUpdateVariable from "../../services/cleanVariable";
    import TitreApplication from "../block/TitreApplication";

    export default {
        name: "PonderationCritereCreate",
        components:{TitreApplication},
        setup(){
            const form = reactive({
                point:'',
                libelle:'',
            })
            const {validateNameField,Errors} = useFormValidation();

            const {cleanVar} = useUpdateVariable(Errors,form);
            onMounted(cleanVar);

            const {isFormButtonDisabled} = confirmForm(form,Errors);

            const {tabError,createPonderationCritere} = usePonderationCritere();

            const validateInputlibelle = ()=>{
                validateNameField('libelle',form.libelle)
            }

            const validateInputpoint = ()=>{
                validateNameField('point',form.point)
            }

            const storeRessource = async () =>{
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                await createPonderationCritere({...form})
                form.libelle = '';
            }

            return{
                form,
                tabError,
                Errors,
                isFormButtonDisabled,
                validateInputpoint,
                validateInputlibelle,
                storeRessource,
            }
        }
    }
</script>

<style scoped>

</style>