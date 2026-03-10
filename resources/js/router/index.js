import { createRouter, createWebHistory } from "vue-router";
import {useStore} from "vuex";
import store from "../store/index"
import Customerindex from "../components/customers/Customerindex.vue";
import CustomerCreate from "../components/customers/CustomerCreate.vue";
import CustomerEdit from "../components/customers/CustomerEdit.vue";
import DirectionIndex from "../components/directions/DirectionIndex";
import DirectionCreate from "../components/directions/DirectionCreate";
import DirectionEdit from "../components/directions/DirectionEdit";
import departementIndex from "../components/departements/departementIndex";
import departementCreate from "../components/departements/departementCreate";
import departementEdit from "../components/departements/departementEdit";
import serviceIndex from "../components/services/serviceIndex";
import serviceCreate from "../components/services/serviceCreate";
import serviceEdit from "../components/services/serviceEdit";
import typeEvaluationIndex from "../components/TypeEvaluation/TypeEvaluationIndex";
import TypeEvaluationCreate from "../components/TypeEvaluation/TypeEvaluationCreate";
import TypeEvaluationEdit from "../components/TypeEvaluation/TypeEvaluationEdit";
import CategorieEmployeIndex from "../components/CategorieEmploye/CategorieEmployeIndex";
import CategorieEmployeCreate from "../components/CategorieEmploye/CategorieEmployeCreate";
import CategorieEmployeEdit from "../components/CategorieEmploye/CategorieEmployeEdit";
import CategorieProfIndex from "../components/CategorieProfessionnelle/CategorieProfIndex";
import CategorieProfCreate from "../components/CategorieProfessionnelle/CategorieProfCreate";
import CategorieProfEdit from "../components/CategorieProfessionnelle/CategorieProfEdit";
import CategorieCritereIndex from "../components/CategorieCritere/CategorieCritereIndex";
import CategorieCritereCreate from "../components/CategorieCritere/CategorieCritereCreate";
import CategorieCritereEdit from "../components/CategorieCritere/CategorieCritereEdit";
import CritereEvaluationIndex from "../components/critereEvaluation/CritereEvaluationIndex";
import CritereEvaluationCreate from "../components/critereEvaluation/CritereEvaluationCreate";
import CritereEvaluationEdit from "../components/critereEvaluation/CritereEvaluationEdit";
import FonctionIndex from "../components/Fonctions/FonctionIndex";
import FonctionCreate from "../components/Fonctions/FonctionCreate";
import FonctionEdit from "../components/Fonctions/FonctionEdit";
import UniteIndex from "../components/Unites/UniteIndex";
import UniteCreate from "../components/Unites/UniteCreate";
import UniteEdit from "../components/Unites/UniteEdit";
import EmployeIndex from "../components/Employes/EmployeIndex";
import evaluateur from "../components/Employes/evaluateurs.vue";
import EmployeCreate from "../components/Employes/EmployeCreate";
import EmployeEdit from "../components/Employes/EmployeEdit";
import NiveauPerformanceIndex from "../components/NiveauPerformance/NiveauPerformanceIndex";
import NiveauPerformanceCreate from "../components/NiveauPerformance/NiveauPerformanceCreate";
import NiveauPerformanceEdit from "../components/NiveauPerformance/NiveauPerformanceEdit";
import PonderationCritereIndex from "../components/PonderationCritere/PonderationCritereIndex";
import PonderationCritereCreate from "../components/PonderationCritere/PonderationCritereCreate";
import PonderationCritereEdit from "../components/PonderationCritere/PonderationCritereEdit";
import MissionCreate from "../components/Missions/MissionCreate";
import MissionIndex from "../components/Missions/MissionIndex";
import MissionEdit from "../components/Missions/MissionEdit";
import NIveauObjectifIndex from "../components/NiveauExecutionObjectifs/NIveauObjectifIndex";
import NiveauObjectifCreate from "../components/NiveauExecutionObjectifs/NiveauObjectifCreate";
import NiveauObjectifsEdit from "../components/NiveauExecutionObjectifs/NiveauObjectifsEdit";
import FormationIndex from "../components/Formations/FormationIndex";
import FormationEdit from "../components/Formations/FormationEdit";
import FormationCreate from "../components/Formations/FormationCreate";
import ObjectifIndex from "../components/objectifs/ObjectifIndex";
import ObjectifCreate from "../components/objectifs/ObjectifCreate";
import ObjectifEdit from "../components/objectifs/ObjectifEdit";
import Login from "../components/block/Login";
import App from "../components/block/App";
import dashbord from "../components/block/dashbord";
import ResetPassword from "../components/block/ResetPassword";
import RoleIndex from "../components/Roles/RoleIndex";
import RoleCreate from "../components/Roles/RoleCreate";
import RoleEdit from "../components/Roles/RoleEdit";
import userIndex from "../components/Users/userIndex";
import userCreate from "../components/Users/userCreate";
import userEdit from "../components/Users/userEdit";
import campagneObjectifIndex from "../components/campagneObjectif/campagneObjectifIndex";
import campagneObjectifCreate from "../components/campagneObjectif/campagneObjectifCreate";
import campagneObjectifEdit from "../components/campagneObjectif/campagneObjectifEdit";
import CampagnePerformanceIndex from "../components/CampagnePerformance/CampagnePerformanceIndex";
import CampagnePerformanceCreate from "../components/CampagnePerformance/CampagnePerformanceCreate";
import CampagnePerformanceEdit from "../components/CampagnePerformance/CampagnePerformanceEdit";
import CollaborateurIndex from "../components/collaborateurs/CollaborateurIndex";
import autoEvaluation from "../components/evaluations/autoEvaluation";
import ValidationMission from "../components/collaborateurs/ValidationMission";
import ValidationObjectif from "../components/collaborateurs/ValidationObjectif";
import evaluationIndex from "../components/evaluations/evaluationIndex";
import evaluationCollaborateurs from "../components/collaborateurs/evaluationCollaborateurs";
import EntretienEvaluation from "../components/collaborateurs/EntretienEvaluation";
import boardAdmin from "../components/dashboard/boardAdmin";
import boardUser from "../components/dashboard/boardUser";
import boardResponsable from "../components/dashboard/boardResponsable";
import boardDepart from "../components/dashboard/boardDepart";
import EvaluationSalarieIndex from "../components/collaborateurs/EvaluationSalarieIndex";
import missionSalarie from "../components/Rh/missionSalarie";
import objectifSalarie from "../components/Rh/objectifSalarie";
import evaluationSalarie from "../components/Rh/evaluationSalarie";
import formationSalarie from "../components/Rh/formationSalarie";
import validationPerformance from "../components/validation/validationPerformance";
import validationDirecteur from "../components/validation/validationDirecteur";
import consultationPerformance from "../components/validation/consultationPerformance";
import validationRespDepart from "../components/validation/validationRespDepart";
import consultationPerformanceRd from "../components/validation/consultationPerformanceRd";
import boardDirecteur from "../components/dashboard/boardDirecteur";
import consultationPerformanceDir from "../components/validation/consultationPerformanceDir";
import historiquePerformance from "../components/validation/historiquePerformance";
import validationCodi from "../components/validation/validationCodi";
import salarierEvaluer from "../components/Rh/salarierEvaluer";
import serviceEvaluer from "../components/Rh/serviceEvaluer";
import simulationPerformance from "../components/validation/simulationPerformance";
import bonus from "../components/Rh/bonus";
import autoEvaluationSalarieByResp from "../components/collaborateurs/autoEvaluationSalarieByResp";
import laureatBonus from "../components/Rh/laureatBonus";
import importation from "../components/importationData/importation";
import misPacours from "../components/evaluations/misPacours";
import EntretienMiParcours from "../components/collaborateurs/EntretienMiParcours";
import missionReconduire from "../components/Missions/missionReconduire";
import clotureEvaluation from "../components/rh/cloture"
import ConsultationmisParcoursByRh from "../components/Rh/consultationmisParcoursByRh"
import evaluationMiparcousByResponsable from "../components/collaborateurs/evaluationMiparcousByResponsable.vue";
import entretien from "../components/collaborateurs/entretien.vue";

