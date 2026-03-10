<template>
    <titre-application big-title="MODIFICATION MISSIONS" small-title="Formulaire-Modification" name-page="Mission"></titre-application>
    <div class="container flex items-center justify-center p-12">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editResource">
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Intiutlé<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.name?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="role.name" type="text" name="libelle"
                               id="libelle" placeholder="saisir le libellé"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.name" class="text-red-600">{{Errors.name }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Description<i class="text-red-600">*</i></b>
                        </label>
                        <textarea :class="Errors.description?'is-invalid':''" @keyup="validateInputdescription"
                               @blur="validateInputdescription" v-model="role.description" type="text" name="libelle"
                               id="description" placeholder="saisir le libellé"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.description" class="text-red-600">{{Errors.description }}</strong>
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
    import TitreApplication from "../block/TitreApplication";
    import {onMounted} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useRoles from "../../services/rolesServices";

    export default {
        name: "RoleEdit",
        components:{TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {

            const {role,tabError,getRole,updateRole} = useRoles();
            onMounted(() => getRole(props.id))

            const {validateNameField, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(role, Errors);

            const validateInputlibelle = ()=>{
                validateNameField('name',role.name)
            }

            const validateInputdescription = ()=>{
                validateNameField('description',role.description)
            }

            const editResource = async () => {
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateRole(props.id)
                role.name = '';
            }

            return {
                Errors,
                tabError,
                role,
                isFormButtonDisabled,
                validateInputlibelle,
                validateInputdescription,
                editResource,
            }
        }
    }
</script>

<style scoped>

</style>