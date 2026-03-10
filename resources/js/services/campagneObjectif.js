import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
// import VueSweetalert2 from "vue-sweetalert2";
import Swal from 'sweetalert2'

export default function useCampagneObjectif() {
    const errors = ref('');
    const tabError = ref('');
    let campagne = reactive({id: '', annee: '', libelle: '', plage1: '', plage2: '', cloturer: ''});
    const campagnes = ref([]);
    const annees = ref([]);
    const typeevaluations = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {loadRessource,catchErrors,getDate1} = appService();

    const getCampagne = async (id) => {
        axios.get('/campagneobjectifs/' + id)
            .then((response) => {
                if (response.data.data.code ===500){
                    Swal.fire({
                        title: 'Erreur serveur',
                        text:  response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    campagne.id = response.data.data.id;
                    campagne.annee = response.data.data.annee_id;
                    campagne.libelle = response.data.data.libelle;
                    campagne.plage1 = getDate1(response.data.data.plage1);
                    campagne.plage2 = getDate1(response.data.data.plage2);
                    campagne.cloturer = response.data.data.cloturer;
                }

            }).catch((error) => {
                catchErrors(error,succesMessage);
        });

    }
    const getCampagnes = async () => {
        axios.get('/campagneobjectifs')
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    campagnes.value = response.data.data;
                    loadRessource.value=true;
                }

            }).catch(error => {
                catchErrors(error,succesMessage);
        });

    }

    const getAnnes = async () => {
       axios.get('/annees')
           .then((response)=>{
               annees.value = response.data.data
           })
           .catch((error)=>{
               catchErrors(error,succesMessage);
           });

    }
    const getTypeEvaluations = async () => {
       axios.get('/typeevaluations')
           .then((response)=>{
               typeevaluations.value = response.data.data;
           })
           .catch((error)=>{
               catchErrors(error,succesMessage);
           });

    }

    const getAnnee = async () => {
       axios.get('get/annee')
           .then((response)=>{
               annees.value = response.data.data.annees
           })
           .catch((error)=>{
               catchErrors(error,succesMessage);
           });

    }

    const createCampagne = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('/campagneobjectifs', data)
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
                    succesMessage.value = 'La campagne de saisir des objectifs est lancée !'
                    router.push({name: 'campagne.objectif.index', params: {messages: succesMessage.value}});
                }
            }).catch(error => {
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

    const updateCampagne = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('/campagneobjectifs/' + id, campagne)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'campagne.objectif.index', params: {messages: succesMessage.value}});
                }
            }).catch(error => {
            let createCustomerErrors = error.response.data.errors;
            if (error.response.status === 422) {
                for (const key in createCustomerErrors) {
                    tabError.value += error.response.data.errors[key][0] + '| ';
                }
            }
            catchErrors(error,succesMessage);
        })
    }

    const deleteCampagne = async (id, idbtn) => {
        axios.delete('/campagneobjectifs/' + id)
            .then((response)=>{
               /* if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {*/
                    succesMessage.value = 'Supression effectuée avec succès.'
                    getCampagnes();
                    router.push({name: 'campagne.objectif.index', params: {messages: succesMessage.value}})
                    $('#' + idbtn)[0].click();
                    $('#btn-confirm').html('OUI');
               /* }*/
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    return {
        campagnes,
        campagne,
        tabError,
        errors,
        annees,
        typeevaluations,
        loadRessource,
        getAnnes,
        getTypeEvaluations,
        getCampagnes,
        getCampagne,
        createCampagne,
        updateCampagne,
        deleteCampagne,
    }
}