const routes = [

    {
        path:'/connect',
        name:'login',
        component: Login,
        props: true,
    },
    {
        path:'/reset-password',
        name:'reset.password',
        component: ResetPassword,
        props: true,
        meta: {requiresAuth:true}
    },
    {
        path:'/',
        name:'dashbord',
        component: dashbord,
        meta: {requiresAuth:true},
        children:[
            {
                path:'dashboard/admin',
                name: 'dashboard.admin',
                component: boardAdmin,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'dashboard/user',
                name: 'dashboard.user',
                component: boardUser,
                props: true,
                meta: {requiresAuth: true}
            }
            , {
                path:'dashboard/master',
                name: 'dashboard.responsable',
                component: boardResponsable,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'dashboard/chief/departement',
                name: 'dashboard.departement',
                component: boardDepart,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'dashboard/direction',
                name: 'dashboard.direction',
                component: boardDirecteur,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'direction',
                name:'directions.index',
                component: DirectionIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path:'direction/create',
                name:'directions.create',
                component: DirectionCreate,
                meta: {requiresAuth:true}
            },
            {
                path:'direction/:id/edit',
                name:'directions.edit',
                component: DirectionEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'departement',
                name: 'departements.index',
                component: departementIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'departement/create',
                name: 'departements.create',
                component: departementCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'departement/:id/edit',
                name: 'departements.edit',
                component: departementEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'service',
                name: 'services.index',
                component: serviceIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'service/create',
                name: 'services.create',
                component: serviceCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'service/:id/edit',
                name: 'services.edit',
                component: serviceEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'type-evaluation',
                name: 'typeevaluations.index',
                component: typeEvaluationIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'type-evaluation/create',
                name:'typeevaluations.create',
                component: TypeEvaluationCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'type-evaluation/:id/edit',
                name: 'typeevaluations.edit',
                component: TypeEvaluationEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'categorie-employe',
                name: 'categorie.employe.index',
                component: CategorieEmployeIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'categorie-employe/create',
                name: 'categorie.employe.create',
                component: CategorieEmployeCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'categorie-employe/:id/edit',
                name: 'categorie.employe.edit',
                component: CategorieEmployeEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'categorie-professionnelle',
                name: 'categorie.professionnelles.index',
                component: CategorieProfIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'categorie-professionnelle/create',
                name: 'categorie.professionnelles.create',
                component: CategorieProfCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'categorie-professionnelle/:id/edit',
                name: 'categorie.professionnelles.edit',
                component: CategorieProfEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'categorie-critere',
                name: 'categories.cirtere.index',
                component: CategorieCritereIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'categorie-critere/create',
                name: 'categories.cirtere.create',
                component: CategorieCritereCreate
            },
            {
                path: 'categorie-critere/:id/edit',
                name: 'categories.cirtere.edit',
                component: CategorieCritereEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'critere-evaluation',
                name: 'cirteres.evaluation.index',
                component: CritereEvaluationIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'critere-evaluation/create',
                name: 'cirteres.evaluation.create',
                component: CritereEvaluationCreate,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'critere-evaluation/:id/edit',
                name: 'criteres.evaluation.edit',
                component: CritereEvaluationEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'fonction',
                name: 'fonctions.index',
                component: FonctionIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'fonction/create',
                name: 'fonctions.create',
                component: FonctionCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'fonction/:id/edit',
                name: 'fonctions.edit',
                component: FonctionEdit,
                props: true,
                meta: {requiresAuth:true},
            },
            {
                path: 'unite',
                name: 'unites.index',
                component: UniteIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: '/unite/create',
                name: 'unites.create',
                component: UniteCreate,
                meta: {requiresAuth:true}
            },
            {
                path: '/unite/:id/edit',
                name: 'unites.edit',
                component: UniteEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'employe',
                name: 'employes.index',
                component: EmployeIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'employe/create',
                name: 'employes.create',
                component: EmployeCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'employe/:id/edit',
                name: 'employes.edit',
                component: EmployeEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'evaluateur',
                name: 'evaluateur.index',
                component: evaluateur,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'niveau-performance',
                name: 'niveau.performances.index',
                component: NiveauPerformanceIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'niveau-performance/create',
                name: 'niveau.performances.create',
                component: NiveauPerformanceCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'niveau-performance/:id/edit',
                name: 'niveau.performances.edit',
                component: NiveauPerformanceEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'ponderation-criteres',
                name: 'ponderation.criteres.index',
                component: PonderationCritereIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'ponderation-critere/create',
                name: 'ponderation.criteres.create',
                component: PonderationCritereCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'ponderation-critere/:id/edit',
                name: 'ponderation.criteres.edit',
                component: PonderationCritereEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'missions',
                name: 'missions.index',
                component: MissionIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'missions/create',
                name: 'missions.create',
                component: MissionCreate,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'missions/reconduire',
                name: 'missions.reconduire',
                component: missionReconduire,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'missions/:id/edit',
                name: 'missions.edit',
                component: MissionEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'niveau-execution-objectifs',
                name: 'niveau.execution.objectifs.index',
                component: NIveauObjectifIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'niveau-execution-objectifs/create',
                name: 'niveau.execution.objectifs.create',
                component: NiveauObjectifCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'niveau-execution-objectifs/:id/edit',
                name: 'niveau.execution.objectifs.edit',
                component: NiveauObjectifsEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'formation',
                name: 'formations.index',
                component: FormationIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'formation/create',
                name: 'formations.create',
                component: FormationCreate,
                meta: {requiresAuth:true}
            },
            {
                path: 'formation/:id/edit',
                name: 'formations.edit',
                component: FormationEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'objectifs',
                name: 'objectifs.index',
                component: ObjectifIndex,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'objectif/create',
                name: 'objectifs.create',
                component: ObjectifCreate,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'objectif/:id/edit',
                name: 'objectifs.edit',
                component: ObjectifEdit,
                props: true,
                meta: {requiresAuth:true}
            },
            {
                path: 'roles',
                name: 'roles.index',
                component: RoleIndex,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'role/create',
                name: 'roles.create',
                component: RoleCreate,
                meta: {requiresAuth: true}
            },
            {
                path: 'role/:id/edit',
                name: 'roles.edit',
                component: RoleEdit,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'users',
                name: 'users.index',
                component: userIndex,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'users/create',
                name: 'users.create',
                component: userCreate,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'users/:id/edit',
                name: 'users.edit',
                component: userEdit,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'campagne-objectif',
                name: 'campagne.objectif.index',
                component: campagneObjectifIndex,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'campagne-objectif/create',
                name: 'campagne.objectif.create',
                component: campagneObjectifCreate,
                meta: {requiresAuth: true}
            },
            {
                path: 'campagne-objectif/:id/edit',
                name: 'campagne.objectif.edit',
                component: campagneObjectifEdit,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'campagne-performance',
                name: 'campagne.performance.index',
                component: CampagnePerformanceIndex,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'campagne-performance/create',
                name: 'campagne.performance.create',
                component: CampagnePerformanceCreate,
                meta: {requiresAuth: true}
            },
            {
                path: 'campagne-performance/:id/edit',
                name: 'campagne.performance.edit',
                component: CampagnePerformanceEdit,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'collaborateurs/:idColab',
                name: 'collaborateurs.index',
                component: CollaborateurIndex,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'evaluation-index',
                name: 'evaluations.index',
                component: evaluationIndex,
                props: true,
                meta: {requiresAuth: true}
            }
            ,{
                path:'auto-evaluation',
                name: 'auto.evaluations.index',
                component: autoEvaluation,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'collaborateurs/mission/:id/:intitule',
                name: 'collaborateur.mission',
                component: ValidationMission,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'collaborateurs/objectif/:id/:intitule',
                name: 'collaborateur.objectifs',
                component: ValidationObjectif,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'collaborateurs/evaluation/:id/:intitule',
                name: 'collaborateur.evaluations',
                component: evaluationCollaborateurs,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'entretien/collaborateurs/:id/intitule',
                name: 'entretien.evaluations',
                component: EntretienEvaluation,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'evaluation/user',
                name: 'evaluations.user.index',
                component: EvaluationSalarieIndex,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'rh/mission/salarie/:id/:intitule',
                name: 'missions.salarie.rh',
                component: missionSalarie,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'rh/objectif/salarie/:id/:intitule',
                name: 'objectifs.salarie.rh',
                component: objectifSalarie,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'rh/evaluation/salarie/:id/:intitule',
                name: 'evaluations.salarie.rh',
                component: evaluationSalarie,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'rh/formation/suive/salarie/:id/:intitule',
                name: 'evaluations.formations.rh',
                component: formationSalarie,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'validation/performance',
                name: 'validations.performance',
                component: validationPerformance,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'consultation/performance',
                name: 'consultations.performance',
                component: consultationPerformance,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'consultation/performance/rd',
                name: 'consultations.performance.pole',
                component: consultationPerformanceRd,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'validation/performance/rd',
                name: 'validation.performance.pole',
                component: validationRespDepart,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'validation/performance/direction',
                name: 'validations.performance.directions',
                component: validationDirecteur,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'consultation/performance/direction',
                name: 'consultation.performance.directions',
                component: consultationPerformanceDir,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'historique/performance/direction',
                name: 'historique.performance.directions',
                component: historiquePerformance,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path:'validation/performance/codi',
                name: 'validation.performance.codi',
                component: validationCodi,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'salarie/evaluer/:annee',
                name: 'salaier.evaluer',
                component: salarierEvaluer,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'services/evaluer/:annee',
                name: 'services.evaluer',
                component: serviceEvaluer,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'codi/simulation/performance',
                name: 'simulation.performance',
                component: simulationPerformance,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'calcul/performance',
                name: 'calcul.performance',
                component: bonus,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'laureat/bonus',
                name: 'laureat.bonus',
                component: laureatBonus,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'codi/misparcours/consultation',
                name: 'codi.misparcours',
                component: ConsultationmisParcoursByRh,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'cloture/evaluation',
                name: 'cloture.evaluation',
                component: clotureEvaluation,
                props: false,
                meta: {requiresAuth: true}
            },
            {
                path: 'auto-evaluation/salarie/:id/:intitule/by/responsable',
                name: 'auto.evaluation.salarie.by.responsable',
                component: autoEvaluationSalarieByResp,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'evaluation-mi-parcours/:id/:intitule/by/responsable',
                name: 'evaluation.miparcours.by.responsable',
                component: evaluationMiparcousByResponsable,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'importation/data',
                name: 'importation.data',
                component: importation,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'evaluation/mis-pacours',
                name: 'evaluation.mis.pacours',
                component: misPacours,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'entretien/mis-pacours/:id',
                name: 'entretien.mis.pacours',
                component: EntretienMiParcours,
                props: true,
                meta: {requiresAuth: true}
            },
            {
                path: 'entretien/evaluation/:id/:intitule',
                name: 'entretien.evaluation',
                component: entretien,
                props: true,
                meta: {requiresAuth: true}
            }
        ]
    },

];

const router = createRouter({
    history:createWebHistory(),
    routes,
    scrollBehavior(to,from,savedPosition){
        if (to.hash){
            return {selector: to.hash}
        }
        if (savedPosition){
            return savedPosition
        }
        return { left: 0,top:0}
    }
})

router.beforeEach((to,from,next)=>{
    //console.log(to.meta.requiresAuth,'ok',store.state.authenticate);
    if (to.meta.requiresAuth && !store.state.authenticate){
        //console.log('bandit',sessionStorage.getItem('authenticate'))
        next({name:'login'})
    }else {
        next()
    }
});

export default router
