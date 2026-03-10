import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useFormation() {
    const errors = ref('');
    const tabError = ref('');
    let formation = reactive({id: '', libelle: '',type_formation_id: '', dateFormation: '', objectifVise: '', unites: ''});
    const formations = ref([]);
    const typeformations = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getFormation = async (id) => {
        axios.get('formations/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    formation.id = response.data.data.id;
                    formation.libelle = response.data.data.libelle;
                    formation.type_formation_id = response.data.data.type_formation_id;
                    formation.dateFormation = response.data.data.dateFormation;
                    formation.objectifVise = response.data.data.objectifVise;
                    formation.unites = response.data.data.employes;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }
    const getTypeFormations = async () => {
        axios.get('typeformations')
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    typeformations.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getFormations = async () => {
        axios.get('formations')
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    formations.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }
    const getFormationsForEvaluation = async (idEvaluation = 0,from='param') => {
        axios.get('formations/evaluation/collaborateur/' + idEvaluation+'/'+from)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    formations.value = response.data.data
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createFormation = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('formations', data)
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
                    succesMessage.value = data.length + ' formations enregistrées dans la base avec succès !'
                    router.push({name: 'formations.index', params: {messages: succesMessage.value}});
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

    const updateFormation = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('formations/' + id, formation)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'formations.index', params: {messages: succesMessage.value}});
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

    const deleteFormation = async (id, idbtn) => {
        await axios.delete('formations/' + id)
            .then((response)=>{
                succesMessage.value = 'Supression effectuée avec succès.'
                getFormations();
                router.push({name: 'formations.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    return {
        formation,
        formations,
        tabError,
        errors,
        loadRessource,
        typeformations,
        getFormation,
        getFormations,
        createFormation,
        updateFormation,
        deleteFormation,
        getFormationsForEvaluation,
        getTypeFormations,
    }
}
