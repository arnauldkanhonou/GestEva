import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useFonctions() {
    const errors = ref('');
    const tabError = ref('');
    const countData = ref([]);
    const countPage = ref([]);
    let fonction = reactive({id: '', code: '', libelle: '', service: ''});
    const fonctions = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getFonction = async (id) => {
        await axios.get('fonctions/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    fonction.id = response.data.data.id;
                    fonction.code = response.data.data.code;
                    fonction.libelle = response.data.data.libelle;
                    fonction.service = response.data.data.service_id;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getFonctions = async () => {
        await axios.get('fonctions')
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    fonctions.value = response.data.data
                    //loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getFonctionByIdService = async (idService,filtre=false) => {
        axios.get('fonctions/services/' + idService)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    if (!filtre){
                        fonctions.value = response.data.data;
                    }else {
                        fonctions.value = response.data
                        loadRessource.value = true;
                    }

                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createFonction = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('fonctions', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer')
                    succesMessage.value = 'Une fonction est enregistrée dans la base avec succès !'
                    router.push({name: 'fonctions.index', params: {messages: succesMessage.value}});
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

    const updateFonction = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('fonctions/' + id, fonction)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'fonctions.index', params: {messages: succesMessage.value}});
                }
            })
            .catch(error => {
                let createCustomerErrors = error.response.data.errors;
                if (error.response.status === 422) {
                    for (const key in createCustomerErrors) {
                        tabError.value += error.response.data.errors[key][0] + '| ';
                    }
                }
                catchErrors(error, succesMessage);
            })
    }

    const deleteFonction = async (id, idbtn) => {
        axios.delete('fonctions/' + id)
            .then((response) => {
                succesMessage.value = 'Supression effectuée avec succès.'
                getFonctions();
                router.push({name: 'fonctions.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const paginateData = (nbre, page = 1) => {
        axios.get('fonction/liste/' + nbre + '?page=' + page)
            .then(response => {
                fonctions.value = response.data;
                countData.value = response.data.data;
                loadRessource.value = true;
            });
    }

    const getRessources = async (nbre = 10) => {
        await axios.get('fonction/liste/' + nbre)
            .then((response) => {
                fonctions.value = response.data;
                countData.value = response.data.data;
                countPage.value = response.data.meta.last_page;
                loadRessource.value = true;
            }).catch(errors => {
                catchErrors(error, succesMessage);
            });

    }

    const searchRessource = (req) => {
        loadRessource.value=false;
        if (req.length > 3) {
            axios.get('fonction/search/' + req)
                .then((response) => {
                    fonctions.value = response.data;
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
        fonction,
        fonctions,
        tabError,
        countData,
        countPage,
        errors,
        loadRessource,
        getFonctionByIdService,
        getFonction,
        getFonctions,
        createFonction,
        updateFonction,
        deleteFonction,
        paginateData,
        searchRessource,
        getRessources,
    }
}
