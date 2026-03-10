<template>
    <titre-application big-title="MODIFICATION GROUPE professionnel" small-title="Formulaire" name-page="Groupe-Prof."></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editResource">
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Code<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.code?'is-invalid':''" @keyup="validateIputcode" @blur="validateIputcode"
                               v-model="categorieEmploye.code" type="text" name="code" id="code" placeholder="code de la direction"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.code" class="text-red-600">{{ Errors.code }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="categorieEmploye.libelle" type="text" name="libelle"
                               id="libelle" placeholder="intiutlé de la direction"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled"
                                :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                type="submit" class="btn-green col-md-12">
                            <b id="btn-submit">Modifier</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>

<script>
    import useTypeEvaluation from "../../services/typeEvalaution";
    import {onMounted} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useCategorieEmploye from "../../services/categorieEmploye";
    import TitreApplication from "../block/TitreApplication";
    import useUpdateVariable from "../../services/cleanVariable";
    import {useStore} from "vuex";

    export default {
        name: "CategorieEmployeEdit",
        components:{TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {

            const {categorieEmploye,tabError,getCategorieEmploye,updateCategorieEmploye} = useCategorieEmploye();
            onMounted(() => getCategorieEmploye(props.id))
            const store = useStore();
            const {validateNameField, validateLength, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(categorieEmploye, Errors);

            const {cleanVar} = useUpdateVariable(Errors,categorieEmploye);
            onMounted(cleanVar);

            const validateIputcode = () => {
                (categorieEmploye.code === '' ? validateNameField('code', categorieEmploye.code) :
                    validateLength('code', categorieEmploye.code, store.state.MAX_LENGTH))
            }

            const validateInputlibelle = () => {
                validateNameField('libelle', categorieEmploye.libelle)
            }

            const editResource = async () => {
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateCategorieEmploye(props.id)
                categorieEmploye.code = '';
            }

            return {
                Errors,
                tabError,
                categorieEmploye,
                isFormButtonDisabled,
                validateIputcode,
                validateInputlibelle,
                editResource,
            }
        }
    }
</script>

<style scoped>

</style>
