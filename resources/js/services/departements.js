import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useDepartement() {
    const errors = ref('');
    const tabError = ref('');
    let departement = reactive({id: '', code: '', libelle: '', /*direction: ''*/});
    const departements = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getDepartement = async (id) => {
        axios.get('departements/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    departement.id = response.data.data.id;
                    departement.code = response.data.data.code;
                    departement.libelle = response.data.data.libelle;
                    departement.direction = response.data.data.direction_id;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }
    const getDepartements = async () => {
        axios.get('departements')
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    departements.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createDepartement = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('departements', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-saveDep').html('Enregistrer');
                    succesMessage.value = 'Un département est enregistré dans la base avec succès !'
                    router.push({name: 'departements.index', params: {messages: succesMessage.value}});
                }
            })
            .catch(error => {
                $('#btn-saveDep').html('Enregistrer');
                let createCustomerErrors = error.response.data.errors;
                if (error.response.status === 422) {
                    for (const key in createCustomerErrors) {
                        tabError.value += error.response.data.errors[key][0] + '| ';
                    }
                }
                catchErrors(error, succesMessage);
            })
    }

    const updateDepartement = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('departements/' + id, departement)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-saveDep').html('Enregistrer');
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'departements.index', params: {messages: succesMessage.value}});
                }
            })
            .catch((error) => {
                $('#btn-saveDep').html('Enregistrer');
                let createCustomerErrors = error.response.data.errors;
                if (error.response.status === 422) {
                    for (const key in createCustomerErrors) {
                        tabError.value += error.response.data.errors[key][0] + '| ';
                    }
                }
                catchErrors(error,succesMessage);
            })
    }

    const deleteDepartement = async (id, idbtn) => {
        axios.delete('departements/' + id)
            .then((response)=>{
                succesMessage.value = 'Supression effectuée avec succès.'
                getDepartements();
                router.push({name: 'departements.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    return {
        departement,
        departements,
        tabError,
        errors,
        loadRessource,
        getDepartement,
        getDepartements,
        createDepartement,
        updateDepartement,
        deleteDepartement
    }
}
