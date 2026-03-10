import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import useCollaborateur from "./collaborateurs";
import appService from "./appService";
import Swal from "sweetalert2";

export default function useMission() {
    const errors = ref('');
    const tabError = ref('');
    const countData = ref([]);
    const countPage = ref([]);
    let mission = reactive({id: '', libelle: '',annee_id:'',idCol:'d',intitule:'f'});
    const missions = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {getMissionCollaborateur} = useCollaborateur();
    const {catchErrors,hasRole,loadRessource} = appService();

    const getMission = async (id) => {
        axios.get('missions/' + id)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    mission.id = response.data.data.id;
                    mission.libelle = response.data.data.libelle;
                    mission.annee_id = response.data.data.annee_id;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getMissions = async (nbre=10) => {
        loadRessource.value = false;
        axios.get('mission/liste/'+nbre)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    missions.value = response.data;
                    countData.value = response.data.data;
                    countPage.value = response.data.meta.last_page;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createMission = async (data,nbre,reconduire=false) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('missions', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    $('#btn-save').html('Enregistrer');
                    succesMessage.value = nbre + ' mission(s) enregistrées dans la base avec succès !'
                    if (data.idColab==='0') {
                        if (reconduire===true){
                            succesMessage.value+=' Veuillez commencer votre au-évaluation';
                            Swal.fire({
                                title: 'SUCCES',
                                text: succesMessage.value,
                                icon: 'success',
                            });
                            router.push({name: 'auto.evaluations.index'});
                            return 0;
                        }
                        router.push({name: 'missions.index', params: {messages: succesMessage.value}});
                    } else {
                        router.push({name: 'collaborateur.mission', params: {id: data.idColab, intitule: data.intituleColab,messages: succesMessage.value}});
                    }
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

    const updateMission = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';

        axios.patch('missions/' + id, mission)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    if (mission.idCol==='0') {
                        router.push({name: 'missions.index', params: {messages: succesMessage.value}});
                    } else {
                        router.push({name: 'collaborateur.mission', params: {id: mission.idCol, intitule: mission.intitule,messages: succesMessage.value}});
                    }

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

    const validateMission = async (data, idbtnConfirm, idbtn, idCollaborateur, intitule) => {
        axios.post('missions/validate', data)
            .then((response) => {
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    // succesMessage.value = 'Validation effectuée avec succès.';
                    //router.push({name: 'collaborateur.mission', params: {messages: succesMessage.value,id: idCollaborateur, intitule:intitule }});
                    // $('#' + idbtn)[0].click();
                    // $('#' + idbtnConfirm).html('OUI');
                    window.location.reload();
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const filtreMission = (annee) =>{
        loadRessource.value = false;
        axios.post('missions/filtre',{annee:annee})
            .then((response)=>{
                missions.value = response.data;
                loadRessource.value = true;
            })

    }

    const searchRessource = (req) => {
        countData.value = [];
        if (req.length >= 4) {
            axios.get('missions/search/' + req)
                .then((response) => {
                    missions.value = response.data;
                    countData.value = response.data.data;
                    countPage.value = response.data.meta.last_page;
                })
                .catch(error => {
                    console.log(error);
                })
        }else {
            getMissions(10)
        }
    }

    const deleteMission = async (id, idbtn) => {
        axios.delete('missions/' + id)
            .then((response) => {
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
                succesMessage.value = 'Supression effectuée avec succès.'
                if (mission.idCol==='0') {
                    getMissions();
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const paginateData = (nbre, page = 1) => {
        loadRessource.value = false;
        axios.get('mission/liste/' + nbre + '?page=' + page)
            .then(response => {
                missions.value = response.data;
                countData.value = response.data.data;
                countPage.value = response.data.meta.last_page;
                loadRessource.value = true;
            });
    }

    return {
        mission,
        missions,
        tabError,
        errors,
        succesMessage,
        loadRessource,
        countData,
        countPage,
        paginateData,
        validateMission,
        getMission,
        getMissions,
        createMission,
        updateMission,
        deleteMission,
        filtreMission,
        searchRessource,
        // missionAreconduire,
    }
}
