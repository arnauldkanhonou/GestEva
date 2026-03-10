import {ref, reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useUsers() {
    const {getDate} = appService()
    const errors = ref('');
    const tabError = ref('');
    let user = reactive({id: '', name: '', email: '', created: '', roles: ''});
    const users = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors} = appService();

    const getUser = async (id) => {
        axios.get('users/' + id)
            .then((response) => {
                user.id = response.data.data.id;
                user.name = response.data.data.name;
                user.email = response.data.data.email;
                user.roles = response.data.data.roles;
                user.created = response.data.data.created_at;
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }
    const getUsers = async () => {
        axios.get('users')
            .then((response) => {
                users.value = response.data.data
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createUser = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('users', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer');
                    succesMessage.value = data.length + ' compte(s) enregistré(s) dans la base avec succès !'
                    router.push({name: 'users.index', params: {messages: succesMessage.value}});
                }
            })
            .catch((error) => {
            $('#btn-save').html('Enregistrer');
            let createCustomerErrors = error.response.data.errors;
            if (error.response.status === 422) {
                for (const key in createCustomerErrors) {
                    tabError.value += error.response.data.errors[key][0] + '| ';
                }
            }
                catchErrors(error,succesMessage);
        })
    }

    const updateUser = async (id, data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('users/' + id, data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'users.index', params: {messages: succesMessage.value}});
                }
            })
            .catch(error => {
            let createCustomerErrors = error.response.data.errors;
            if (error.response.status === 422) {
                for (const key in createCustomerErrors) {
                    tabError.value += error.response.data.errors[key][0] + '| ';
                }
            }
                catchErrors(error,succesMessage);
        })
    }

    const deleteUser = async (id, idbtn) => {
        axios.delete('users/' + id)
            .then(()=>{
                succesMessage.value = 'Supression effectuée avec succès.'
                getUsers();
                router.push({name: 'users.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    return {
        user,
        users,
        tabError,
        errors,
        getDate,
        getUsers,
        getUser,
        createUser,
        updateUser,
        deleteUser,
    }
}
