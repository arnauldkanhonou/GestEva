import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useNiveauExecutionObjectifs() {
    const errors = ref('');
    const tabError = ref('');
    let niveauObjectif = reactive({id: '', point: '', libelle: '', appreciation: ''});
    const niveauObjectifs = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getNiveauObjectif = async (id) => {
        axios.get('niveauexecutionobjectifs/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    niveauObjectif.id = response.data.data.id;
                    niveauObjectif.point = response.data.data.point;
                    niveauObjectif.libelle = response.data.data.libelle;
                    niveauObjectif.appreciation = response.data.data.appreciation;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }
    const getNiveauObjectifs = async () => {
        axios.get('niveauexecutionobjectifs')
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    niveauObjectifs.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createNiveauObjectif = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('niveauexecutionobjectifs', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer')
                    succesMessage.value = 'Un niveau d\'exécution d\'objectifs est enregistré dans la base avec succès !'
                    router.push({name: 'niveau.execution.objectifs.index', params: {messages: succesMessage.value}});
                }
            })
            .catch((error) => {
                $('#btn-save').html('Enregistrer');
                let createCustomerErrors = error.response.data.errors;
                if (error.response.status === 422) {
                    for (const key in createCustomerErrors) {
                        tabError.value += error.response.data.errors[key][0] + '| ';
                    }
                }
                catchErrors(error, succesMessage);
            })
    }

    const updateNiveauObjectif = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('niveauexecutionobjectifs/' + id, niveauObjectif)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'niveau.execution.objectifs.index', params: {messages: succesMessage.value}});
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

    const deleteRessource = async (id, idbtn) => {
        axios.delete('niveauexecutionobjectifs/' + id)
            .then(() => {
                succesMessage.value = 'Supression effectuée avec succès.'
                getNiveauObjectifs();
                router.push({name: 'niveau.execution.objectifs.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    return {
        niveauObjectif,
        niveauObjectifs,
        tabError,
        errors,
        loadRessource,
        getNiveauObjectif,
        getNiveauObjectifs,
        createNiveauObjectif,
        updateNiveauObjectif,
        deleteRessource
    }
}
