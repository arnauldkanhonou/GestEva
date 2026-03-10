import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function usePonderationCritere() {
    const errors = ref('');
    const tabError = ref('');
    let ponderationCritere = reactive({id: '', point: '', libelle: ''});
    const ponderationCriteres = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getPonderation = async (id) => {
        axios.get('ponderationcriteres/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    ponderationCritere.id = response.data.data.id;
                    ponderationCritere.point = response.data.data.point;
                    ponderationCritere.libelle = response.data.data.libelle;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }
    const getPonderations = async () => {
        axios.get('ponderationcriteres')
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    ponderationCriteres.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createPonderationCritere = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('ponderationcriteres', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer')
                    succesMessage.value = 'Une pondération est enregistrée dans la base avec succès !'
                    router.push({name: 'ponderation.criteres.index', params: {messages: succesMessage.value}});
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

    const updatePonderationCritere = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('ponderationcriteres/' + id, ponderationCritere)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'ponderation.criteres.index', params: {messages: succesMessage.value}});
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

    const deleteRessource = async (id, idbtn) => {
        axios.delete('ponderationcriteres/' + id)
            .then(()=>{
                succesMessage.value = 'Supression effectuée avec succès.'
                getPonderations();
                router.push({name: 'ponderation.criteres.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    return {
        ponderationCritere,
        ponderationCriteres,
        tabError,
        errors,
        loadRessource,
        getPonderation,
        getPonderations,
        createPonderationCritere,
        updatePonderationCritere,
        deleteRessource
    }
}
