import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
// import VueSweetalert2 from "vue-sweetalert2";
import Swal from 'sweetalert2'

export default function useCollaborateur() {
    const errors = ref('');
    const tabError = ref('');
    const collaborateurs = ref([]);
    const evaluateurs = ref([]);
    const countData = ref([]);
    const countPage = ref([]);
    const annees = ref([]);
    const missions = ref([]);
    const objectifs = ref([]);
    const critereEvaluations = ref([]);
    const evaluationsCollaborateur = ref([]);
    const formationsCollaboateur = ref([]);
    const tabCitereEvaluer = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors, loadRessource} = appService();

    const getEvaluateurs = async () => {
        axios.get('get/liste/evaluateur')
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    evaluateurs.value = response.data.data;
                    loadRessource.value = true;
                }

            }).catch(error => {
            catchErrors(error, succesMessage);
        });

    }

    const getEvaluateursByService = async (id_service) => {
        axios.get('get/liste/evaluateur/by/service/'+id_service)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    evaluateurs.value = response.data.data;
                    loadRessource.value = true;
                }

            }).catch(error => {
            catchErrors(error, succesMessage);
        });

    }

    const searchEvaluateur = (req) => {
        evaluateurs.value = [];
        loadRessource.value = false;
        if (req.length > 3) {
            axios.get('evaluateur/search/' + req)
                .then((response) => {
                    evaluateurs.value = response.data.data;
                    loadRessource.value = true;
                })
                .catch(error => {
                    console.log(error);
                })
        }else {
            getEvaluateurs()
        }
    }

    const getCollaborateurs = async (id,option='user') => {
        axios.get('collaborateurs/' + id+'/'+option)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    collaborateurs.value = response.data.data;
                    loadRessource.value = true;
                }

            }).catch(error => {
            catchErrors(error, succesMessage);
        });

    }

    const getMissionCollaborateur = async (id) => {
        loadRessource.value= false;
        missions.value = [];
        axios.get('missions/collaborateurs/'+id)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    missions.value = response.data.data;
                    loadRessource.value= true;
                }
            }).catch((error) => {
             catchErrors(error, succesMessage);
        });

    }

    const getObjectifCollaborateur = async (id) => {
        loadRessource.value= false;
        objectifs.value = [];
        axios.get('objectifs/collaborateurs/' + id)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    objectifs.value = response.data.data;
                    loadRessource.value=true;
                }
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getObjectifCollaborateurValider = async (idEvaluation = 0,from='param') => {
        axios.get('objectifs/evaluation/collaborateur/' + idEvaluation+'/'+from)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    objectifs.value = response.data.data;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getCritereEvaluationCollaborateur = async (id, idEvaluation = 0,from='param') => {
        await axios.get('critere/evaluation/collaborateur/' + id + '/' + idEvaluation+'/'+from)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    critereEvaluations.value = response.data.data.criteresFormater;
                    tabCitereEvaluer.value = response.data.data.tabCritere;
                }

            }).catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getEvaluationCollaborateur = async (id) => {
        axios.get('evaluation/collaborateur/' + id)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    evaluationsCollaborateur.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getFormationSuivieCollaborateur = async (id) => {
        axios.get('formation/suivie/salarie/' + id)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    formationsCollaboateur.value = response.data.data;
                    loadRessource.value=true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getEvaluationSalarie = async (nbre=10) => {
        loadRessource.value = false;
        axios.get('evaluations/index/'+nbre)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    evaluationsCollaborateur.value = response.data;
                    countData.value = response.data;
                    countPage.value = response.data.last_page;
                    loadRessource.value = true;
                }
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const filtreEvaluationByType = (user_id,filtre,chief=false) => {
        loadRessource.value = false;
        axios.get('evaluation/by/type/' + user_id + '/' + filtre + '/' + chief)
            .then((response) => {
                evaluationsCollaborateur.value = response.data;
                loadRessource.value = true
            })
    }

    const filtreEvalautionSalarie = (annee) =>{
        loadRessource.value = false;
        axios.post('evaluations/filtre',{annee:annee})
            .then((response)=>{
                evaluationsCollaborateur.value = response.data;
                loadRessource.value = true;
            })

    }

    const paginateData = (nbre, page = 1) => {
        loadRessource.value = false;
        axios.get('evaluations/index/' + nbre + '?page=' + page)
            .then(response => {
                evaluationsCollaborateur.value = response.data;
                countData.value = response.data;
                countPage.value = response.data.last_page;
                loadRessource.value = true;
            });
    }

    const getAnnees = async () => {
        let response = await axios.get('get/annees/');
        annees.value = response.data.data;
    }


    return {
        tabError,
        errors,
        collaborateurs,
        objectifs,
        missions,
        critereEvaluations,
        tabCitereEvaluer,
        evaluationsCollaborateur,
        formationsCollaboateur,
        annees,
        loadRessource,
        evaluateurs,
        countPage,
        countData,
        // initEvaluation,
        getEvaluateurs,
        searchEvaluateur,
        getEvaluateursByService,
        getCollaborateurs,
        getMissionCollaborateur,
        getObjectifCollaborateur,
        getObjectifCollaborateurValider,
        getCritereEvaluationCollaborateur,
        getEvaluationCollaborateur,
        getAnnees,
        getEvaluationSalarie,
        getFormationSuivieCollaborateur,
        paginateData,
        filtreEvalautionSalarie,
        filtreEvaluationByType,
    }
}
