import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useCategorieCritere() {
    const errors = ref('');
    const tabError = ref('');
    const countData = ref([]);
    const countPage = ref([]);
    let categorieCritere = reactive({id: '', code: '', libelle: ''});
    const categorieCriteres = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getCategorieCritere = async (id) => {
        axios.get('/categoriecriteres/' + id)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    categorieCritere.id = response.data.data.id;
                    categorieCritere.code = response.data.data.code;
                    categorieCritere.libelle = response.data.data.libelle;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getCategorieCriteres = async () => {
        axios.get('/categoriecriteres')
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    categorieCriteres.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createCategorieCritere = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('/categoriecriteres', data)
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
                    succesMessage.value = 'Une catégorie de critère d\'évaluation est enregistreé dans la base avec succès !'
                    router.push({name: 'categories.cirtere.index', params: {messages: succesMessage.value}});
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

    const updateCategorieCritere = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('/categoriecriteres/' + id, categorieCritere)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'categories.cirtere.index', params: {messages: succesMessage.value}});
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

    const deleteCategorieCritere = async (id, idbtn) => {
        await axios.delete('/categoriecriteres/' + id)
            .then((response) => {
                succesMessage.value = 'Supression effectuée avec succès.'
                getCategorieCriteres();
                router.push({name: 'categories.cirtere.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI');

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const paginateData = (nbre, page = 1) => {
        axios.get('categoriecriteres/liste/' + nbre + '?page=' + page)
            .then(response => {
                categorieCriteres.value = response.data;
                countData.value = response.data.data;
                loadRessource.value = true;
            });
    }

    const getRessources = async (nbre = 10) => {
        await axios.get('categoriecriteres/liste/' + nbre)
            .then((response) => {
                categorieCriteres.value = response.data;
                countData.value = response.data.data;
                countPage.value = response.data.meta.last_page;
                loadRessource.value = true;
            }).catch(errors => {
                catchErrors(error, succesMessage);
            });

    }

    const searchRessource = (req) => {
        countData.value = [];
        if (req.length > 3) {
            axios.get('categoriecriteres/search/' + req)
                .then((response) => {
                    categorieCriteres.value = response.data;
                    countData.value = response.data.data;
                    loadRessource.value = true;
                })
                .catch(error => {
                    console.log(error);
                })
        }else {
            getRessources(10)
        }
    }

    return {
        categorieCritere,
        categorieCriteres,
        tabError,
        errors,
        countData,
        countPage,
        loadRessource,
        getCategorieCritere,
        getCategorieCriteres,
        createCategorieCritere,
        updateCategorieCritere,
        deleteCategorieCritere,
        paginateData,
        searchRessource,
        getRessources,
    }
}
