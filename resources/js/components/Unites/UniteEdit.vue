<template>
        <titre-application big-title="MODIFICATION UNITES" small-title="Formulaire-Modification" name-page="Unite"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editResource">
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Service<i class="text-red-600">*</i></b>
                        </label>
                        <select v-model="unite.service" name="" id="" class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez le service</option>
                            <option v-for="service in services" :key="service.id" :value="service.id" :selected="unite.service===service.id">
                                {{service.libelle}}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Code<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.code?'is-invalid':''" @keyup="validateIputcode" @blur="validateIputcode"
                               v-model="unite.code" type="text" name="code" id="code" placeholder="code de la direction"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.code" class="text-red-600">{{ Errors.code }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="unite.libelle" type="text" name="libelle"
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
    import useFonctions from "../../services/fonction";
    import {onMounted} from "vue";
    import useServices from "../../services/services";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useUnite from "../../services/unites";
    import TitreApplication from "../block/TitreApplication";
    import useUpdateVariable from "../../services/cleanVariable";
    import {useStore} from "vuex";

    export default {
        name: "UniteEdit",
        components:{TitreApplication},

        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {

            const {tabError,unite,getUnite,updateUnite} = useUnite();
            onMounted(() => getUnite(props.id))
            const {services,getServices} = useServices();
            onMounted(getServices)
            const store = useStore();
            const {validateNameField, validateLength, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(unite, Errors);

            const {cleanVar} = useUpdateVariable(Errors,unite);
            onMounted(cleanVar);

            const validateIputcode = () => {
                (unite.code === '' ? validateNameField('code', unite.code) :
                    validateLength('code', unite.code, store.state.MAX_LENGTH))
            }

            const validateInputlibelle = () => {
                validateNameField('libelle', unite.libelle)
            }

            const editResource = async () => {
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateUnite(props.id)
                unite.code = '';
            }

            return {
                Errors,
                tabError,
                unite,
                isFormButtonDisabled,
                services,
                validateIputcode,
                validateInputlibelle,
                editResource,
            }
        }
    }
</script>

<style scoped>

</style>
