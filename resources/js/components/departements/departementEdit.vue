<template>
    <titre-application big-title="MODIFICATION POLE" small-title="Formulaire" name-page="Poles"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editDepartement">
                    <!--<div class="mb-4">
                        <label for="categorieEmploye" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Direction<i class="text-red-600">*</i></b>
                        </label>
                        <select v-model="departement.direction" name="" id="categorieEmploye"
                                class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez la direction</option>
                            <option v-for="direction in directions" :key="direction.id" :value="direction.id">
                                {{direction.libelle}}
                            </option>
                        </select>
                    </div>-->
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Code<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.code?'is-invalid':''" @keyup="validateIputcode" @blur="validateIputcode"
                               v-model="departement.code" type="text" name="code" id="code" placeholder="code de la direction"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.code" class="text-red-600">{{ Errors.code }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="departement.libelle" type="text" name="libelle"
                               id="libelle" placeholder="intiutlé de la direction"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled"
                                :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                type="submit"  class="btn-green col-md-12">
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
    import useDepartement from "../../services/departements";
    import useDirection from "../../services/directionServices";
    import useUpdateVariable from "../../services/cleanVariable";
    import {useStore} from "vuex";
    import TitreApplication from "../block/TitreApplication";

    export default {
        name: "departementEdit",
        components:{TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {
            const store = useStore();
            const {departement,tabError,getDepartement,updateDepartement} = useDepartement();
            onMounted(() => getDepartement(props.id))

            const {validateNameField, validateLength, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(departement, Errors);

            const {cleanVar} = useUpdateVariable(Errors,departement);
            onMounted(cleanVar);

            const validateIputcode = () => {
                (departement.code === '' ? validateNameField('code', departement.code) :
                    validateLength('code', departement.code, store.state.MAX_LENGTH))
            }
            const  {directions,getDirections} = useDirection();
            onMounted(getDirections)

            const validateInputlibelle = () => {
                validateNameField('libelle', departement.libelle)
            }

            const editDepartement = async () => {
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateDepartement(props.id)
                departement.code = '';
            }

            return {
                directions,
                Errors,
                tabError,
                departement,
                isFormButtonDisabled,
                validateIputcode,
                validateInputlibelle,
                editDepartement,
            }
        }
    }
</script>

<style scoped>

</style>
