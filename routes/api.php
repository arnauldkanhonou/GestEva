 <?php

 use App\Http\Controllers\Api\CampagneObjectifController;
 use App\Http\Controllers\Api\CampagnePerformanceController;
 use App\Http\Controllers\Api\CategorieCritereController;
 use App\Http\Controllers\Api\CategorieEmployeController;
 use App\Http\Controllers\Api\CategorieProfController;
 use App\Http\Controllers\Api\CritereEvaluationController;
 use App\Http\Controllers\Api\CustomerController;
 use App\Http\Controllers\Api\DepartementController;
 use App\Http\Controllers\Api\DirectionController;
 use App\Http\Controllers\Api\EmployeController;
 use App\Http\Controllers\Api\FonctionController;
 use App\Http\Controllers\Api\FormationController;
 use App\Http\Controllers\Api\ImportationDataController;
 use App\Http\Controllers\Api\LoginController;
 use App\Http\Controllers\Api\MissionController;
 use App\Http\Controllers\Api\NiveauObjectifController;
 use App\Http\Controllers\Api\ObjectifController;
 use App\Http\Controllers\Api\PerformanceController;
 use App\Http\Controllers\Api\PonderationCritereController;
 use App\Http\Controllers\Api\RoleController;
 use App\Http\Controllers\Api\ServiceController;
 use App\Http\Controllers\Api\TypeEvaluationController;
 use App\Http\Controllers\Api\UniteController;
 use App\Http\Controllers\EvaluationController;
 use App\Http\Controllers\service\ServiceCustomizeController;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login',[LoginController::class,'authenticate']);
Route::post('logout',[LoginController::class,'logout']);
Route::post('password/reset',[LoginController::class,'resetPassword']);
Route::get('data/salarie/update',[LoginController::class,'miseAjour']);

