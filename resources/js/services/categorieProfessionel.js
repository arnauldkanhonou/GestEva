import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useCategorieProfessionnelle() {
    const errors = ref('');
    const tabError = ref('');
    let categorieProfessionelle = reactive({id: '', code: '', libelle: '', categorieEmploye: '',smc:''});
    const categorieProfessionelles = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getCategorieProfessionnelle = async (id) => {
        axios.get('categorieprofes/' + id)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    categorieProfessionelle.id = response.data.data.id;
                    categorieProfessionelle.code = response.data.data.code;
                    categorieProfessionelle.libelle = response.data.data.libelle;
                    categorieProfessionelle.smc = response.data.data.smc;
                    categorieProfessionelle.categorieEmploye = response.data.data.categorie_employe_id;
                }

            }).catch((error) => {
            catchErrors(error, succesMessage);
        });

    }
    const getCategorieProfessionnelles = async () => {
        axios.get('categorieprofes')
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    categorieProfessionelles.value = response.data.data;
                    loadRessource.value = true;
                }

            }).catch((error) => {
            catchErrors(error, succesMessage);
        });

    }
    const getCategorieProfessionnellesByCategorie = async (idCategorie) => {
        axios.get('categorieprofes/categorie/' + idCategorie)
            .then((response) => {
                categorieProfessionelles.value = response.data.data
            }).catch((error) => {
            catchErrors(error, succesMessage);
        });

    }

    const createCategorieProfs = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('categorieprofes', data)
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
                    succesMessage.value = 'Une catégorie professionnelle est enregistreé dans la base avec succès !'
                    router.push({name: 'categorie.professionnelles.index', params: {messages: succesMessage.value}});
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

    const updateCategorieProfs = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('categorieprofes/' + id, categorieProfessionelle)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Modifier')
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'categorie.professionnelles.index', params: {messages: succesMessage.value}});
                }
            })
            .catch((error) => {
                $('#btn-save').html('Modifier')
                let createCustomerErrors = error.response.data.errors;
                if (error.response.status === 422) {
                    for (const key in createCustomerErrors) {
                        tabError.value += error.response.data.errors[key][0] + '| ';
                    }
                }
                catchErrors(error, succesMessage);
            })
    }

    const deleteCategorieProfs = async (id, idbtn) => {
        await axios.delete('categorieprofes/' + id)
            .then((response) => {
                succesMessage.value = 'Supression effectuée avec succès.'
                getCategorieProfessionnelles();
                router.push({name: 'categorie.professionnelles.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            }).catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    return {
        categorieProfessionelle,
        categorieProfessionelles,
        tabError,
        errors,
        loadRessource,
        getCategorieProfessionnelle,
        getCategorieProfessionnelles,
        createCategorieProfs,
        updateCategorieProfs,
        deleteCategorieProfs,
        getCategorieProfessionnellesByCategorie
    }
}
