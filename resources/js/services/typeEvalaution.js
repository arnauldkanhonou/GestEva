import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useTypeEvaluation() {
    const errors = ref('');
    const tabError = ref('');
    let typeEvaluation = reactive({id:'', code:'', libelle:''});
    const typeEvaluations = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource}= appService();

    const getTypeEvaluation = async (id) =>{
        axios.get('typeevaluations/'+ id)
            .then((response)=>{
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    typeEvaluation.id = response.data.data.id;
                    typeEvaluation.code = response.data.data.code;
                    typeEvaluation.libelle = response.data.data.libelle;
                }

            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }
    const getTypeEvaluations = async () => {
       axios.get('typeevaluations')
            .then((response)=>{
                typeEvaluations.value = response.data.data;
                loadRessource.value = true;
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    const createTypeEvaluation = async (data)=> {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('typeevaluations', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    $('#btn-save').html('Enregistrer')
                    succesMessage.value = 'Un type évaluation est enregistré dans la base avec succès !'
                    router.push({name: 'typeevaluations.index', params: {messages: succesMessage.value}});
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
                catchErrors(error,succesMessage);
        })
    }

    const updateTypeEvaluation = async (id)=> {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('typeevaluations/'+id, typeEvaluation)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'typeevaluations.index', params: {messages: succesMessage.value}});
                }
            })
            .catch(error => {
            let createCustomerErrors = error.response.data.errors;
            if (error.response.status === 422) {
                for (const key in createCustomerErrors) {
                    tabError.value += error.response.data.errors[key][0] + '| ';
                }
            }
                catchErrors(error,succesMessage);
        })
    }

    const deleteTypeEvaluation = async (id,idbtn) => {
        axios.delete('typeevaluations/'+id)
            .then(()=>{
                succesMessage.value = 'Supression effectuée avec succès.'
                getTypeEvaluations();
                router.push({name: 'typeevaluations.index', params: {messages: succesMessage.value}})
                $('#'+idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    return{
        typeEvaluation,
        typeEvaluations,
        tabError,
        errors,
        loadRessource,
        getTypeEvaluation,
        getTypeEvaluations,
        createTypeEvaluation,
        updateTypeEvaluation,
        deleteTypeEvaluation
    }
}