Route::middleware('auth:sanctum')->group(function (){
   /* Route::apiResource('customers',CustomerController::class);*/
    Route::apiResource('directions',DirectionController::class);
    Route::apiResource('departements',DepartementController::class);
    Route::apiResource('services',ServiceController::class);
    Route::get('service/liste/{nbre}',[ServiceController::class,'ressource']);
    Route::get('service/search/{req}',[ServiceController::class,'search']);
    Route::apiResource('typeevaluations',TypeEvaluationController::class);
    Route::apiResource('categorieemployes',CategorieEmployeController::class);
    Route::apiResource('categorieprofes',CategorieProfController::class);
    Route::get('categoriecriteres/liste/{nbre}',[CategorieCritereController::class,'ressource']);
    Route::get('categoriecriteres/search/{req}',[CategorieCritereController::class,'search']);
    Route::get('categorieprofes/categorie/{id}',[CategorieProfController::class,'getCateProfByCategorie']);
    Route::apiResource('categoriecriteres',CategorieCritereController::class);
    Route::apiResource('critereevaluations',CritereEvaluationController::class);
    Route::get('critereevaluation/liste/{nbre}',[CritereEvaluationController::class,'ressource']);
    Route::get('critereevaluation/search/{req}',[CritereEvaluationController::class,'search']);
    Route::apiResource('fonctions',FonctionController::class);
    Route::get('fonctions/services/{id}',[FonctionController::class,'getFonctionsByService']);
    Route::get('fonction/liste/{nbre}',[FonctionController::class,'ressource']);
    Route::get('fonction/search/{req}',[FonctionController::class,'search']);
    Route::apiResource('unites',UniteController::class);
    Route::get('unite/liste/{nbre}',[UniteController::class,'ressource']);
    Route::get('unite/search/{req}',[UniteController::class,'search']);
    Route::get('unites/services/{id}',[UniteController::class,'getUniteByService']);
    Route::apiResource('employes',EmployeController::class);
    Route::get('employe/liste/{nbre}',[EmployeController::class,'index']);
    Route::get('employe/by/service/{id}/{nbre}',[EmployeController::class,'salarieByService']);
    Route::get('employe/search/{req}',[EmployeController::class,'search']);
    Route::post('salarie/by/paie',[EmployeController::class,'getSalarieOnPaie']);
    Route::post('employes/update/{id}',[EmployeController::class,'updateCustomize']);
    Route::get('idService/idcategorie/{idEmploye}',[EmployeController::class,'getIdcategorieService']);
    Route::get('search/employe/{requestValue}',[ServiceCustomizeController::class,'searchEmploye']);
    Route::apiResource('niveauperformances',PerformanceController::class);
    Route::apiResource('ponderationcriteres',PonderationCritereController::class);
    Route::apiResource('missions',MissionController::class);
    Route::get('mission/liste/{nbre}',[MissionController::class,'index']);
    Route::post('missions/validate',[MissionController::class,'validateMission']);
    Route::post('missions/filtre',[MissionController::class,'filtre']);
    Route::get('missions/search/{req}',[MissionController::class,'search']);
    Route::post('missions/areconduire',[MissionController::class,'MissionAReconduire']);
    Route::post('objectifs/validate',[ObjectifController::class,'validateObjectif']);
    Route::apiResource('niveauexecutionobjectifs',NiveauObjectifController::class);
    Route::apiResource('formations',FormationController::class);
    Route::get('typeformations',[FormationController::class,'typeFormation']);
    Route::apiResource('objectifs',ObjectifController::class);
    Route::get('objectifs/liste/{nbre}',[ObjectifController::class,'index']);
    Route::post('objectifs/filtre',[ObjectifController::class,'filtre']);
    Route::get('objectifs/search/{req}',[ObjectifController::class,'search']);
    Route::apiResource('roles',RoleController::class);
    Route::post('/users',[LoginController::class,'createUser']);
    Route::get('/users',[LoginController::class,'listeUser']);
    Route::get('/users/{id}',[LoginController::class,'show']);
    Route::patch('/users/{id}',[LoginController::class,'editCompte']);
    Route::delete('/users/{id}',[LoginController::class,'destroy']);
    Route::apiResource('campagneobjectifs',CampagneObjectifController::class);
    Route::get('/annees',[ServiceCustomizeController::class,'getAnnees']);
    Route::apiResource('campagneperformances',CampagnePerformanceController::class);
    Route::get('/collaborateurs/{id}/{option}',[ServiceCustomizeController::class,'getCollaborateurs']);
    Route::get('/missions/collaborateurs/{idCollaborateur}',[ServiceCustomizeController::class,'getMissionCollaborateur']);
    Route::get('/objectifs/collaborateurs/{idCollaborateur}',[ServiceCustomizeController::class,'getObjectifCollaborateur']);
    Route::get('/formations/evaluation/collaborateur/{idEvaluation}/{from}',[ServiceCustomizeController::class,'getFormationForEvaluationEncours']);
    Route::get('/objectifs/evaluation/collaborateur/{idEvaluation}/{from}',[ServiceCustomizeController::class,'getObjectifsValider']);
    Route::get('/critere/evaluation/collaborateur/{idCollaborateur}/{idEvaluation}/{from}',[ServiceCustomizeController::class,'getCritereEvaluationEmploye']);
    Route::get('/init/evaluation/collaborateur/{idCollaborateur}/{from}',[EvaluationController::class,'init']);
    Route::post('/add/faire/marquant/collaborateur/{idCollaborateur}/{idEvaluation}/{from}',[EvaluationController::class,'addFaireMarquant']);
    Route::post('/add/formation/suivie/collaborateur/{idCollaborateur}/{idEvaluation}/{from}',[EvaluationController::class,'addFormationSuivie']);
    Route::post('/add/apprciation/objectif/collaborateur/{idCollaborateur}',[EvaluationController::class,'addAppreciationObjectifs']);
    Route::post('/add/commentaire/objectif/collaborateur/{idCollaborateur}',[EvaluationController::class,'addcommentaireObjectif']);
    Route::post('/add/niveau/objectif/collaborateur/{idCollaborateur}/{idEvaluation}/{from}',[EvaluationController::class,'addNiveauObjectif']);
    Route::post('/add/critere/evaluer/{idCollaborateur}/{idEvaluation}/{from}',[EvaluationController::class,'critereEvaluer']);
    Route::post('/add/preoccupation/collaborateur/{idCollaborateur}/{idEvaluation}/{from}',[EvaluationController::class,'addSouhaitPreoccupation']);
    Route::post('/remove/formation/suivie/collaborateur/{idCollaborateur}/{idEvaluation}/{from}',[EvaluationController::class,'removeFormationSuivie']);
    Route::post('/add/besoin/formation/collaborateur/{idCollaborateur}/{idEvaluation}/{from}',[EvaluationController::class,'setBesoinFormationCollaborateur']);
    Route::post('/add/commentaire/evaluation/collaborateur/{idCollaborateur}/{idEvaluation}/{from}',[EvaluationController::class,'addCommentaireEvaluation']);
    Route::post('/delete/commentaire/evaluation/{idCommentaire}',[EvaluationController::class,'deleteCommentaireEvaluation']);
    Route::post('/cloture/evaluation/{idCollaborateur}/{idEvaluation}/{from}',[EvaluationController::class,'clotureEvaluation']);
    Route::get('/verify/campagne/performance/{idCollaborateur}',[EvaluationController::class,'verifyCampagne']);
    Route::post('/destroy/objectif/by/libelle/{idCollaborateur}/{idEvaluation}/{from}',[ObjectifController::class,'destroyByLibelle']);
    Route::get('/evaluation/collaborateur/{idCollaborateur}',[EvaluationController::class,'getEvaluationCollaborateur']);
    Route::get('/get/annees/',[ServiceCustomizeController::class,'getAnnees']);
    Route::get('/get/annee/',[ServiceCustomizeController::class,'getAnnee']);
    Route::get('/init/entretien/collaborateur/{idEvaluation}',[EvaluationController::class,'initEntretienEvaluation']);
    Route::post('/destroy/besoin/formation/by/libelle/{idCollaborateur}/{idEvaluation}/{from}',[EvaluationController::class,'destroyBesoinFormationByLibelle']);
    Route::post('/validation/nouvelle/objectifs/{idEvaluation}',[EvaluationController::class,'validationNouvelleObjectifs']);
    Route::get('/rapport/evaluation/collaborateur/{idEvaluation}/{display}',[EvaluationController::class,'rapportEvaluation']);
    Route::get('/statistique/number/{idAnnee}',[ServiceCustomizeController::class,'statistiqueNumber']);
    Route::get('/formation/suivie/salarie/{idCollaborateur}',[ServiceCustomizeController::class,'getFormationSuivieCollaborateur']);
    Route::get('/evaluations/index/{nbre}',[EvaluationController::class,'getEvaluationSalarie']);
    Route::post('/evaluations/filtre',[EvaluationController::class,'filtreByAnnee']);
    Route::post('/validation/performance',[EvaluationController::class,'validationPerformance']);
    Route::get('/performance/service/{idTypeEva}',[EvaluationController::class,'getPerformanceService']);
//    Route::get('/performance/service/{idAnnee}',[EvaluationController::class,'getPerformanceService']);
    Route::get('/services/by/departement/{depart}',[ServiceCustomizeController::class,'getServiceDepartement']);
    Route::get('/collaborateur/by/service/{idService}/{idTypeEva}',[ServiceCustomizeController::class,'getCollaborateurService']);
//    Route::get('/collaborateur/by/service/{idService}/{idAnnee}',[ServiceCustomizeController::class,'getCollaborateurService']);
    Route::post('/validation/performance/service/{idService}/{idAnnee}',[EvaluationController::class,'validationPerformanceSalarie']);
    Route::get('/service/direction/{idDirection}/{type}',[EvaluationController::class,'getServiceByDirection']);
    Route::get('/performance/service/validate/{idService}/{idAnnee}',[EvaluationController::class,'getPerformanceValidate']);
    Route::post('/validationCodi/performance/{idService}/{groupe}',[EvaluationController::class,'validationCodi']);
    Route::get('/get/rapport/rh/{idAnnee}/{idEmploye}',[EvaluationController::class,'getRapportByRh']);
    Route::get('/liste/salarier/{idAnnee}/{type}',[ServiceCustomizeController::class,'listeSalarierEvaluer']);
    Route::get('/liste/service/{idAnnee}/{type}',[ServiceCustomizeController::class,'listeServiceEvaluer']);
    Route::get('/check/mission/collaborateur/valider/{id}/{idEvaluation}',[EvaluationController::class,'checkMissionIsvalidate']);
    Route::post('/calcul/bonus',[EvaluationController::class,'calculBonus']);
    Route::get('/send/evaluation/for/chief/{idEvaluation}',[EvaluationController::class,'sendEvaluationToChief']);
    Route::post('/invalid/evaluation/{idEvaluation}',[EvaluationController::class,'invalidEvaluation']);
    Route::post('/importation/salaries',[ImportationDataController::class,'importSalarie']);
    Route::get('/init/evaluation/miparcours/{idCollaborateur}/{from}',[EvaluationController::class,'initMipacours']);
    Route::post('/niveau/realisation/miparcours/{idObjectif}',[EvaluationController::class,'niveauRealisationAMiParcours']);
    Route::post('/add/commentaire/miparcours/{idObjectif}',[EvaluationController::class,'addCommentaireAmiParcours']);
    Route::post('/add/commentaire/annuel/{idObjectif}',[EvaluationController::class,'addCommentaireAnnuel']);
    Route::post('/add/realisation/miparcours/{idEvaluation}',[EvaluationController::class,'addRealisationAmiParcours']);
    Route::get('/remove/realisation/miparcours/{idRealiation}/{idEvaluation}',[EvaluationController::class,'removeRealisation']);
    Route::post('/add/commentaire/evaluation/miparcours/{idEvaluation}',[EvaluationController::class,'addCommentaireEvaluationAmiParcours']);
    Route::post('/cloture/evaluation/miparcours/{idEvaluation}',[EvaluationController::class,'clotureEvaluationAmiparcours']);
    Route::get('/evaluation/by/type/{idUser}/{filtre}/{chief}',[EvaluationController::class,'getEvaluationByType']);
    Route::get('/show/evaluation/miparcours/{idEvaluation}',[EvaluationController::class,'showEvaluationMipacours']);
    Route::get('/init/entretien//miparcours/{idEvaluation}',[EvaluationController::class,'initEntretienMiparcours']);
    Route::get('/get/liste/notepondere',[ServiceCustomizeController::class,'getPerformanceGlobale']);
    Route::get('/get/notepondere/service/{service_id}',[ServiceCustomizeController::class,'getPerformanceService']);
    Route::get('/get/liste/evaluateur',[ServiceCustomizeController::class,'getEvaluateurs']);
    Route::get('/get/liste/evaluateur/by/service/{id}',[ServiceCustomizeController::class,'getEvaluateursByService']);
    Route::get('/evaluateur/search/{req}',[ServiceCustomizeController::class,'searchEvaluateur']);
    Route::post('/calcul/cagnotte',[EvaluationController::class,'calculCagnote']);
    Route::get('/verify/cloture',[EvaluationController::class,'verifyCloture']);
    Route::get('/cloturer/toute/evaluation',[EvaluationController::class,'cloturerAllEvaluationsByCodi']);
    Route::post('/definir/beneficiaire/primeexcept',[EvaluationController::class,'definedBeneficiarePrimeExceptionnelle']);
    Route::post('/remove/beneficiaire/primeexcept',[EvaluationController::class,'removeBeneficiairePrimeExceptionnelle']);
    Route::post('/set/value/primeexcept/salarie',[EvaluationController::class,'setValeurPrimeExceptionnelle']);
    Route::get('/check/liste/beneficiaire',[EvaluationController::class,'checkListeValider']);
    Route::get('/valider/liste/beneficiaire',[EvaluationController::class,'validerListeBeneficiaire']);
    Route::get('/get/evaluation/miparcours/service/{idService}',[EvaluationController::class,'getEvaluationMipacoursService']);
    Route::get('verify/entretien/miparcours/{idColab}',[EvaluationController::class,'verifyEntretienMiparcours']);
    Route::get('verify/entretien/annuel/{idColab}',[EvaluationController::class,'verifyEntretienAnnuel']);
});

