import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useNiveauPerformance() {
    const errors = ref('');
    const tabError = ref('');
    let niveauPerformance = reactive({id: '', appreciation: '', libelle: '', borneInf: '', borneSup: ''});
    const niveauPerformances = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getNiveauPerformance = async (id) => {
        axios.get('niveauperformances/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    niveauPerformance.id = response.data.data.id;
                    niveauPerformance.appreciation = response.data.data.appreciation;
                    niveauPerformance.libelle = response.data.data.libelle;
                    niveauPerformance.borneInf = response.data.data.borneInf;
                    niveauPerformance.borneSup = response.data.data.borneSup;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }
    const getNiveauPerformances = async () => {
        axios.get('niveauperformances')
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    niveauPerformances.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createNiveauPerformance = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('niveauperformances', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer')
                    succesMessage.value = 'Une performance est enregistrée dans la base avec succès !'
                    router.push({name: 'niveau.performances.index', params: {messages: succesMessage.value}});
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

    const updateNiveauPerformance = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('niveauperformances/' + id, niveauPerformance)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'niveau.performances.index', params: {messages: succesMessage.value}});
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

    const deleteNiveauPerformance = async (id, idbtn) => {
       axios.delete('niveauperformances/' + id)
            .then(()=>{
                succesMessage.value = 'Supression effectuée avec succès.'
                getNiveauPerformances();
                router.push({name: 'niveau.performances.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
           .catch((error)=>{
               catchErrors(error,succesMessage);
           });

    }

    return {
        niveauPerformance,
        niveauPerformances,
        tabError,
        errors,
        loadRessource,
        getNiveauPerformance,
        getNiveauPerformances,
        createNiveauPerformance,
        updateNiveauPerformance,
        deleteNiveauPerformance
    }
}
