<template>
    <titre-application big-title="MODIFICATION COMPTE" small-title="Formulaire" name-page="Compte-utilisateur"></titre-application>
    <div class="container flex items-center justify-center p-12">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editResource">
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Intitulé compte<i class="text-red-600">*</i></b>
                        </label>
                        <input readonly :class="Errors.name?'is-invalid':''" v-model="user.name" type="text" name="libelle" id="libelle"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.name" class="text-red-600">{{Errors.name }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Email compte<i class="text-red-600">*</i></b>
                        </label>
                        <input readonly :class="Errors.email?'is-invalid':''" v-model="user.email" type="text" name="libelle" id="email"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.email" class="text-red-600">{{Errors.email }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="role" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Rôle attribuer<i class="text-red-600">*</i></b>
                        </label>
                        <div class="row">
                            <div class="col-md-12">
                                <select name="" id="role" multiple  class="form-control select2">
                                    <option v-for="role in roles" v-bind:key="role.id" :value="role.id" :selected="selectItem(role.id)">{{ role.name }}</option>
                                </select>
                            </div>
                        </div>
                        <strong v-if="Errors.tabRole" class="text-red-600">{{Errors.tabRole }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Nouveau mot de passe<i class="text-red-600">*</i></b>
                        </label>
                        <input  :class="Errors.password?'is-invalid':''" @keyup="validateInputpassword"
                                v-model="form.password" type="password" name="libelle"
                               id="password" placeholder="saisir le mot de passe"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.password" class="text-red-600">{{Errors.password }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="repatPassword" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Confirmer mot de passe<i class="text-red-600">*</i></b>
                        </label>
                        <input  :class="Errors.passwordRepat?'is-invalid':''" @keyup="validateInputpasswordRepeat"
                                v-model="form.passwordRepat" type="password" name="libelle"
                               id="repatPassword" placeholder="confirmer le mot de passe"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.passwordRepat" class="text-red-600">{{Errors.passwordRepat }}</strong>
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
    import {onMounted,reactive} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import usersServices from "../../services/usersServices";
    import useRoles from "../../services/rolesServices";
    import useUpdateVariable from "../../services/cleanVariable";

    export default {
        name: "userEdit",
        components: {TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {
            const form = reactive({
                password:'',
                passwordRepat:'',
                email:'',
                roles:''
            })

            onMounted(()=>{
                $(function () {
                    $('.select2').select2();
                });
            })
            const {user,tabError,getUser,updateUser} = usersServices();
            onMounted(() => getUser(props.id));

            const {roles,getRoles} = useRoles();
            onMounted(getRoles);

            const {validateNameField,validateLength, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(user, Errors);

            const validateInputpassword = ()=>{
                validateLength('password',form.password,8,false)
            }

            const validateInputpasswordRepeat = ()=>{
                validateLength('passwordRepat',form.passwordRepat,8,false)
            }
            const selectItem = (id) => {
                let data = user.roles;
                let trouver = false;
                for (let i=0;i<data.length;i++){
                    if(parseInt(data[i].id) === parseInt(id) && trouver===false) {
                        trouver = true
                    }
                }
                return trouver;
            }


            const editResource = async () => {
                if (form.password !== form.passwordRepat){
                    form.password = '';
                    form.passwordRepat = '';
                    alert('Le mot de passe n\'est pas bien confirmé');
                    return  0;
                }
                form.email = user.email;
                form.roles = $('#role').val();
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateUser(props.id,{...form})
                form.password = '';
                form.passwordRepat = '';
            }

            return {
                form,
                Errors,
                tabError,
                user,
                roles,
                selectItem,
                isFormButtonDisabled,
                validateInputpasswordRepeat,
                validateInputpassword,
                editResource,
            }
        }
    }
</script>

<style scoped>

</style>