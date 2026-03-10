import {ref, reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useRoles() {
    const errors = ref('');
    const tabError = ref('');
    let role = reactive({id: '', name: '', description: '', created: ''});
    const roles = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors} = appService();

    const getRole = async (id) => {
        axios.get('roles/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    role.id = response.data.data.id;
                    role.name = response.data.data.name;
                    role.description = response.data.data.description;
                    role.created = response.data.data.created_at;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }
    const getRoles = async () => {
        await axios.get('roles')
            .then((response) => {
                roles.value = response.data.data
            }).catch(error => {
                catchErrors(error, succesMessage);
            });

    }

    const createRole = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('roles', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer');
                    succesMessage.value = data.length + 'role(s) enregistré(s) dans la base avec succès !'
                    router.push({name: 'roles.index', params: {messages: succesMessage.value}});
                }
            })
            .catch((error) => {
                $('#btn-save').html('Enregistrer')
                let createCustomerErrors = error.response.data.errors;
                if (error.response.status === 422) {
                    for (const key in createCustomerErrors) {
                        tabError.value += error.response.data.errors[key][0] + '| ';
                    }
                }
                catchErrors(error, succesMessage);
            })
    }

    const updateRole = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('roles/' + id, role)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'roles.index', params: {messages: succesMessage.value}});
                }
            })
            .catch((error) => {
                let createCustomerErrors = error.response.data.errors;
                if (error.response.status === 422) {
                    for (const key in createCustomerErrors) {
                        tabError.value += error.response.data.errors[key][0] + '| ';
                    }
                }
                catchErrors(error, succesMessage);
            })
    }

    const deleteRole = async (id, idbtn) => {
        axios.delete('roles/' + id)
            .then(() => {
                succesMessage.value = 'Supression effectuée avec succès.'
                getRoles();
                router.push({name: 'roles.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    return {
        role,
        roles,
        tabError,
        errors,
        getRoles,
        getRole,
        createRole,
        updateRole,
        deleteRole,
    }
}
