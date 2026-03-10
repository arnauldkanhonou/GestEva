import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import useCategorieProfessionnelle from "./categorieProfessionel";
import useFonctions from "./fonction";
import useUnite from "./unites";
import appService from "./appService";
import Swal from "sweetalert2";
import useServices from "./services";
import useCategorieEmploye from "./categorieEmploye";

export default function useEmploye() {
    const {fonctions, getFonctionByIdService} = useFonctions();
    const {unites, getUniteByIdService} = useUnite();
    const {services, getServices} = useServices();
    const {categorieEmployes, getCategorieEmployes} = useCategorieEmploye();
    const {categorieProfessionelles, getCategorieProfessionnellesByCategorie} = useCategorieProfessionnelle();
    const errors = ref('');
    const tabError = ref('');
    let employe = reactive({
        id: '', matricule: '', nom: '', prenoms: '', sexe: '', email: '',
        telephone: '', dateEmbauche: '', service: '', categorie: '', pathFile: '',
        fonction: '', unite: '', categorieProf: '', respService: '', respUnite: '', respDepartement: '',
    });
    const employes = ref([]);
    const countData = ref([]);
    const countPage = ref([]);
    const dataSQl = reactive({idCategorie: '', idService: ''});
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors} = appService();

    const getEmploye = async (id) => {
        axios.get('employes/' + id)
            .then((response) => {
                employe.id = response.data.data.id;
                employe.matricule = response.data.data.matricule;
                employe.nom = response.data.data.nom;
                employe.sexe = response.data.data.sexe;
                employe.prenoms = response.data.data.prenoms;
                employe.email = response.data.data.email;
                employe.telephone = response.data.data.telephone;
                employe.dateEmbauche = response.data.data.dateEmbauche;
                employe.categorieProf = response.data.data.categorie_professionnelle_id;
                employe.pathFile = response.data.data.pathFile;
                employe.fonction = response.data.data.fonction_id;
                employe.unite = response.data.data.unite_id;
                employe.respService = response.data.data.respService;
                employe.respUnite = response.data.data.respUnite;
                employe.respDepartement = response.data.data.respDepartement;
            })
            .catch(error => {
                console.log(error)
                //catchErrors(error, succesMessage);
            });

    }

    const getEmployes = async (nbre = 500) => {
        axios.get('employe/liste/' + nbre)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    employes.value  = response.data;
                    countData.value = response.data.data;
                    countPage.value = response.data.last_page;
                 }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getEmployeByService = async (id,nbre = 25) => {
        countData.value = [];
        axios.get('employe/by/service/' +id +'/'+ nbre)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    employes.value  = response.data;
                    countData.value = response.data.data;
                    countPage.value = response.data.last_page;
                 }

            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const createEmploye = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        console.log(data)
        axios.post('employes', data)
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
                    succesMessage.value = 'Un employé est enregistré dans la base avec succès !'
                    router.push({name: 'employes.index', params: {messages: succesMessage.value}});
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

    const updateEmploye = async (data) => {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        //la methode PATCH OU PUT est normalement celle requise pour la modification d'une ressource mais
        //vu que je veux envoyer un formData qui contient si possible une image choisie par le user il faut donc
        //utiliser la methode post
        axios.post('employes/update/' + employe.id, data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'employes.index', params: {messages: succesMessage.value}});
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

    const deleteEmploye = async (id, idbtn) => {
        axios.delete('employes/' + id)
            .then((response) => {
                succesMessage.value = 'Supression effectuée avec succès.'
                getEmployes();
                router.push({name: 'employes.index', params: {messages: succesMessage.value}})
                $('#' + idbtn)[0].click();
                $('#btn-confirm').html('OUI')
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            });

    }

    const getIdCategorieService = async (id) => {
        axios.get('idService/idcategorie/' + id)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    employe.id = response.data.data.employe.id;
                    employe.categorie = response.data.data.dataSql.idCategorie;
                    employe.service = response.data.data.dataSql.idService;
                    employe.matricule = response.data.data.employe.matricule;
                    employe.nom = response.data.data.employe.nom;
                    employe.sexe = response.data.data.employe.sexe;
                    employe.prenoms = response.data.data.employe.prenoms;
                    employe.email = response.data.data.employe.email;
                    employe.telephone = response.data.data.employe.telephone;
                    employe.dateEmbauche = response.data.data.employe.dateEmbauche;
                    employe.categorieProf = response.data.data.employe.categorie_professionnelle_id;
                    employe.pathFile = response.data.data.employe.pathFile;
                    employe.fonction = response.data.data.employe.fonction_id;
                    employe.unite = response.data.data.employe.unite_id;
                    employe.respService = response.data.data.employe.respService;
                    employe.respUnite = response.data.data.employe.respUnite;
                    employe.respDepartement = response.data.data.employe.respDepartement;
                    getCategorieProfessionnellesByCategorie(employe.categorie);
                    getFonctionByIdService(employe.service);
                    getUniteByIdService(employe.service);
                    getServices();
                    getCategorieEmployes();

                }

            })
            .catch(error => {
                console.log(error)
                catchErrors(error, succesMessage);
            })
        // dataSQl.idCategorie = response.data.idCategorie;
        // dataSQl.idService = response.data.idService;

    }

    const paginateData = (nbre, page = 1) => {
        axios.get('employe/liste/' + nbre + '?page=' + page)
            .then(response => {
                employes.value = response.data;
                countData.value = response.data.data;
                countPage.value = response.data.meta.last_page;
            });
    }

    const searchRessource = (req) => {
        countData.value = [];
        if (req.length > 3) {
            axios.get('employe/search/' + req)
                .then((response) => {
                    employes.value = response.data;
                    countData.value = response.data.data;
                    countPage.value = response.data.meta.last_page;
                })
                .catch(error => {
                    console.log(error);
                })
        }else {
            getEmployes(10)
        }
    }

    return {
        employe,
        categorieProfessionelles,
        categorieEmployes,
        services,
        fonctions,
        unites,
        dataSQl,
        employes,
        countData,
        countPage,
        tabError,
        errors,
        getIdCategorieService,
        getCategorieProfessionnellesByCategorie,
        getFonctionByIdService,
        getUniteByIdService,
        getEmployes,
        getEmploye,
        createEmploye,
        updateEmploye,
        deleteEmploye,
        paginateData,
        getEmployeByService,
        searchRessource
    }
}
