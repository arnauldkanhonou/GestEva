import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";
import {useStore} from "vuex";
import useCollaborateur from "./collaborateurs";

export default function useObjectifs() {
    const errors = ref('');
    const tabError = ref('');
    let objectif = reactive({id: '', libelle: '', actions: '', resultats: '', echeance: '',employe_id:'',employe:'',idCol:'0'});
    const objectifs = ref([]);
    const countData = ref([]);
    const countPage = ref([]);
    const succesMessage = ref('');
    const have_campagne = ref(false);
    const annee_sys = ref('');
    const router = useRouter();
    const store = useStore()
    const {catchErrors, getDate1, hasRole,loadRessource} = appService();
    const form = reactive({
        action: '',
        actions: [],
        tabAction: [],
        resultat: '',
        resultats: [],
        tabResults: [],
        increment: 1
    })

    const getObjectif = async (id) => {
        axios.get('objectifs/' + id)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    objectif.id = response.data.data.id;
                    objectif.libelle = response.data.data.libelle;
                    objectif.actions = response.data.data.actionCle;
                    objectif.resultats = response.data.data.resultatAttendu;
                    objectif.echeance = response.data.data.echeance;
                    objectif.employe_id = response.data.data.employe_id;
                    objectif.employe = response.data.data.employe;
                    form.increment = 1;
                    form.tabResults =  response.data.data.resultatAttendu;
                    form.tabAction = objectif.actions;
                }

            })
            .then(() => {
                form.actions = objectif.actions;
                let tab = objectif.actions.split(';');
                for (let i = 0; i < tab.length; ++i) {
                    form.tabAction.push(tab[i]);
                }
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getObjectifs = async (nbre=10) => {
        loadRessource.value = false;
        await axios.get('objectifs/liste/'+nbre)
            .then((response) => {
                if (response.status === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    have_campagne.value = response.data.have_campagne;
                    annee_sys.value = response.data.annee_sys
                    let resp = response.data.dataObjectifs;
                    objectifs.value = resp.data;
                    countData.value = resp.data;
                    countPage.value = resp.last_page;
                    loadRessource.value = true;
                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createObjectif = async (data, nbre, type) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('objectifs', data)
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
                    succesMessage.value = nbre + ' objectif(s) enregistrés dans la base avec succès !'
                    if (data.idColab==='0') {
                        router.push({name: 'objectifs.index', params: {messages: succesMessage.value}});
                    } else {
                        router.push({name: 'collaborateur.objectifs', params: {id: data.idColab, intitule: data.intituleColab,messages: succesMessage.value}});
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

    const updateObjectif = async (id) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('objectifs/' + id, objectif)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    let route = '';
                    if (objectif.idCol !=='0') {
                        route = 'collaborateur.objectifs';
                        succesMessage.value = 'Modification effectuée avec succès !'
                        router.push({name: route, params: {id: objectif.employe_id, intitule:objectif.employe.nom+' '+objectif.employe.prenoms,messages: succesMessage.value}});
                    } else {
                        route = 'objectifs.index';
                        succesMessage.value = 'Modification effectuée avec succès !'
                        router.push({name: route, params: {messages: succesMessage.value}});
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


    const deleteObjectif = async (id, idbtn) => {
        axios.delete('objectifs/' + id)
            .then(() => {
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
                succesMessage.value = 'Supression effectuée avec succès.';
                if (objectif.idCol==='0') {
                    getObjectifs();
                }
                //router.push({name: 'objectifs.index', params: {messages: succesMessage.value}})

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const filtreObjectif = (annee) =>{
        loadRessource.value = false;
        axios.post('objectifs/filtre',{annee:annee})
            .then((response)=>{
                have_campagne.value = response.data.have_campagne;
                annee_sys.value = response.data.annee_sys
                let resp = response.data.dataObjectifs;
                objectifs.value = resp.data;
                countData.value = resp.data;
                countPage.value = resp.last_page;
                loadRessource.value = true;
            })

    }

    const searchRessource = (req) => {
        countData.value = [];
        if (req.length >= 4) {
            axios.get('objectifs/search/' + req)
                .then((response) => {
                    objectifs.value = response.data;
                    countData.value = response.data.data;
                    countPage.value = response.data.meta.last_page;
                })
                .catch(error => {
                    console.log(error);
                })
        }else {
            getObjectifs(10)
        }
    }

    const paginateData = (nbre, page = 1) => {
        loadRessource.value = false;
        axios.get('objectifs/liste/' + nbre + '?page=' + page)
            .then(response => {
                objectifs.value = response.data;
                countData.value = response.data.data;
                countPage.value = response.data.meta.last_page;
                loadRessource.value = true;
            });
    }

    const addActions = (form) => {
        if (form.action === '') {
            alert('Veuillez saisir les actions clées de l\'objectifs');
            return;
        }
        // if (form.tabAction.length ===0) {
        //     form.actions = form.action
        // }else
        //     form.actions = form.actions + ';' + form.action
        form.tabAction.push(form.action);
        form.action = '';
    }

    const updateActions = (form,index) => {
        form.action = form.tabAction[index];
        removeAction(form,index);
    }

    const removeAction = (form, index) => {
        form.action = form.tabAction[index];
        form.tabAction.splice(index, 1);
        // if (form.tabAction.length > 0) {
        //     form.actions = '';
        //     for (let i = 0; i < form.tabAction.length; ++i) {
        //         form.actions += form.tabAction[i] + ';';
        //     }
        // } else
        //     form.actions = '';
    }

    const addResultats = (form) => {
        if (form.resultat === '') {
            alert('Veuillez saisir les resultats de l\'objectifs');
            return;
        }
        // if (form.tabResults.length ===0) {
        //     form.resultats = form.resultat
        // }else
        //     form.resultats = form.resultats + ';' + form.resultat
        form.tabResults.push(form.resultat);
        form.resultat = '';
    }

    const updateResultat = (form,index) => {
        form.resultat = form.tabResults[index];
        removeResultat(form,index);
    }

    const removeResultat = (form, index) => {
        form.resultat = form.tabResults[index];
        form.tabResults.splice(index, 1);
        // if (form.tabResults.length > 0) {
        //     form.resultat = '';
        //     for (let i = 0; i < form.tabResults.length; ++i) {
        //         form.resultat += form.tabResults[i] + ';';
        //     }
        // } else
        //     form.resultat = '';
    }

    const validateObjectif = async (data, idbtnConfirm, idbtn, idCollaborateur, intitule) => {
        axios.post('objectifs/validate', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    //succesMessage.value = 'Validation effectuée avec succès.';
                    window.location.reload();
                    //$('#' + idbtn)[0].click();
                    //$('#' + idbtnConfirm).html('OUI');

                }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    return {
        objectif,
        objectifs,
        tabError,
        errors,
        succesMessage,
        form,
        loadRessource,
        countData,
        countPage,
        addActions,
        updateActions,
        removeAction,
        addResultats,
        updateResultat,
        removeResultat,
        getObjectif,
        getObjectifs,
        createObjectif,
        updateObjectif,
        deleteObjectif,
        validateObjectif,
        filtreObjectif,
        paginateData,
        searchRessource,
        have_campagne,
        annee_sys,
    }
}
