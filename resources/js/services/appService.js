import moment from "moment";
import Swal from "sweetalert2";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {reactive, ref} from "vue";
import axios from "axios";
export default function servicesApp(){
    const router = useRouter();
    const store = useStore();
    const loadRessource = ref(false);
    const succesMessage = ref('');
    let appUrl = 'http://localhost:8085/api/';
    const load = ref(false);
    const form1 = reactive({
        anciennete: '',
        missions: '',
        objectifs: '',
        criteres: '',
        nouvelleObjectifs: '',
        annee: '',
        employe: '',
        evaluation: '',
        tabAccomplissement: '',
        tabDifficulte: '',
        tabProgres: '',
        formationSuivie: '',
        besoinFormations: '',
        sommeBonus: '',
        commentaires: '',
        performances: '',
        totalperformance: 0,
        totalcompetence: 0,
        total: 0,
        codeCategorie: '',
    })
    const getDate =  (date)=> {
        return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
    }
    const getDate1 =  (date)=> {
        return moment(date, 'YYYY-MM-DD').format('YYYY-MM-DD');
    }

    const catchErrors = (error,succesMessage)=>{
        if (error.response.status === 403  ){
            Swal.fire({
                title: 'Droit d\'accès',
                text: error.response.data.message,
                icon: 'error',
            });
        }

        if ( error.response.status === 419  ){
            Swal.fire({
                title: 'Session token',
                text: 'Votre session est expirée. Reconnectez-vous à nouveau',
                icon: 'error',
            });
        }
        if (error.response.status === 401) {
            if (error.response.data.message === 'Unauthenticated.') {
                succesMessage.value = 'Votre session est expirée. Veuillez vous reconnectez. !'
                router.push({name: 'login', params: {message: succesMessage.value}});
            }
        }
    }
    const  hasRole = (role)=>{
        let user = sessionStorage.getItem('user');
        let roles = JSON.parse(user).roles;
        let found = false;
        for (let i=0;i<roles.length;++i){
            if (roles[i].name ===role && found===false){
                found = true;
            }
        }
        return found;
    }

    const displayDasboard = ()=>{
        let employe = JSON.parse(store.state.salarie);
        if (hasRole('ROLE_ADMIN')||hasRole('ROLE_CODI')){
            router.push({name: 'dashboard.admin'});
        }else if (hasRole('ROLE_RESPONSABLE') && (employe.respService || employe.respUnite)){
            router.push({name: 'dashboard.responsable'});
        }else if (hasRole('ROLE_RESPONSABLE') && employe.respDepartement){
            router.push({name: 'dashboard.departement'});
        }else if (hasRole('ROLE_DIRECTEUR')) {
            router.push({name: 'dashboard.direction'});
        } else {
            router.push({name: 'dashboard.user'});
        }
    }

    const dasboardName = ()=>{
        let employe = JSON.parse(store.state.salarie);
        let name = '';
        if (hasRole('ROLE_ADMIN')||hasRole('ROLE_CODI')){
            name = 'dashboard.admin';
        }else if (hasRole('ROLE_RESPONSABLE') && employe.respService){
            name= 'dashboard.responsable';
        }else if (hasRole('ROLE_RESPONSABLE') && employe.respDepartement){
            name= 'dashboard.departement';
        }else {
            name= 'dashboard.user';
        }
        return name;
    }

    const displayRapport = (id, display) => {
        axios.get('rapport/evaluation/collaborateur/' + id + '/' + display)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                } else {
                    let resp = response.data.data;
                    form1.evaluation = resp.evaluation;
                    form1.employe = resp.employe;
                    form1.codeCategorie = resp.codeCategorie;
                    form1.annee = resp.annee;
                    form1.besoinFormations = resp.besoinFormations;
                    form1.commentaires = resp.commentaires;
                    form1.criteres = resp.criteres;
                    form1.formationSuivie = resp.formationSuivie;
                    form1.missions = resp.missions;
                    form1.nouvelleObjectifs = resp.nouvelleObjectifs;
                    form1.objectifs = resp.objectifs;
                    form1.performances = resp.performances;
                    form1.tabAccomplissement = resp.tabAccomplissement;
                    form1.tabDifficulte = resp.tabDifficulte;
                    form1.tabProgres = resp.tabProgres;
                    form1.totalperformance = resp.totalperformance;
                    form1.totalcompetence = resp.totalcompetence;
                    form1.total = resp.total;
                    load.value = true;
                }
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            })
    }

    const validationBySalirie = async (idUser,idEval,form)=>{
        let data = reactive({
            type: 'evaluateur',
            commentaireUnitaire:form.commentaireUnitaire,
            bonusUnitaire:form.bonusUnitaire,
        });
        $('#btn-confirmValid').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
        await axios.post('cloture/evaluation/' +idUser + '/' + idEval+'/param', {...data})
            .then((response) => {
                if (response.data.data.code === 500){
                    Swal.fire({
                        title: 'Erreur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }
                succesMessage.value = 'Evaluation du collaborateur validée avec succès';
            })
            .catch((error) => {
                catchErrors(error, succesMessage);
            })
    }

    const sleep = (ms) => {
        return new Promise(resolve => setTimeout(resolve, ms));
    }


    return{
        appUrl,
        loadRessource,
        form1,
        load,
        getDate1,
        getDate,
        hasRole,
        catchErrors,
        displayDasboard,
        dasboardName,
        displayRapport,
        validationBySalirie,
        sleep,
    }
}
