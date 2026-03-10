<template>
    <titre-application big-title="MODIFICATION CATEGORIE-PROFESSIONNELLE" small-title="Formulaire" name-page="Catégorie-Professionnelle"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editResource">
                    <div class="mb-4">
                        <label for="categorieEmploye" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Categorie<i class="text-red-600">*</i></b>
                        </label>
                        <select v-model="categorieProfessionelle.categorieEmploye" name="" id="categorieEmploye"
                                class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez la catégorie</option>
                            <option v-for="categorie in categorieEmployes" :key="categorie.id" :value="categorie.id">
                                {{categorie.libelle}}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Code<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.code?'is-invalid':''" @keyup="validateIputcode" @blur="validateIputcode"
                               v-model="categorieProfessionelle.code" type="text" name="code" id="code" placeholder="code de la direction"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.code" class="text-red-600">{{ Errors.code }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="categorieProfessionelle.libelle" type="text" name="libelle"
                               id="libelle" placeholder="intiutlé de la direction"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Smc<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.smc?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="categorieProfessionelle.smc" type="number" name="smc"
                               id="smc" placeholder="saisir le smc"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.smc" class="text-red-600">{{Errors.smc }}</strong>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled"
                                :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                type="submit" class="btn-green col-md-12">
                            <b id="btn-save">Modifier</b>
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
    import useCategorieProfessionnelle from "../../services/categorieProfessionel";
    import useCategorieEmploye from "../../services/categorieEmploye";
    import TitreApplication from "../block/TitreApplication";
    import useUpdateVariable from "../../services/cleanVariable";
    import {useStore} from "vuex";

    export default {
        name: "CategorieProfEdit",
        components:{TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {

            const {categorieProfessionelle,tabError,getCategorieProfessionnelle,updateCategorieProfs} = useCategorieProfessionnelle();
            onMounted(() => getCategorieProfessionnelle(props.id))
            const store = useStore();
            const {validateNameField, validateLength, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(categorieProfessionelle, Errors);

            const {cleanVar} = useUpdateVariable(Errors,categorieProfessionelle);
            onMounted(cleanVar);

            const validateIputcode = () => {
                (categorieProfessionelle.code === '' ? validateNameField('code', categorieProfessionelle.code) :
                    validateLength('code', categorieProfessionelle.code, store.state.MAX_LENGTH))
            }

            const  {categorieEmployes,getCategorieEmployes} = useCategorieEmploye();
            onMounted(getCategorieEmployes);

            const validateInputlibelle = () => {
                validateNameField('libelle', categorieProfessionelle.libelle)
            }

            const validateInputsmc = ()=>{
                validateNameField('smc',categorieProfessionelle.smc)
            }

            const editResource = async () => {
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateCategorieProfs(props.id)
                categorieProfessionelle.code = '';
            }

            return {
                Errors,
                tabError,
                categorieEmployes,
                categorieProfessionelle,
                isFormButtonDisabled,
                validateIputcode,
                validateInputlibelle,
                validateInputsmc,
                editResource,
            }
        }
    }
</script>

<style scoped>

</style>
