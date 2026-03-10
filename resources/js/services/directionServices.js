import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useDirection() {
    const errors = ref('');
    const tabError = ref('');
    let direction = reactive({
        id: '',
        code: '',
        codeUse: '',
        libelle: ''
    });
    const directions = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource} = appService();

    const getDirection = async (id) => {
        await axios.get('directions/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    direction.id = response.data.data.id;
                    direction.code = response.data.data.code;
                    direction.codeUse = response.data.data.code;
                    direction.libelle = response.data.data.libelle;
                }
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }
    const getDirections = async () => {
        await axios.get('directions')
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    directions.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createDirection = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('directions', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer')
                    succesMessage.value = 'La direction ' + data.code + ' est enregistrée dans la base avec succès !'
                    router.push({name: 'directions.index', params: {messages: succesMessage.value}});
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

    const updateDirection = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('directions/' + id, direction)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Modifier')
                    succesMessage.value = 'La direction ' + direction.codeUse + ' a été modifiée avec succès !'
                    router.push({name: 'directions.index', params: {messages: succesMessage.value}});
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
                catchErrors(error,succesMessage);
            })
    }

    const deleteDirection = async (id, idbtn) => {
        await axios.delete('directions/' + id)
            .then((response)=>{
                succesMessage.value = 'Supression effectuée avec succès.'
                getDirections();
                router.push({name: 'directions.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    return {
        direction,
        directions,
        tabError,
        errors,
        loadRessource,
        getDirection,
        getDirections,
        createDirection,
        updateDirection,
        deleteDirection
    }
}
