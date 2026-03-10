import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useCritereEvaluation() {
    const errors = ref('');
    const tabError = ref('');
    const countData = ref([]);
    const countPage = ref([]);
    let critereEvaluation = reactive({id: '', code: '', libelle: '', categorieCritere: ''});
    const critereEvaluations = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getCritereEvaluation = async (id) => {
        axios.get('critereevaluations/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    critereEvaluation.id = response.data.data.id;
                    critereEvaluation.code = response.data.data.code;
                    critereEvaluation.libelle = response.data.data.libelle;
                    critereEvaluation.categorieCritere = response.data.data.critere_evaluation_id;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getCritereEvaluations = async () => {
        axios.get('critereevaluations')
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    critereEvaluations.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createCritereEvaluation = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('critereevaluations', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('ENREGISTRER')
                    succesMessage.value = 'Un critère d\'évaluation est enregistré dans la base avec succès !'
                    router.push({name: 'cirteres.evaluation.index', params: {messages: succesMessage.value}});
                }
            })
            .catch((error) => {
                let createCustomerErrors = error.response.data.errors;
                if (error.response.status === 422) {
                    $('#btn-save').html('ENREGISTRER')
                    for (const key in createCustomerErrors) {
                        tabError.value += error.response.data.errors[key][0] + '| ';
                    }
                }
                catchErrors(error,succesMessage);

            })
    }

    const updateCritereEvaluation = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('critereevaluations/' + id, critereEvaluation)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'cirteres.evaluation.index', params: {messages: succesMessage.value}});
                }
            })
            .catch((error) => {
                let createCustomerErrors = error.response.data.errors;
                if (error.response.status === 422) {
                    for (const key in createCustomerErrors) {
                        tabError.value += error.response.data.errors[key][0] + '| ';
                    }
                }
                catchErrors(error,succesMessage);
        })
    }

    const deleteCritereEvaluation = async (id, idbtn) => {
        await axios.delete('critereevaluations/' + id)
            .then((response)=>{
                succesMessage.value = 'Supression effectuée avec succès.'
                getCritereEvaluations();
                router.push({name: 'cirteres.evaluation.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    const paginateData = (nbre, page = 1) => {
        axios.get('critereevaluation/liste/' + nbre + '?page=' + page)
            .then(response => {
                critereEvaluations.value = response.data;
                countData.value = response.data.data;
                loadRessource.value = true;
            });
    }

    const getRessources = async (nbre = 10) => {
        await axios.get('critereevaluation/liste/' + nbre)
            .then((response) => {
                critereEvaluations.value = response.data;
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
            axios.get('critereevaluation/search/' + req)
                .then((response) => {
                    critereEvaluations.value = response.data;
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
        critereEvaluation,
        critereEvaluations,
        tabError,
        errors,
        countData,
        countPage,
        loadRessource,
        getCritereEvaluation,
        getCritereEvaluations,
        createCritereEvaluation,
        updateCritereEvaluation,
        deleteCritereEvaluation,
        paginateData,
        searchRessource,
        getRessources,
    }
}
