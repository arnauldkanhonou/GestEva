import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useUnite() {
    const errors = ref('');
    const myOptions = ref([]);
    const countData = ref([]);
    const countPage = ref([]);
    const tabError = ref('');
    let unite = reactive({id: '', code: '', libelle: '', service: ''});
    const unites = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getUnite = async (id) => {
        axios.get('unites/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    unite.id = response.data.data.id;
                    unite.code = response.data.data.code;
                    unite.libelle = response.data.data.libelle;
                    unite.service = response.data.data.service_id;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getUnites = async () => {
        await axios.get('unites')
            .then((response) => {
                unites.value = response.data.data;
                //loadRessource.value = true;
            }).catch(errors => {
                catchErrors(error, succesMessage);
            });

    }
    const getRessources = async (nbre = 10) => {
        await axios.get('unite/liste/' + nbre)
            .then((response) => {
                unites.value = response.data
                countData.value = response.data.data;
                countPage.value = response.data.meta.last_page;
                loadRessource.value = true;
            }).catch(errors => {
                catchErrors(error, succesMessage);
            });

    }

    const getUniteByIdService = async (idService,filtre = false) => {
        axios.get('unites/services/' + idService)
            .then((response) => {
                if (!filtre){
                    unites.value = response.data.data
                }else {
                    unites.value = response.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createUnite = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('unites', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer')
                    succesMessage.value = 'Une unité est enregistrée dans la base avec succès !'
                    router.push({name: 'unites.index', params: {messages: succesMessage.value}});
                }
            })
            .catch(error => {
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

    const updateUnite = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('unites/' + id, unite)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'unites.index', params: {messages: succesMessage.value}});
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

    const deleteUnite = async (id, idbtn) => {
        axios.delete('unites/' + id)
            .then(()=>{
                succesMessage.value = 'Supression effectuée avec succès.'
                getUnites();
                router.push({name: 'unites.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    const paginateData = (nbre, page = 1) => {
        axios.get('unite/liste/' + nbre + '?page=' + page)
            .then(response => {
                unites.value = response.data;
                countData.value = response.data.data;
                loadRessource.value = true;
            });
    }

    const searchRessource = (req) => {
        loadRessource.value=false;
        if (req.length > 3) {
            axios.get('unite/search/' + req)
                .then((response) => {
                    unites.value = response.data;
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
        unite,
        unites,
        countData,
        countPage,
        tabError,
        errors,
        myOptions,
        loadRessource,
        getUnite,
        getUniteByIdService,
        getUnites,
        createUnite,
        updateUnite,
        deleteUnite,
        paginateData,
        searchRessource,
        getRessources
    }
}
