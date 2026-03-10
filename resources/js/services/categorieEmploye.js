import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useCategorieEmploye() {
    const errors = ref('');
    const tabError = ref('');
    let categorieEmploye = reactive({id: '', code: '', libelle: ''});
    const categorieEmployes = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getCategorieEmploye = async (id) => {
        axios.get('categorieemployes/' + id)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    categorieEmploye.id = response.data.data.id;
                    categorieEmploye.code = response.data.data.code;
                    categorieEmploye.libelle = response.data.data.libelle;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }
    const getCategorieEmployes = async () => {
        axios.get('categorieemployes')
            .then((response) => {
                categorieEmployes.value = response.data.data;
                loadRessource.value = true;
            }).catch((error) => {
            catchErrors(error, succesMessage);
        });

    }

    const createCategorieEmploye = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('categorieemployes', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    $('#btn-save').html('Enregistrer')
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer')
                    succesMessage.value = 'Une catégorie d\'employé est enregistreé dans la base avec succès !'
                    router.push({name: 'categorie.employe.index', params: {messages: succesMessage.value}});
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

    const updateCategorieEmploye = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('categorieemployes/' + id, categorieEmploye)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer')
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'categorie.employe.index', params: {messages: succesMessage.value}});
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

    const deleteCategorieEmploye = async (id, idbtn) => {
        await axios.delete('categorieemployes/' + id)
            .then((response) => {
                succesMessage.value = 'Supression effectuée avec succès.'
                getCategorieEmployes();
                router.push({name: 'categorie.employe.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            }).catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    return {
        categorieEmploye,
        categorieEmployes,
        tabError,
        errors,
        loadRessource,
        getCategorieEmploye,
        getCategorieEmployes,
        createCategorieEmploye,
        updateCategorieEmploye,
        deleteCategorieEmploye
    }
}
