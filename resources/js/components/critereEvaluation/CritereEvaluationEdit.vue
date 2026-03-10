<template>
    <titre-application big-title="MODIFICATION ELEMENT D'APPRECICATION CRIT." small-title="Formulaire" name-page="Element"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editResource">
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Categorie Critère<i class="text-red-600">*</i></b>
                        </label>
                        <select v-model="critereEvaluation.categorieCritere" name="" id="" class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez la catégorie</option>
                            <option v-for="critere in categorieCriteres" :key="critere.id" :value="critere.id" :selected="critereEvaluation.categorieCritere===critere.id">
                                {{critere.libelle}}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Code<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.code?'is-invalid':''" @keyup="validateIputcode" @blur="validateIputcode"
                               v-model="critereEvaluation.code" type="text" name="code" id="code" placeholder="code de la direction"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.code" class="text-red-600">{{ Errors.code }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="critereEvaluation.libelle" type="text" name="libelle"
                               id="libelle" placeholder="intiutlé de la direction"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
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
    import useCritereEvaluation from "../../services/critereevaluations";
    import useCategorieCritere from "../../services/categorieCritere";
    import TitreApplication from "../block/TitreApplication";
    import useUpdateVariable from "../../services/cleanVariable";
    import {useStore} from "vuex";

    export default {
        name: "CritereEvaluationEdit",
        components:{TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {
            const store = useStore();
            const {critereEvaluation,tabError,getCritereEvaluation,updateCritereEvaluation} = useCritereEvaluation();
            onMounted(() => getCritereEvaluation(props.id))

            const {categorieCriteres,getCategorieCriteres} = useCategorieCritere();
            onMounted(getCategorieCriteres)

            const {validateNameField, validateLength, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(critereEvaluation, Errors);

            const {cleanVar} = useUpdateVariable(Errors,critereEvaluation);
            onMounted(cleanVar);

            const validateIputcode = () => {
                (critereEvaluation.code === '' ? validateNameField('code', critereEvaluation.code) :
                    validateLength('code', critereEvaluation.code, store.state.MAX_LENGTH))
            }

            const validateInputlibelle = () => {
                validateNameField('libelle', critereEvaluation.libelle)
            }

            const editResource = async () => {
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateCritereEvaluation(props.id)
                critereEvaluation.code = '';
            }

            return {
                Errors,
                tabError,
                categorieCriteres,
                critereEvaluation,
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
