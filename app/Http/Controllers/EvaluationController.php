<?php


namespace App\Http\Controllers;


use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Http\Traits\CustormRequestSqlTrait;
use App\Models\Annee;
use App\Models\AutreRealisation;
use App\Models\BesoinFormation;
use App\Models\CampagnePerformance;
use App\Models\CategorieEmploye;
use App\Models\CategorieProfessionnelle;
use App\Models\Commentaire;
use App\Models\Employe;
use App\Models\Evaluation;
use App\Models\Fonction;
use App\Models\Formation;
use App\Models\LaureatEvaluation;
use App\Models\Mission;
use App\Models\NiveauExecutionObjectif;
use App\Models\NiveauPerformance;
use App\Models\Objectif;
use App\Models\PonderationCritere;
use App\Models\Service;
use App\Models\TypeEvaluation;
use App\Models\User;
use App\Models\Validation;
use App\Tools\DateConverter;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Type;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use function PHPUnit\Framework\isEmpty;

class EvaluationController extends Controller
{
    use CustormRequestSqlTrait;
    protected $mouchard;

    public function __construct(MouchardApp $mouchardApp)
    {
        $this->mouchard = $mouchardApp;
        $this->middleware(['user'])->except('downloadListeBeneficiaire');
    }

    public function getEvaluationSalarie(Request $request,$nbre=10)
    {
        $employe = Employe::where('email', $request->user()->email)->first();

        if ($employe==''){
            return GlobalResource::make([]);
        }
        /*$req = "select eval.id,eval.accomplissement,eval.difficulteMission,eval.progres,eval.dateEvaluation,eval.clotureCodi,val.niveau1,
                val.niveau2,val.niveau3,val.niveau4,annees.libelle as annee,'EA'as typeEva
                from evaluations eval
                inner join validations val on val.evaluation_id=eval.id
                inner join annees on annees.id = eval.annee_id
                where eval.employe_id = $employe->id";*/
        $evaluations = DB::table('evaluations')
            ->join('validations','evaluations.id','=','validations.evaluation_id')
            ->join('annees','annees.id','=','evaluations.annee_id')
            ->join('type_evaluations','type_evaluations.id','=','evaluations.type_evaluation_id')
            ->select('evaluations.id','evaluations.accomplissement','evaluations.difficulteMission','evaluations.progres','evaluations.dateEvaluation','evaluations.clotureCodi','evaluations.clotureResp','validations.niveau1',
                'validations.niveau2','validations.niveau3','validations.niveau4','annees.libelle as annee',"type_evaluations.code as typeEva")
            ->where('evaluations.employe_id', $employe->id)
            ->paginate($nbre);

        return $evaluations;
    }

    public function filtreByAnnee(Request $request){
        $employe = Employe::where('email', $request->user()->email)->first();

        if ($employe==''){
            return GlobalResource::make([]);
        }
        $evaluations = DB::table('evaluations')
            ->join('validations','evaluations.id','=','validations.evaluation_id')
            ->join('annees','annees.id','=','evaluations.annee_id')
            ->join('type_evaluations','type_evaluations.id','=','evaluations.type_evaluation_id')
            ->select('evaluations.id','evaluations.accomplissement','evaluations.difficulteMission','evaluations.progres','evaluations.dateEvaluation','evaluations.clotureCodi','validations.niveau1',
                'validations.niveau2','validations.niveau3','validations.niveau4','annees.libelle as annee',"type_evaluations.code as typeEva")
            ->where('evaluations.employe_id', $employe->id)
            ->where('annees.id',$request->annee)
            ->get();

        return GlobalResource::make($evaluations);
    }

    public function getEvaluationByType(Request $request,$idUser,$filtre,$chief){
        try {
            if ($chief=='false'){
                $user = User::find($idUser);
                $employe = Employe::where('email',$user->email)->first();
            }else{
                $employe = Employe::find($idUser);
            }

            $type = TypeEvaluation::where('code',$filtre)->first();
            $evaluations = DB::table('evaluations')
                ->join('annees','annees.id','=','evaluations.annee_id')
                ->join('type_evaluations','type_evaluations.id','=','evaluations.type_evaluation_id')
                ->select('evaluations.id','evaluations.accomplissement','evaluations.difficulteMission','evaluations.progres','evaluations.dateEvaluation','evaluations.clotureCodi','annees.libelle as annee',"type_evaluations.code as typeEva")
                ->where('evaluations.employe_id', $employe->id)
                ->where('type_evaluations.id',$type->id)
                ->get();


            /*$req = "select eval.id,eval.accomplissement,eval.difficulteMission,eval.progres,eval.dateEvaluation,eval.clotureCodi,
               annees.libelle as annee,typeEv.code as typeEva
                from evaluations eval
                inner join annees on annees.id = eval.annee_id
                inner join type_evaluations typeEv on typeEv.id = eval.type_evaluation_id
                where eval.employe_id = $employe->id
                and typeEv.id = $type->id ";*/
            if ($request->user()->hasRole('ROLE_RESPONSABLE')){
                /*$req .=' and eval.transmis=1';*/
                $evaluations = DB::table('evaluations')
                    ->join('annees','annees.id','=','evaluations.annee_id')
                    ->join('type_evaluations','type_evaluations.id','=','evaluations.type_evaluation_id')
                    ->select('evaluations.id','evaluations.accomplissement','evaluations.difficulteMission','evaluations.progres','evaluations.dateEvaluation','evaluations.clotureCodi','annees.libelle as annee',"type_evaluations.code as typeEva")
                    ->where('evaluations.employe_id', $employe->id)
                    ->where('type_evaluations.id',$type->id)
                    //->where('evaluations.transmis','=',1)
                    ->get();
            }

            return GlobalResource::make($evaluations);
        }catch (\Exception $exception){
            return GlobalResource::make(['message'=>$exception->getMessage(),'code'=>500]);
        }
    }

    public function showEvaluationMipacours(Request $request,$idEvaluation){
        $evaluation = Evaluation::with('annee')->find($idEvaluation);
        if ($evaluation!=''&& $evaluation!=null){
           // $campagne = CampagnePerformance::where('annee_id', $evaluation->annee_id)->first();
            $employe = Employe::with('categorie_professionnelle','fonction')->find($evaluation->employe_id);
            $curentDate = date_create(date('Y/m/d'));
            $dateEmbauche = date_create($employe->dateEmbauche);
            $anciennete = date_diff($curentDate, $dateEmbauche);
            $year = $anciennete->y;
            $month = $anciennete->m;
            $days = $anciennete->d;
            $anciennete = '';
            if ($year != 0) {
                $anciennete .= $year . ' an(s)';
            }

            if ($month != 0) {
                $anciennete .= ' ' . $month . ' mois';
            }

            if ($month != 0) {
                $anciennete .= ' ' . $days . ' jours';
            }

            $tabAccomplissement = explode(';', $evaluation->accomplissement);
            $tabDifficulte = explode(';', $evaluation->difficulteMission);

            $objectifs = Objectif::where('employe_id', $employe->id)
                ->where('annee_id', $evaluation->annee->id)
                ->where('NivRealisationMPEvaluateur', '!=', 'NULL')
                ->get();

            $service = Service::find($employe->fonction->service_id);

            $autreRealisations = AutreRealisation::where('evaluation_id',$evaluation->id)->get();
            return new GlobalResource([
                'code'=>200,
                'anciennete' => $anciennete,
                'objectifs' => $objectifs,
                'autreRealisations'=>$autreRealisations,
                'evaluation' => $evaluation,
                'tabAccomplissement' => $tabAccomplissement,
                'tabDifficulte' => $tabDifficulte,
                'employe' => $employe,
                'annee'=>$evaluation->annee,
                'fonction'=>$employe->fonction,
                'service'=>$service,
                ]);
        }else{

        }

    }

    public function initMipacours(Request $request, $idCollaborateur,$from = '')
    {
        $typeEvalutionMiparcours = TypeEvaluation::where('code','EMP')->first();
        $campagne = CampagnePerformance::where('cloturer', false)
            ->where('type_evaluation_id',$typeEvalutionMiparcours->id)
            ->first();
        if ($campagne == '' or $campagne == null) {
            return GlobalResource::make(['code' => 403, 'message' => 'Aucune campagne d\'évaluation mi-parcours lancée par les RH']);
        }
        if ($campagne->type_evaluation->code!='EMP'){
            return GlobalResource::make(['code' => 403, 'message' => 'Aucune campagne d\'évaluation mi-parcours lancée par les RH']);
        }
        $plageA = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
        $plageB = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
        $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

        if ($plageA->lte($dateCurrent) && $dateCurrent->lte($plageB)) {
        } else {
            return GlobalResource::make(['code' => 403, 'message' => 'La période définie pour la campagne des performances est expirée! Contactez les RH.']);
        }
        $typeEvaluation = TypeEvaluation::where('code', 'EMP')->first();
        if ($typeEvaluation == '' || $typeEvaluation == null) {
            return GlobalResource::make(['code' => 403, 'message' => 'Impossible de démarrer car le type d\'évaluation est introuvable.']);
        }
        if ($from == 'evaluationMiparcoursSalarieByResp') {
            $employe = Employe::find($idCollaborateur);
        } else {
            $user = User::find($idCollaborateur);
            $employe = Employe::where('email', $user->email)->first();
        }
        if ($employe==''){
            return GlobalResource::make(['code' => 403, 'message' => 'Impossible de démarrer ! Votre profil salarié est introuvable.']);
        }
        $evaluation = Evaluation::where('employe_id', $employe->id)
            ->where('annee_id', $campagne->annee_id)
            ->where('type_evaluation_id', $typeEvaluation->id)
            /*->where('transmis', 0)->where('clotureResp', 0)*/->get();
        if (count($evaluation) > 0) {
            $evaluation = $evaluation[0];

            if ($evaluation->transmis) {
                return GlobalResource::make(['code' => 403, 'message' => 'Vous avez terminé déjà votre évaluation mi-parcours et ce dernier est dans le processus de validation']);
            }

            $tabAccomplissement = explode(';', $evaluation->accomplissement);
            $tabDifficulte = explode(';', $evaluation->difficulteMission);
            $tabProgres = explode(';', $evaluation->progres);
            $realisations = AutreRealisation::where('evaluation_id',$evaluation->id)->get();
            $tabRealisation = array();

            foreach ($realisations as $realisation){
                array_push($tabRealisation,[
                    'id'=>$realisation->id,
                    'libelle'=>$realisation->realisation,
                    'difficulte'=>$realisation->difficultes
                ]);
            }
            return GlobalResource::make([
                'code' => 500,
                'tabAccomplissement' => $tabAccomplissement,
                'tabDifficulte' => $tabDifficulte, 'tabProgres' => $tabProgres,
                'evaluation' => $evaluation,
                'realisations'=>$tabRealisation
            ]);
        }

        $objectifsValider = Objectif::where('employe_id', $employe->id)
            ->where('valider', true)
            ->where('ignorer', false)
            ->where('annee_id', $campagne->annee_id)
            ->orderBy('created_at', 'desc')
            ->get();

        if (count($objectifsValider) < 5) {
            return GlobalResource::make(['code' => 403, 'message' => 'Impossible de démarrer ! Vos objectifs de cette année ne sont pas validée ou n\'atteignent pas 5. Contactez votre responsable']);
        }

        $evaluation = Evaluation::create([
            'dateEvaluation' => (new \DateTime())->format('Y-d-m H:i:s'),
            'employe_id' => $employe->id,
            'annee_id' => $campagne->annee_id,
            'type_evaluation_id' => $typeEvaluation->id,
        ]);

        $evaluation->save();

        return GlobalResource::make($evaluation);

    }

    public function addCommentaireEvaluationAmiParcours(Request $request,$idEvaluation){
        $campagne = CampagnePerformance::where('cloturer', false)->first();
        $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();

        $evaluation->updated_at = (new \DateTime())->format('Y-d-m H:i:s');

        if ($request->type == 'evaluer' && $request->commentaire != '' && $request->commentaire != 'null') {
            $evaluation->commentaireEvaluer = $request->commentaire;
        }

        if ($request->type == 'evaluateur' && $request->commentaire != '' && $request->commentaire != 'null') {
            $evaluation->commentaireResp = $request->commentaire;
        }

        $evaluation->save();

        return GlobalResource::make($evaluation);
    }

    public function niveauRealisationAMiParcours(Request $request, $idObjectif)
    {
        $objectif = Objectif::find($idObjectif);
        try {
            if ($request->type == 'evaluer') {
                $objectif->NivRealisationMPEvaluer = $request->niveau;
                $objectif->NivRealisationMPEvaluateur = $request->niveau;
            } else {
                $objectif->NivRealisationMPEvaluateur = $request->niveau;
            }

            $objectif->save();

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement niveau mi-parcours',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'niveauRealisationAMiParcours'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement du niveau à mi-parcours. Contactez IT']);

        }

        return GlobalResource::make($objectif);

    }

    public function addCommentaireAnnuel(Request $request, $idObjectif)
    {
        try {
            $objectif = Objectif::find($idObjectif);
            if ($request->type == 'evaluer') {
                $objectif->commentaireEvaluer = $request->commentaire;
                $objectif->commentaireResp = $request->commentaire;
            } else {
                $objectif->commentaireResp = $request->commentaire;
            }
            $objectif->save();
            return GlobalResource::make(['code' => 200, 'message' => 'opération effectuée avec succes']);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement commentaire annuel sur objectif',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addCommentaireAnnuel'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement du commentaire à mi-parcours. Contactez IT']);

        }
    }
    public function addCommentaireAmiParcours(Request $request, $idObjectif){
        try {
            $objectif = Objectif::find($idObjectif);
            if ($request->type == 'evaluer') {
                $objectif->commentaireEvaluerMP = $request->commentaire;
                $objectif->commentaireEvaluateurMP = $request->commentaire;
            } else {
                $objectif->commentaireEvaluateurMP = $request->commentaire;
            }

            $objectif->save();
            return GlobalResource::make(['code' => 200, 'message' => 'opération effectuée avec succes']);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement commentaire mi-parcours',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addCommentaireAmiParcours'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement du commentaire à mi-parcours. Contactez IT']);

        }
    }

    public function addRealisationAmiParcours(Request $request, $idEvaluation)
    {
        try {
            $evaluation = Evaluation::find($idEvaluation);
            $realisation = 'R.A.S';
            $difficulte = 'R.A.S';
            if ($request->libelle !=''){
                $realisation = $request->libelle;
            }
            if ($request->difficulte !=''){
                $difficulte = $request->difficulte;
            }
            $realisation = AutreRealisation::create([
                'evaluation_id' => $evaluation->id,
                'realisation' => $realisation,
                'difficultes' => $difficulte
            ]);

            $realisation->save();
            $realisations = AutreRealisation::where('evaluation_id',$evaluation->id)->get();
            $tabRealisation = array();
            foreach ($realisations as $realisation){
                array_push($tabRealisation,[
                    'id'=>$realisation->id,
                    'libelle'=>$realisation->realisation,
                    'difficulte'=>$realisation->difficultes
                ]);
            }
            return GlobalResource::make($tabRealisation);

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement autre realisation mi-parcours',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addRealisationAmiParcours'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement de la réalisation à mi-parcours. Contactez IT']);

        }
    }

    public function removeRealisation($idRealiation,$idEvaluation){
        $realisation = AutreRealisation::find($idRealiation);
        $realisation->delete();
        $realisations = AutreRealisation::where('evaluation_id',$idEvaluation)->get();
        $tabRealisation = array();
        foreach ($realisations as $realisation){
            array_push($tabRealisation,[
                'id'=>$realisation->id,
                'libelle'=>$realisation->realisation,
                'difficulte'=>$realisation->difficultes
            ]);
        }
        return GlobalResource::make($tabRealisation);
    }

    public function clotureEvaluationAmiparcours(Request $request,$idEvaluation){
        $evaluation = Evaluation::find($idEvaluation);
        try {
            if ($request->type =='evaluer'){
                $evaluation->clotureEvaluer = true;
                if ($request->send){
                    $evaluation->transmis = true;
                }
                // Envoyer un mail au N+1 du collaborateur pour lui notifier que son collabo a finit son auto-évalaution
                $employe = Employe::find($evaluation->employe_id);
                $service = $employe->fonction->service;
                $req = "select empl.id,empl.nom,empl.prenoms,empl.email
                from employes empl
                inner join fonctions fonct on fonct.id=empl.fonction_id
                inner join services serv on serv.id=fonct.service_id
                where serv.id=$service->id
                and empl.respService = 1";
                $evaluateur = '';
                $emailEvaluateur = '';
                $dataEvaluateur = DB::select($req);
                if (count(DB::select($req)) > 0) {
                    $dataEvaluateur = $dataEvaluateur[0];
                    $emailEvaluateur = $dataEvaluateur->email;
                    $evaluateur = $dataEvaluateur->nom.' '.$dataEvaluateur->prenoms;
                }

                if ($emailEvaluateur!=''){
                    $datas = [
                        'title'=>' Salut cher '.$evaluateur.',',
                        'message'=>'Votre collaborateur '.$employe->nom .' '.$employe->prenoms.' a finit son évaluation évaluation à mis parcours',
                        'message1'=>'Veuillez vous connecté à votre espace pour réaliser un entretien avec ce dernier'
                    ];
                    $this->sendMailNotification([$emailEvaluateur],$datas);
                }
            }else{
                $responsable = Employe::where('email',$request->user()->email)->first();
                $evaluation->clotureResp = true;
                $evaluation->dateEntretien = (new \DateTime())->format('Y-m-d');
                $evaluation->evaluateur = $responsable->nom.' '.$responsable->prenoms;
            }
            $evaluation->save();
        }catch (\Exception $exception){

        }


        return GlobalResource::make($evaluation);
    }

    public function verifyEntretienMiparcours(Request $request,$idColab)
    {
        $typeEvalutionMiparcours = TypeEvaluation::where('code','EMP')->first();
        $campagne = CampagnePerformance::where('cloturer', false)
            ->where('type_evaluation_id',$typeEvalutionMiparcours->id)
            ->first();
        if ($campagne == '' or $campagne == null) {
            return GlobalResource::make(['code' => 403, 'message' => 'Aucune campagne d\'évaluation mi-parcours lancée par les RH']);
        }
        $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
        $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
        $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

        if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
        } else {
            return GlobalResource::make(['code' => 500, 'message' => 'La période définie pour la campagne des evaluations mi-parcours est expirée! Contactez les RH.']);
        }

        $evaluation = Evaluation::with('employe')->where('employe_id', $idColab)
            ->where('annee_id', $campagne->annee_id)
            ->where('type_evaluation_id', $typeEvalutionMiparcours->id)
            ->where('clotureEvaluer', true)->first();

        if ($evaluation == '' or $evaluation == null) {
            return GlobalResource::make(['code' => 500, 'message' => 'Evaluation du collaborateur n\'est pas encore disponible', 'evaluation' => $evaluation]);
        }

        if ($evaluation->clotureResp) {
            return GlobalResource::make(['code' => 500, 'message' => 'Entretien déjà éffectué avec le collaborateur pour cette campagne', 'evaluation' => $evaluation]);
        }

        if ($evaluation == '' or $evaluation == null) {
            return GlobalResource::make(['code' => 500, 'message' => 'Votre collaborateur n\'a pas encore effectué son évaluation.', 'evaluation' => $evaluation]);
        }

        if (!$evaluation->transmis) {
            return GlobalResource::make(['code' => 500, 'message' => 'Le collaborateur n\'a pas encore transmis l\'évaluation', 'evaluation' => $evaluation]);
        }

        return GlobalResource::make(['code' => 200, 'evaluation' => $evaluation]);
    }

    public function verifyEntretienAnnuel(Request $request,$idColab)
    {
        $typeEvalution = TypeEvaluation::where('code','EA')->first();
        $campagne = CampagnePerformance::where('cloturer', false)
            ->where('type_evaluation_id',$typeEvalution->id)
            ->first();
        if ($campagne == '' or $campagne == null) {
            return GlobalResource::make(['code' => 403, 'message' => 'Aucune campagne d\'évaluation annuelle lancée par les RH']);
        }
        $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
        $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
        $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

        if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
        } else {
            return GlobalResource::make(['code' => 500, 'message' => 'La période définie pour la campagne des evaluations annuelles est expirée! Contactez les RH.']);
        }

        $evaluation = Evaluation::with('employe')->where('employe_id', $idColab)
            ->where('annee_id', $campagne->annee_id)
            ->where('type_evaluation_id', $typeEvalution->id)
            ->where('clotureEvaluer', true)->first();

        if ($evaluation->clotureResp) {
            return GlobalResource::make(['code' => 500, 'message' => 'Entretien déjà éffectué avec le collaborateur pour cette campagne', 'evaluation' => $evaluation]);
        }

        if ($evaluation == '' or $evaluation == null) {
            return GlobalResource::make(['code' => 500, 'message' => 'Votre collaborateur n\'a pas encore effectué son évaluation.', 'evaluation' => $evaluation]);
        }

        if (!$evaluation->transmis) {
            return GlobalResource::make(['code' => 500, 'message' => 'Le collaborateur n\'a pas encore transmis l\'évaluation', 'evaluation' => $evaluation]);
        }

        return GlobalResource::make(['code' => 200, 'evaluation' => $evaluation]);
    }

    public function initEntretienMiparcours(Request $request,$idEvaluation){
        try {
            $typeEvalutionMiparcours = TypeEvaluation::where('code','EMP')->first();
            $campagne = CampagnePerformance::where('cloturer', false)
                ->where('type_evaluation_id',$typeEvalutionMiparcours->id)
                ->first();
            if ($campagne == '' or $campagne == null) {
                return GlobalResource::make(['code' => 403, 'message' => 'Aucune campagne d\'évaluation mi-parcours lancée par les RH']);
            }
            $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
            $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
            $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

            if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
            } else {
                return GlobalResource::make(['code' => 500, 'message' => 'La période définie pour la campagne des evaluations mi-parcours ou de saisir d\'objectif est expirée! Contactez les RH.']);
            }

            $evaluation = Evaluation::with('employe')->where('id', $idEvaluation)->where('annee_id', $campagne->annee_id)->where('clotureEvaluer', true)->first();

            if ($evaluation->clotureResp) {
                return GlobalResource::make(['code' => 500, 'message' => 'Entretien déjà éffectué avec le collaborateur sur cette évaluation', 'evaluation' => $evaluation]);
            }

            if ($evaluation == '' or $evaluation == null) {
                return GlobalResource::make(['code' => 500, 'message' => 'Votre collaborateur n\'a pas encore effectué son évaluation.Veuillez choisir un autre collaborateur.', 'evaluation' => $evaluation]);
            }

            if (!$evaluation->transmis) {
                return GlobalResource::make(['code' => 500, 'message' => 'Le collaborateur n\'a pas encore transmis l\'évaluation', 'evaluation' => $evaluation]);
            }

            $tabAccomplissement = explode(';', $evaluation->accomplissement);
            $tabDifficulte = explode(';', $evaluation->difficulteMission);
            $realisations = AutreRealisation::where('evaluation_id',$evaluation->id)->get();
            $tabRealisation = array();
            foreach ($realisations as $realisation){
                array_push($tabRealisation,[
                    'id'=>$realisation->id,
                    'libelle'=>$realisation->realisation,
                    'difficulte'=>$realisation->difficultes
                ]);
            }
            return GlobalResource::make([
                'code' => 200,
                'tabAccomplissement' => $tabAccomplissement,
                'tabDifficulte' => $tabDifficulte,
                'evaluation' => $evaluation,
                'realisations'=>$tabRealisation
            ]);
        }catch (\Exception $exception){
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec initialisation entretien-miparcours',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'initEntretienMiparcours'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            $evaluation = Evaluation::with('employe')->find($idEvaluation);

            return GlobalResource::make(['code' => 500,
                'evaluation'=>$evaluation,
                'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'initialisation de l\'entretien. Contactez IT']);

        }

    }

    public function init(Request $request, $idCollaborateur, $from = '')
    {
        try {
            $typeEvalutionMiparcours = TypeEvaluation::where('code','EA')->first();
            $campagne = CampagnePerformance::where('cloturer', false)
                ->where('type_evaluation_id',$typeEvalutionMiparcours->id)
                ->first();
            if ($campagne == '' or $campagne == null) {
                return GlobalResource::make(['code' => 403, 'message' => 'Aucune campagne d\'évaluation annuelle lancée par les RH']);
            }
            if ($campagne->type_evaluation->code!='EA'){
                return GlobalResource::make(['code' => 403, 'message' => 'Aucune campagne d\'évaluation annuelle lancée par les RH']);
            }
            $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
            $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
            $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

            if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
            } else {
                return GlobalResource::make(['code' => 403, 'message' => 'La période définie pour la campagne des performances est expirée! Contactez les RH.']);
            }
            if ($from == 'autoEvaluationSalarieByResp') {
                $employe = Employe::find($idCollaborateur);
            } else {
                $user = User::find($idCollaborateur);
                $employe = Employe::where('email', $user->email)->first();
            }

            $typeEvaluation = TypeEvaluation::where('code', 'EA')->first();
            if ($typeEvaluation == '' || $typeEvaluation == null) {
                return GlobalResource::make(['code' => 403, 'message' => 'Impossible de démarrer car le type d\'évaluation est introuvable.']);
            }
            $evaluation = Evaluation::where('employe_id', $employe->id)
                ->where('annee_id', $campagne->annee_id)
                ->where('type_evaluation_id', $typeEvaluation->id)
                ->get();

            if (count($evaluation) > 0) {
                $evaluation = $evaluation[0];
                if ($evaluation->transmis) {
                    return GlobalResource::make(['code' => 403, 'message' => 'Vous avez terminé déjà votre auto-évaluation et ce dernier est dans le processus de validation']);
                }
                $tabAccomplissement = explode(';', $evaluation->accomplissement);
                $tabDifficulte = explode(';', $evaluation->difficulteMission);
                $tabProgres = explode(';', $evaluation->progres);
                $formationSuivie = [];
                foreach ($evaluation->formation as $item) {
                    array_push($formationSuivie, [
                        'id' => $item->id,
                        'libelle' => $item->libelle,
                        'objectif' => $item->objectifVise,
                        'bilan' => $item->formationSuivie->appreciation,
                        'date' => (explode(' ', $item->formationSuivie->date))[0],
                    ]);
                }

                $data = $this->getCritereEvaluer($evaluation);
                $pointTotalByCatgorieCritere = $this->getTotalByCategorieCritere($evaluation);

                $anneeCampagne = Annee::find($campagne->annee_id);
                $date = $anneeCampagne->libelle + 1;
                $annee = Annee::where('libelle', $date)->first();
                $idAnnee = $annee->id;
                $tabObjectif = array();
                $objectifExistantPourLaCampagneEncours = Objectif::where('employe_id', $employe->id)->where('annee_id', $idAnnee)->get();
                foreach ($objectifExistantPourLaCampagneEncours as $encour) {
                    array_push($tabObjectif, array(
                        'libelle' => $encour->libelle,
                        'echeance' => $encour->echeance,
                        'resultats' => $encour->resultatAttendu,
                        'isjson' => $encour->isjson,
                        'actions' => $encour->actionCle,
                    ));
                }

                $besoinFormations = BesoinFormation::where('evaluation_id', $evaluation->id)->get();
                $tabBesoin = array();
                foreach ($besoinFormations as $besoin) {
                    array_push($tabBesoin, array(
                        'libelle' => $besoin->libelle,
                        'enonce' => $besoin->problemeEnonce,
                        'resultat' => $besoin->resultatAttendu,
                    ));
                }

                $req = "select * from objectifs where employe_id=$evaluation->employe_id and annee_id=$campagne->annee_id and appreFinal!='NULL' and appreFinal!=''";
                $objectifDejaApprecierNiveau = DB::select($req);

                $req = "select * from objectifs where employe_id=$evaluation->employe_id and annee_id=$campagne->annee_id and niveauExecFinal!='NULL' and niveauExecFinal!=''";
                $objectifDejaApprecierExecution = DB::select($req);

                $req = "select sum(objectifs.noteObtenue) as pointExecution from objectifs where employe_id=$evaluation->employe_id and annee_id=$campagne->annee_id and niveauExecFinal!='NULL' and niveauExecFinal!=''";
                $pointTotalObjectifExecute = DB::select($req);

                return GlobalResource::make([
                    'code' => 500, 'pointTotalByCatgorieCritere' => $pointTotalByCatgorieCritere,
                    'critereEvaluer' => $data, 'tabAccomplissement' => $tabAccomplissement,
                    'tabDifficulte' => $tabDifficulte, 'tabProgres' => $tabProgres,
                    'formationSuivie' => $formationSuivie, 'evaluation' => $evaluation,
                    'tabObjectif' => $tabObjectif, 'tabBesoin' => $tabBesoin, 'objectifDejaApprecierNiveau' => $objectifDejaApprecierNiveau,
                    'objectifDejaApprecierExecution' => $objectifDejaApprecierExecution, 'pointTotalObjectifExecute' => $pointTotalObjectifExecute[0]->pointExecution
                ]);

            }
            $missionsAnneeEncours = Mission::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->get();
            if (count($missionsAnneeEncours) == 0) {
                return GlobalResource::make(['code' => 402, 'annee_id'=>(int)$campagne->annee_id, 'message' => 'Impossible de démarrer ! Vos missions de cette année n\'existent pas dans la base. Veuillez reconduire celles de l\'an dernier']);
            }

            $objectifsValider = Objectif::where('employe_id', $employe->id)
                ->where('valider', true)
                ->where('ignorer', false)
                ->where('annee_id', $campagne->annee_id)
                ->orderBy('created_at', 'desc')
                ->get();

            if (count($objectifsValider) < 5) {
                return GlobalResource::make(['code' => 403, 'message' => 'Impossible de démarrer ! Vos objectifs de cette année ne sont pas validés ou n\'atteignent pas 5. Contactez votre responsable']);
            }

            $evaluation = Evaluation::create([
                'dateEvaluation' => (new \DateTime())->format('Y-d-m H:i:s'),
                'employe_id' => $employe->id,
                'annee_id' => $campagne->annee_id,
                'type_evaluation_id' => $typeEvaluation->id,
            ]);

            $validation = Validation::create([
                'employe_id' => $employe->id,
                'evaluation_id' => $evaluation->id,
            ]);
            $validation->save();

            return GlobalResource::make($evaluation);

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec initialisation auto-evaluation',
                'message' => $exception->getMessage() . ' à la ligne ' . $exception->getLine(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'init'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 403, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'initialisation de votre évaluation. Contactez IT']);
        }
    }

    public function verifyCampagne(Request $request, $idCollaborateur)
    {
        try {
            $user = User::find($idCollaborateur);
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            if ($campagne == null or $campagne == '') {
                return GlobalResource::make(['code' => 500, 'message' => 'Aucune campagne d\'évaluation n\'est lancée par les ressources humaines']);
            } else {
                $employe = Employe::where('email', $user->email)->first();
                $evaluation = Evaluation::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->get();
                $clotureEvaluation = false;
                $message = '';
                if (count($evaluation) > 0) {
                    $clotureEvaluation = $evaluation[0]->clotureEvaluer;
                    if ($clotureEvaluation) {
                        $message = 'Vous avez déjà terminé votre évaluation pour cette campagne ouverte.';
                    }
                }
                return GlobalResource::make(['code' => 200, 'campagne' => true, 'cloturePerformance' => $clotureEvaluation, 'message' => $message]);
            }

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec vérification campagne',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'verifyCampagne'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la vérification de la campagne. Contactez IT']);

        }

    }

    public function addFaireMarquant(Request $request, $idCollaborateur, $idEvaluation, $from = '')
    {
        try {
            /*if ($idEvaluation == 0) {
                if ($from == 'autoEvaluationSalarieByResp') {
                    $employe = Employe::find($idCollaborateur);
                } else {
                    $user = User::find($idCollaborateur);
                    $employe = Employe::where('email', $user->email)->first();
                }
                $campagne = CampagnePerformance::where('cloturer', false)->first();
                $evaluation = Evaluation::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->where('id',$idEvaluation)->first();
            } else {
                $evaluation = Evaluation::find($idEvaluation);
            }*/
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();

            if ($request->type == 'accomplissement') {
                $evaluation->accomplissement = $request->faireMarquand;
            }

            if ($request->type == 'difficulte') {
                $evaluation->difficulteMission = $request->faireMarquand;
            }

            if ($request->type == 'progres') {
                $evaluation->progres = $request->faireMarquand;
            }

            $evaluation->save();

            return GlobalResource::make($evaluation);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement fait marquant',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addFaireMarquant'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement de votre fait marquant. Contactez IT']);

        }

    }

    public function addFormationSuivie(Request $request, $idCollaborateur, $idEvaluation, $from)
    {
        try {
            /*if ($idEvaluation == 0) {
                if ($from == 'autoEvaluationSalarieByResp') {
                    $employe = Employe::find($idCollaborateur);
                } else {
                    $user = User::find($idCollaborateur);
                    $employe = Employe::where('email', $user->email)->first();
                }
                $campagne = CampagnePerformance::where('cloturer', false)->first();
                $evaluation = Evaluation::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->where('id',$idEvaluation)->first();

            } else {
                $evaluation = Evaluation::find($idEvaluation);
            }*/
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();

            $formation = [];
            if (!$request->autre) {
                $req = "select * from suivre where evaluation_id=$evaluation->id and formation_id=$request->id";
                $formationSuivie = DB::select($req);
                if (count($formationSuivie) > 0) {
                    $idformationSuivieExist = $formationSuivie[0]->id;
                    $date = (new \DateTime($request->date))->format('Y-d-m H:i:s');
                    $req = "update suivre set appreciation='$request->bilan',objectifVise='$request->objectif',date='$date' where id = $idformationSuivieExist";
                    DB::update($req);
                } else {
                    $evaluation->formation()->attach([$request->id => array('date' => (new \DateTime($request->date))->format('Y-d-m H:i:s'), 'appreciation' => $request->bilan, 'objectifVise' => $request->objectif, 'created_at' => (new \DateTime())->format('Y-d-m H:i:s'), 'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'))]);
                }
            } else {
                $formation = Formation::where('libelle',$request->libelle)->first();
                if ($formation == null && $formation =='') {
                    $formation = Formation::create([
                        'libelle' => $request->libelle,
                        'dateFormation' => $request->date,
                        'objectifVise' => $request->objectif,
                        'annee_id' => $campagne->annee_id,
                        'programmer' => true,
                        'type_formation_id' => $request->typeFormation,
                    ]);
                    $formation->save();
                }
                $req = "select * from formation_employe where employe_id= $evaluation->employe_id and formation_id=$formation->id";
                $formationEmploye = DB::select($req);
                if (count($formationEmploye) == 0) {
                    $formation->employes()->attach([$evaluation->employe_id => array('created_at' => (new \DateTime())->format('Y-d-m H:i:s'), 'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'))]);
                }

                $req = "select * from suivre where evaluation_id=$evaluation->id and formation_id=$formation->id";
                $formationSuivie = DB::select($req);
                if (count($formationSuivie) == 0) {
                    $evaluation->formation()->attach([$formation->id => array('date' => (new \DateTime($request->date))->format('Y-d-m H:i:s'), 'appreciation' => $request->bilan, 'objectifVise' => $request->objectif, 'created_at' => (new \DateTime())->format('Y-d-m H:i:s'), 'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'))]);
                }
            }

            return GlobalResource::make(['evaluation' => $evaluation, 'formation' => $formation]);

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement formation suivie',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addFormationSuivie'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement de vos formations suivies. Contactez IT']);

        }

    }

    public function removeFormationSuivie(Request $request, $idCollaborateur, $idEvaluation, $from = '')
    {
        try {
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();

            /*if ($idEvaluation == 0) {
                if ($from == 'autoEvaluationSalarieByResp') {
                    $employe = Employe::find($idCollaborateur);
                } else {
                    $user = User::find($idCollaborateur);
                    $employe = Employe::where('email', $user->email)->first();
                }
                $campagne = CampagnePerformance::where('cloturer', false)->first();
                $evaluation = Evaluation::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->where('id',$idEvaluation)->first();

            } else {
                $evaluation = Evaluation::find($idEvaluation);
            }*/

            $req = "delete from suivre  where formation_id=$request->idFormation and evaluation_id=$evaluation->id";
            DB::delete($req);
            /*$formation = Formation::find($request->idFormation);
            if ($formation->programmer) {
                $formation->employes()->detach();
                $formation->delete();
            }*/

            return GlobalResource::make($evaluation);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec suppression formation suivie',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'removeFormationSuivie'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la suppression de votre formation suivie. Contactez IT']);

        }

    }

    /*public function addAppreciationObjectifs(Request $request, $idCollaborateur)
    {
        try {
            $objectif = Objectif::find($request->id);
            if ($request->type == 'evaluer') {
                $objectif->apprEvaluer = $request->apprceiation;
                $objectif->appreFinal = $request->apprceiation;
            } else {
                if ($request->type != 'rh') {
                    $objectif->appreResp = $request->apprceiation;
                }

                $objectif->appreFinal = $request->apprceiation;
            }
            $objectif->save();

            return GlobalResource::make($objectif);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement appréciation objectif',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addAppreciationObjectifs'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'appréciation de votre objectif. Contactez IT']);

        }

    }*/

    public function addcommentaireObjectif(Request $request)
    {
        try {
            $objectif = Objectif::find($request->id);
            if ($request->type == 'evaluer') {
                $objectif->commentaireEvaluer = $request->commentaire;
            } else {
                if ($request->type != 'rh') {
                    $objectif->commentaireResp = $request->commentaire;
                }
                $objectif->commentaireFinal = $request->commentaire;
            }
            $objectif->save();
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement commentaire auto-evaluation',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addcommentaireObjectif'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement de votre commentaire. Contactez IT']);

        }


        return GlobalResource::make($objectif);
    }

    public function addNiveauObjectif(Request $request, $idCollaborateur, $idEvaluation, $from = '')
    {
        try {
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            /*if ($idEvaluation == 0) {
                if ($from == 'autoEvaluationSalarieByResp') {
                    $employe = Employe::find($idCollaborateur);
                } else {
                    $user = User::find($idCollaborateur);
                    $employe = Employe::where('email', $user->email)->first();
                }
                $evaluation = Evaluation::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->where('id',$idEvaluation)->first();
            } else {
                $evaluation = Evaluation::find($idEvaluation);
                $employe = Employe::find($evaluation->employe_id);
            }*/
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();
            $employe = Employe::find($evaluation->employe_id);

            $objectif = Objectif::find($request->idObjectif);
            $niveau = NiveauExecutionObjectif::find($request->idNiveau);
            $pointBonus = 0;

            /*if ($niveau->libelle == 'A plus de 100%') {
                $niveau100percent = NiveauExecutionObjectif::where('libelle', 'De 90 à 100%')->first();
                $pointBonus = 3;
            }*/

            if ($request->type == 'evaluer') {
                $objectif->niveauExecEvaluer = $niveau->libelle;
                $objectif->noteEvaluer = $niveau->point + $pointBonus;
                $objectif->niveauExecFinal = $niveau->libelle;
                $objectif->noteObtenue = $niveau->point + $pointBonus;
                //ajout de l'appréciation
                $objectif->apprEvaluer = $niveau->appreciation;
                $objectif->appreFinal = $niveau->appreciation;
            } else {
                if ($request->type != 'rh') {
                    $objectif->niveauExecResp = $niveau->libelle;
                    $objectif->noteResp = $niveau->point + $pointBonus;
                    //ajout de l'appréciation
                    $objectif->appreResp = $niveau->appreciation;
                }

                $objectif->niveauExecFinal = $niveau->libelle;
                $objectif->noteObtenue = $niveau->point + $pointBonus;
                //ajout de l'appréciation
                $objectif->appreFinal = $niveau->appreciation;
            }

            $objectif->save();

            $req = "select sum(noteObtenue) as pointTotal from objectifs where employe_id=$employe->id ";

            $pointTotal = DB::select($req);

            $performance = $this->getPerformanceEvaluation($evaluation, $campagne, $employe);
            $evaluation->performanceRealiser = number_format($performance['totalCompetence'] + $performance['totalPerformance'], 2);
            $evaluation->updated_at = (new \DateTime())->format('Y-d-m H:i:s');
            $evaluation->save();

            return GlobalResource::make(['code' => 200,'point' =>$pointTotal[0]->pointTotal, 'pointTotalCatgorieCritere' => $pointTotal[0]->pointTotal, 'pointTotalEvaluationCompetence' => $performance['totalCompetence']]);

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement de niveau objectif',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addNiveauObjectif'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement du niveau d\'exécution de votre objectifs. Contactez IT']);

        }

    }

    public function critereEvaluer(Request $request, $idCollaborateur, $idEvaluation, $from = '')
    {

        try {
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            /*if ($idEvaluation == 0) {
                if ($from == 'autoEvaluationSalarieByResp') {
                    $employe = Employe::find($idCollaborateur);
                } else {
                    $user = User::find($idCollaborateur);
                    $employe = Employe::where('email', $user->email)->first();
                }
                $evaluation = Evaluation::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->where('id',$idEvaluation)->first();
            } else {
                $evaluation = Evaluation::find($idEvaluation);
                $employe = Employe::find($evaluation->employe_id);
            }*/
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();
            $employe = Employe::find($evaluation->employe_id);

            $ponderation = PonderationCritere::find($request->idPonderation);

            $req = "select * from critere_evaluers where evaluation_id=$evaluation->id and critere_evaluation_id=$request->idCritere";
            $critereExist = DB::select($req);

            if ($request->type == 'evaluer') {
                if (count($critereExist) > 0) {
                    $idCritereExist = $critereExist[0]->id;
                    $req = "update critere_evaluers set apprEvaluer='$ponderation->libelle', apprFinal='$ponderation->libelle',pointEvaluer=$ponderation->point,pointFinal=$ponderation->point where id = $idCritereExist";
                    DB::update($req);
                } else {
                    $evaluation->critereEvaluer()->attach([$request->idCritere => array('apprEvaluer' => $ponderation->libelle, 'pointEvaluer' => $ponderation->point, 'apprFinal' => $ponderation->libelle, 'pointFinal' => $ponderation->point, 'created_at' => (new \DateTime())->format('Y-d-m H:i:s'), 'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'))]);
                }
            } else {
                if (count($critereExist) > 0) {
                    $idCritereExist = $critereExist[0]->id;
                    $req = "update critere_evaluers set apprResp='$ponderation->libelle',pointResp=$ponderation->point,apprFinal='$ponderation->libelle',pointFinal=$ponderation->point where id = $idCritereExist";
                    if ($request->type == 'rh') {
                        $req = "update critere_evaluers set apprFinal='$ponderation->libelle',pointFinal=$ponderation->point where id = $idCritereExist";
                    }
                    DB::update($req);
                } else {
                    $evaluation->critereEvaluer()->attach([$request->idCritere => array('apprResp' => $ponderation->libelle, 'pointResp' => $ponderation->point, 'apprFinal' => $ponderation->libelle, 'pointFinal' => $ponderation->point, 'created_at' => (new \DateTime())->format('Y-d-m H:i:s'), 'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'))]);
                }
            }
            $cateEmploye = $employe->categorie_professionnelle->categorie;
            if ($cateEmploye->code == 'AE' or $cateEmploye->code == 'E') {
                $categorieprof = CategorieProfessionnelle::where('code', 'M1')->first();
                $cateEmploye = $categorieprof->categorie->id;
            }else
                $cateEmploye = $cateEmploye->id;

            $req1 = "select count(cateCrit.code) as nbrCritere from critere_evaluations critEval
                     inner join categorie_criteres cateCrit
                     on cateCrit.id = critEval.categorie_critere_id
                     where cateCrit.code='$request->codeCategorie'
                     and critEval.categorie_employe_id='$cateEmploye'";

            $nbrCritereByCateg = DB::select($req1);

            $req = "select sum(critEvaluer.pointFinal) as pointTotal from critere_evaluers critEvaluer
                inner join critere_evaluations critEval
                on critEval.id = critEvaluer.critere_evaluation_id
                inner join categorie_criteres cateCrit
                on cateCrit.id = critEval.categorie_critere_id
                inner join evaluations eval
                on eval.id = critEvaluer.evaluation_id
                inner join employes empl
                on empl.id = eval.employe_id
                where cateCrit.code='$request->codeCategorie'
                and empl.id = '$evaluation->employe_id'
                group by cateCrit.code";

            $pointTotalCatgorie = DB::select($req);

            $pointTotalCatgorieCritere = $pointTotalCatgorie[0]->pointTotal / $nbrCritereByCateg[0]->nbrCritere;
            $performance = $this->getPerformanceEvaluation($evaluation, $campagne, $employe);
            $evaluation->performanceRealiser = number_format($performance['totalCompetence'] + $performance['totalPerformance'], 2);
            $evaluation->updated_at = (new \DateTime())->format('Y-d-m H:i:s');
            $evaluation->save();

            return GlobalResource::make(['pointTotalCatgorieCritere' => $pointTotalCatgorieCritere, 'pointTotalEvaluationCompetence' => $performance['totalCompetence']]);

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement critere evaluation',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'critereEvaluer'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement du critère. Contactez IT']);

        }
    }

    public function addSouhaitPreoccupation(Request $request, $idCollaborateur, $idEvaluation, $from = '')
    {
        try {
            /*if ($idEvaluation == 0) {
                if ($from == 'autoEvaluationSalarieByResp') {
                    $employe = Employe::find($idCollaborateur);
                } else {
                    $user = User::find($idCollaborateur);
                    $employe = Employe::where('email', $user->email)->first();
                }
                $campagne = CampagnePerformance::where('cloturer', false)->first();
                $evaluation = Evaluation::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->where('id',$idEvaluation)->first();
            } else {
                $evaluation = Evaluation::find($idEvaluation);
            }*/
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();

            $evaluation->updated_at = (new \DateTime())->format('Y-d-m H:i:s');
            if ($request->type == 'avenir' && $request->avenirProf != 'null' && $request->avenirProf != '' && $request->avenirProf != null) {
                $evaluation->AvenirProfs = $request->avenirProf;
            }

            if ($request->type == 'mission' && $request->convenanceMission != 'null' && $request->convenanceMission != '' && $request->convenanceMission != null) {
                $evaluation->convenanceMission = $request->convenanceMission;
            }

            if ($request->type == 'difficulte' && $request->difficulteGlobal != 'null' && $request->difficulteGlobal != '' && $request->difficulteGlobal != null) {
                $evaluation->difficulteGblobal = $request->difficulteGlobal;
            }

            if ($request->type == 'supCompetence' && $request->superCompetence != 'null' && $request->superCompetence != '' && $request->superCompetence != null) {
                $evaluation->superCompetence = $request->superCompetence;
            }

            $evaluation->save();

            return GlobalResource::make($evaluation);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement souhait salarié',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addSouhaitPreoccupation'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement de vos souhaits. Contactez IT']);

        }

    }

    public function setBesoinFormationCollaborateur(Request $request, $idCollaborateur, $idEvaluation, $from = '')
    {
        try {
            /* if ($idEvaluation == 0) {
                 if ($from == 'autoEvaluationSalarieByResp') {
                     $employe = Employe::find($idCollaborateur);
                 } else {
                     $user = User::find($idCollaborateur);
                     $employe = Employe::where('email', $user->email)->first();
                 }*/

            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();

            /*} else {
            $evaluation = Evaluation::find($idEvaluation);
        }*/

            $besoinFormation = BesoinFormation::create([
                'evaluation_id' => $evaluation->id,
                'libelle' => $request->libelle,
                'problemeEnonce' => $request->enonce,
                'resultatAttendu' => $request->resultat,
            ]);

            $besoinFormation->save();

            return GlobalResource::make($besoinFormation);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement besoisn de formation',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'setBesoinFormationCollaborateur'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement besoisn de formation. Contactez IT']);

        }

    }

    public function addCommentaireEvaluation(Request $request, $idCollaborateur, $idEvaluation, $from = '')
    {
        try {
            /*if ($idEvaluation == 0) {
                if ($from == 'autoEvaluationSalarieByResp') {
                    $employe = Employe::find($idCollaborateur);
                } else {
                    $user = User::find($idCollaborateur);
                    $employe = Employe::where('email', $user->email)->first();
                }
                $campagne = CampagnePerformance::where('cloturer', false)->first();
                $evaluation = Evaluation::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->where('id',$idEvaluation)->first();
            } else {
                $evaluation = Evaluation::find($idEvaluation);
            }*/
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();

            $evaluation->updated_at = (new \DateTime())->format('Y-d-m H:i:s');

            if ($request->type == 'evaluer' && $request->commentaire != '' && $request->commentaire != 'null') {
                $evaluation->commentaireEvaluer = $request->commentaire;
            }

            if ($request->type == 'evaluateur' && $request->commentaire != '' && $request->commentaire != 'null') {
                if ($evaluation->commentaireResp == '') {
                    $evaluation->commentaireResp = $request->commentaire;
                }
                $responsable = Employe::where('email', $request->user()->email)->first();
                $profile = $this->getProfile($request, $responsable);

                $commentaire = Commentaire::create([
                    'libelle' => $request->commentaire,
                    'bonus' => $request->bonus,
                    'evaluation_id' => $evaluation->id,
                    'employe_id' => $responsable->id,
                    'profile' => $profile
                ]);
                $commentaire->save();
            }

            $evaluation->save();
            $commentaires = Commentaire::where('evaluation_id', $evaluation->id)->get();

            return GlobalResource::make(['code' => 200, 'message' => 'Commentaire ajouté avec succès.', 'commentaires' => $commentaires]);

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec ajout commentaire',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addCommentaireEvaluation'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'ajout de commentaire. Contactez IT']);

        }

    }

    public function deleteCommentaireEvaluation(Request $request, $idCommentaire)
    {
        try {

            $commentaire = Commentaire::find($idCommentaire);
            $idEvaluation = $commentaire->evaluation_id;
            $commentaire->delete();
            $commentaires = Commentaire::where('evaluation_id', $idEvaluation)->get();

            return GlobalResource::make(['code' => 200, 'message' => 'Commentaire annulé avec succès.', 'commentaires' => $commentaires]);

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec ajout commentaire',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'addCommentaireEvaluation'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'ajout de commentaire. Contactez IT']);

        }

    }

    public function getEvaluationCollaborateur($idCollaborateur)
    {
        $typeEva = TypeEvaluation::where('code','EA')->first();
        $req = "select eval.id,eval.accomplissement,eval.difficulteMission,eval.progres,eval.dateEvaluation,eval.clotureCodi,eval.clotureResp,
                annees.libelle as annee,typeEv.code as typeEva
                from evaluations eval
                inner join annees on annees.id = eval.annee_id
                inner join type_evaluations typeEv on typeEv.id = eval.type_evaluation_id
                where eval.employe_id = $idCollaborateur
                and eval.transmis =1
                and eval.type_evaluation_id =$typeEva->id
                order by eval.id desc";

        return GlobalResource::make(DB::select($req));
//      return GlobalResource::collection(Evaluation::with('annee')->where('employe_id', $idCollaborateur)->where('transmis', true)->orderBy('created_at', 'DESC')->get());
    }

    public function initEntretienEvaluation(Request $request, $idEvaluation)
    {
        try {
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
            $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
            $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

            if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
            } else {
                return GlobalResource::make(['code' => 500, 'message' => 'La période définie pour la campagne des performances ou de saisir d\'objectif est expirée! Contactez les RH.']);
            }

            $evaluation = Evaluation::with('employe')->where('id', $idEvaluation)->where('annee_id', $campagne->annee_id)->where('clotureEvaluer', true)->first();

            /*if ($evaluation->clotureResp) {
                return GlobalResource::make(['code' => 500, 'message' => 'Entretien déjà éffectué avec le collaborateur sur cette évaluation', 'evaluation' => $evaluation]);
            }*/

            if ($evaluation == '' or $evaluation == null) {
                return GlobalResource::make(['code' => 500, 'message' => 'Votre collaborateur n\'a pas encore effectué son évaluation.Veuillez choisir un autre collaborateur.', 'evaluation' => $evaluation]);
            }

            if (!$evaluation->transmis) {
                return GlobalResource::make(['code' => 500, 'message' => 'Le collaborateur n\'a pas encore transmis l\'évaluation', 'evaluation' => $evaluation]);
            }

            $employe = Employe::find($evaluation->employe_id);
            $responsable = Employe::where('email', $request->user()->email)->first();
            if ($this->checkStatusEvaluation($evaluation, $responsable)) {
                return GlobalResource::make(['code' => 500, 'message' => 'Entretien déjà éffectué avec le collaborateur sur cette évaluation', 'evaluation' => $evaluation]);
            }

            $tabAccomplissement = explode(';', $evaluation->accomplissement);
            $tabDifficulte = explode(';', $evaluation->difficulteMission);
            $tabProgres = explode(';', $evaluation->progres);
            $formationSuivie = [];
            foreach ($evaluation->formation as $item) {
                array_push($formationSuivie, [
                    'id' => $item->id,
                    'libelle' => $item->libelle,
                    'objectif' => $item->objectifVise,
                    'type_formation_id' => $item->type_formation_id,
                    'bilan' => $item->formationSuivie->appreciation,
                    'date' => (explode(' ', $item->formationSuivie->date))[0],
                ]);
            }
            $data = $this->getCritereEvaluer($evaluation);

            $pointTotalByCatgorieCritere = $this->getTotalByCategorieCritere($evaluation);

            $anneeCampagne = Annee::find($campagne->annee_id);
            //$date = $anneeCampagne->libelle + 1;
            $date = $anneeCampagne->libelle;
            $annee = Annee::where('libelle', $date)->first();
            $idAnnee = $annee->id;
            $tabObjectif = array();
            $objectifExistantPourLaCampagneEncours = Objectif::where('employe_id', $employe->id)->where('annee_id', $idAnnee)->get();
            foreach ($objectifExistantPourLaCampagneEncours as $encour) {
                array_push($tabObjectif, array(
                    'id' => $encour->id,
                    'libelle' => $encour->libelle,
                    'echeance' => $encour->echeance,
                    'commentaireResp' => $encour->commentaireResp,
                    'commentaireEvaluer' => $encour->commentaireEvaluer,
                    'resultat' => $encour->resultatAttendu,
                    'actions' => $encour->actionCle,
                    'niveauExecFinal' => $encour->niveauExecFinal,
                ));
            }
            //liste des objectifs pour la nouvelle année
            $date = $anneeCampagne->libelle + 1;
            $annee = Annee::where('libelle', $date)->first();
            $idAnnee = $annee->id;
            $tabObjectifNouvel = array();
            $objectifNouvels = Objectif::where('employe_id', $employe->id)->where('annee_id', $idAnnee)->get();
            foreach ($objectifNouvels as $encour) {
                array_push($tabObjectifNouvel, array(
                    'libelle' => $encour->libelle,
                    'echeance' => $encour->echeance,
                    'resultats' => $encour->resultatAttendu,
                    'isjson' => $encour->isjson,
                    'actions' => $encour->actionCle,
                ));
            }

            $besoinFormations = BesoinFormation::where('evaluation_id', $evaluation->id)->get();
            $tabBesoin = array();
            foreach ($besoinFormations as $besoin) {
                array_push($tabBesoin, array(
                    'libelle' => $besoin->libelle,
                    'enonce' => $besoin->problemeEnonce,
                    'resultat' => $besoin->resultatAttendu,
                ));
            }

            $req = "select * from objectifs where employe_id=$evaluation->employe_id and appreFinal!='NULL' and appreFinal!=''";
            $objectifDejaApprecierNiveau = DB::select($req);

            $req = "select * from objectifs where employe_id=$evaluation->employe_id and niveauExecFinal!='NULL' and niveauExecFinal!=''";
            $objectifDejaApprecierExecution = DB::select($req);

            $req = "select sum(objectifs.noteObtenue) as pointExecution from objectifs where employe_id=$evaluation->employe_id and niveauExecFinal!='NULL' and niveauExecFinal!=''";
            $pointTotalObjectifExecute = DB::select($req);
            $commentaires = DB::select("
                select *
                from commentaires
                where evaluation_id = ?
                and libelle like 'REJET%'
            ",[$evaluation->id]);
            return GlobalResource::make([
                'code' => 200, 'pointTotalByCatgorieCritere' => $pointTotalByCatgorieCritere,
                'critereEvaluer' => $data, 'tabAccomplissement' => $tabAccomplissement,
                'tabDifficulte' => $tabDifficulte, 'tabProgres' => $tabProgres,
                'formationSuivie' => $formationSuivie, 'evaluation' => $evaluation,
                'tabObjectif' => $tabObjectif, 'tabObjectifNouvel' => $tabObjectifNouvel, 'tabBesoin' => $tabBesoin, 'objectifDejaApprecierNiveau' => $objectifDejaApprecierNiveau,
                'objectifDejaApprecierExecution' => $objectifDejaApprecierExecution, 'pointTotalObjectifExecute' => $pointTotalObjectifExecute[0]->pointExecution,
                'commentaires'=>$commentaires
            ]);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec initialisation entretien-evaluation',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'init'
            ];
            $this->mouchard->saveLogAction($tabInfo);
            $evaluation = Evaluation::with('employe')->find($idEvaluation);

            return GlobalResource::make(['code' => 500,
                'evaluation'=>$evaluation,
                'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'initialisation de l\'entretien. Contactez IT']);

        }

    }

    public function destroyBesoinFormationByLibelle(Request $request, $idCollaborateur, $idEvaluation, $from = '')
    {
        try {
            /*if ($idEvaluation == 0) {
                if ($from == 'autoEvaluationSalarieByResp') {
                    $employe = Employe::find($idCollaborateur);
                } else {
                    $user = User::find($idCollaborateur);
                    $employe = Employe::where('email', $user->email)->first();
                }
                $campagne = CampagnePerformance::where('cloturer', false)->first();
                $evaluation = Evaluation::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->where('id',$idEvaluation)->first();
            } else {
                $evaluation = Evaluation::find($idEvaluation);
            }*/
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();

            $besoin = BesoinFormation::where('libelle', $request->libelle)->where('evaluation_id', $evaluation->id)->first();
            $besoin->delete();
            return response()->noContent();
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec initialisation entretien-evaluation',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'init'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'initialisation de l\'entretien. Contactez IT']);

        }

    }

    public function validationNouvelleObjectifs(Request $request, $idEvaluation)
    {
        try {
            $evaluation = Evaluation::find($idEvaluation);
            $employe = Employe::find($evaluation->employe_id);
            $objectif = Objectif::where('libelle', $request->libelle)->where('employe_id', $employe->id)->first();
            $objectif->valider = true;
            $objectif->save();

            $tabObjectifNouvel = array();
            $objectifNouvel = Objectif::where('employe_id', $employe->id)->where('annee_id', $objectif->annee_id)->get();
            foreach ($objectifNouvel as $encour) {
                array_push($tabObjectifNouvel, array(
                    'id' => $encour->id,
                    'libelle' => $encour->libelle,
                    'echeance' => $encour->echeance,
                    'resultat' => $encour->resultatAttendu,
                    'actions' => $encour->actionCle,
                    'valider' => $encour->valider,
                ));
            }

            return GlobalResource::make(['code' => 200, 'objectifNouvel' => $tabObjectifNouvel]);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec validation objectif',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'validationNouvelleObjectifs'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la validation de l\'objectifs. Contactez IT']);

        }

    }

    public function clotureEvaluation(Request $request, $idCollaborateur, $idEvaluation, $from = '')
    {
        try {
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            if ($campagne == '' or $campagne == null) {
                return GlobalResource::make(['code' => 500, 'message' => 'Aucune campagne d\'évaluation lancée par les RH']);
            }

            $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
            $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
            $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

            if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
            } else {
                return GlobalResource::make(['code' => 500, 'message' => 'La période définie pour la campagne des performances ou de saisir d\'objectif est expirée! Contactez les RH.']);
            }
            /*if ($idEvaluation == 0) {
                if ($from == 'autoEvaluationSalarieByResp') {
                    $employe = Employe::find($idCollaborateur);
                } else {
                    $user = User::find($idCollaborateur);
                    $employe = Employe::where('email', $user->email)->first();
                }
                $evaluation = Evaluation::where('employe_id', $employe->id)->where('annee_id', $campagne->annee_id)->where('id',$idEvaluation)->first();
            } else {
                $evaluation = Evaluation::find($idEvaluation);
                $employe = Employe::find($evaluation->employe_id);
            }*/
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            $evaluation = Evaluation::where('annee_id', $campagne->annee_id)->where('id', $idEvaluation)->first();
            $employe = Employe::find($evaluation->employe_id);

            if ($request->type == 'evaluer') {
                $evaluation->clotureEvaluer = true;
                if ($request->send){
                    $evaluation->transmis = true;
                }
                if ($employe->respUnite) {
                    $validation = Validation::where('evaluation_id', $evaluation->id)->first();
                    $validation->niveau1 = true;
                    $validation->save();
                } elseif ($employe->respService) {
                    $validation = Validation::where('evaluation_id', $evaluation->id)->first();
                    $validation->niveau1 = true;
                    $validation->niveau2 = true;
                    if ($employe->fonction->service->direction->code != 'DU' ) {
                        $validation->niveau3 = true;
                    }else{
                        if ($employe->fonction->service->departement->code =='DFLT'){
                            $validation->niveau3 = true;
                        }
                    }
                    $validation->save();
                } elseif ($employe->respDepartement) {
                    $validation = Validation::where('evaluation_id', $evaluation->id)->first();
                    $validation->niveau1 = true;
                    $validation->niveau2 = true;
                    $validation->niveau3 = true;
                    $validation->save();
                } else {
                    $req = "select * from employes
                            where employes.respUnite =1
                            and employes.unite_id=$employe->unite_id";
                    $data = DB::select($req);
                    if (count($data) == 0) {
                        $validation = Validation::where('evaluation_id', $evaluation->id)->first();
                        $validation->niveau1 = true;
                        $validation->save();
                    }
                }
                $employe = Employe::find($evaluation->employe_id);
                $service = $employe->fonction->service;
                $req = "select empl.id,empl.nom,empl.prenoms,empl.email
                from employes empl
                inner join fonctions fonct on fonct.id=empl.fonction_id
                inner join services serv on serv.id=fonct.service_id
                where serv.id=$service->id
                and empl.respService = 1";
                $evaluateur = '';
                $emailEvaluateur = '';
                $dataEvaluateur = DB::select($req);
                if (count(DB::select($req)) > 0) {
                    $dataEvaluateur = $dataEvaluateur[0];
                    $emailEvaluateur = $dataEvaluateur->email;
                    $evaluateur = $dataEvaluateur->nom.' '.$dataEvaluateur->prenoms;
                }

                if ($emailEvaluateur!='' && $from != 'autoEvaluationSalarieByResp'){
                    $datas = [
                        'title'=>' Mr '.$evaluateur.',',
                        'message'=>'Votre collaborateur '.$employe->nom .' '.$employe->prenoms.' a finit son auto évaluation',
                        'message1'=>'Veuillez vous connecté à votre espace pour réaliser un entretien avec ce dernier'
                    ];
                    $this->sendMailNotification([$emailEvaluateur],$datas);
                }

            }

            if ($request->type == 'evaluateur') {
                $responsable = Employe::where('email', $request->user()->email)->first();
                if ($evaluation->evaluateur == '') {
                    $evaluation->evaluateur = $responsable->nom . ' ' . $responsable->prenoms;
                    $evaluation->dateEntretien = (new \DateTime())->format('Y-m-d H:i:s');
                }
                //$evaluation->clotureResp = true;
                if ($responsable->respUnite) {
                    $validation = Validation::where('evaluation_id', $evaluation->id)->first();
                    $validation->niveau1 = true;
                    $validation->save();
                } elseif ($responsable->respService) {
                    $validation = Validation::where('evaluation_id', $evaluation->id)->first();
                    if (!$validation->niveau1) {
                        $validation->niveau1 = true;
                    }
                    $validation->niveau2 = true;

                    if ($responsable->fonction->service->direction->code != 'DU') {
                        $validation->niveau3 = true;
                    }else{
                        if ($employe->fonction->service->departement->code =='DFLT'){
                            $validation->niveau3 = true;
                        }
                    }
                    $validation->save();
                } elseif ($responsable->respDepartement) {
                    $validation = Validation::where('evaluation_id', $evaluation->id)->first();
                    $validation->niveau3 = true;
                    $validation->save();
                } elseif ($responsable->isDirecteur) {
                    $validation = Validation::where('evaluation_id', $evaluation->id)->first();
                    $validation->niveau3 = true;
                    $validation->niveau4 = true;
                    $validation->save();
                    $evaluation->clotureResp = 1;
                }

                if ($request->commentaireUnitaire != '') {
                    $responsable = Employe::where('email', $request->user()->email)->first();
                    $profile = $this->getProfile($request, $responsable);

                    $commentaire = Commentaire::create([
                        'libelle' => $request->commentaireUnitaire,
                        'bonus' => $request->bonusUnitaire,
                        'evaluation_id' => $evaluation->id,
                        'employe_id' => $responsable->id,
                        'profile' => $profile
                    ]);
                    $commentaire->save();

                }

            }

            $performance = $this->getPerformanceEvaluation($evaluation, $campagne, $employe);
            $evaluation->performanceRealiser = number_format($performance['totalPerformance'] + $performance['totalCompetence'], 2);
            $sommeBonus = $this->getBonusEvaluation($evaluation);

            $evaluation->performanceFinal = $evaluation->performanceRealiser + $sommeBonus;
            $evaluation->updated_at = (new \DateTime())->format('Y-m-d H:i:s');

            if ($from == 'autoEvaluationSalarieByResp') {
                $evaluation->transmis = true;
            }

            $evaluation->save();

            return GlobalResource::make(['code' => 200, 'message' => 'success']);

        } catch (\Exception $exception) {
            dd($exception->getMessage().' '.$exception->getLine());
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec validation validation',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'clotureEvaluation'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la validation. Contactez IT']);

        }

    }

    public function validationPerformance(Request $request)
    {
        if ($request->rubrique == "") {
            return GlobalResource::make(['code' => 500, 'message' => "Veuillez choisir une rubrique valide"]);
        }

        if ($request->collaborateur == "") {
            return GlobalResource::make(['code' => 500, 'message' => "Veuillez choisir un collaborateur"]);
        }

        if ($request->annee == "") {
            return GlobalResource::make(['code' => 500, 'message' => "Veuillez choisir une année"]);
        }

        $evaluation = Evaluation::where("employe_id", $request->collaborateur)->where('annee_id', $request->annee)->latest()->first();
        $evaluation->performanceRealiser = round($evaluation->performanceRealiser,2);

        $campagne = CampagnePerformance::where('annee_id', $request->annee)->first();

        if ($campagne == '' or $campagne == null) {
            return GlobalResource::make(['code' => 500, 'message' => "Aucune campagne d'évaluation n'est lancée par les rh pour l'année choisie"]);
        }

        if ($evaluation == '' or $evaluation == null) {
            return GlobalResource::make(['code' => 500, 'message' => "Aucune evaluation retrouvée pour le collaborateur choisir"]);
        }

        if ($evaluation->clotureResp) {
            return GlobalResource::make(['code' => 500, 'message' => "L'évaluation de ce dernier est déjà validée et est en cours de dernière validation par le comité"]);
        }

        $employe = Employe::find($evaluation->employe_id);
        $responsable = Employe::where('email', $request->user()->email)->first();
        if (count($this->verifyEvaluationValider($request, $employe, $evaluation, $responsable)) == 0) {
            $message = 'Evaluation non validée par le responsable';
            if ($responsable->respService)
                $message .= ' de l\'unité';
            elseif ($responsable->respDepartement)
                $message .= ' service';
            elseif ($responsable->isDirecteur)
                $message .= ' du pôle';
            else
                $message .= ' hiérachique';

            return GlobalResource::make(['code' => 500, 'message' => $message]);
        }

        $missions = [];
        if ($request->rubrique == 'mission') {
            $missions = Mission::where("employe_id", $request->collaborateur)->get();
        }

        $bilans = [];
        if ($request->rubrique == 'bilan') {
            $bilans['accomplissement'] = explode(";", $evaluation->accomplissement);
            $bilans['progres'] = explode(";", $evaluation->progres);
            $bilans['difficultes'] = explode(";", $evaluation->difficulteMission);
        }
        $formationSuivie = [];
        if ($request->rubrique == 'formationSuivie') {
            foreach ($evaluation->formation as $item) {
                array_push($formationSuivie, [
                    'id' => $item->id,
                    'libelle' => $item->libelle,
                    'objectif' => $item->objectifVise,
                    'bilan' => $item->formationSuivie->appreciation,
                    'date' => (explode(' ', $item->formationSuivie->date))[0],
                ]);
            }
        }

        $objectifDejaApprecierNiveau = [];
        $objectifDejaApprecierExecution = [];
        $pointTotalObjectifExecute = [];
        $objetifs = [];
        if ($request->rubrique == 'EvalPerformance') {
            $req = "select * from objectifs where employe_id=$evaluation->employe_id and appreFinal!='NULL' and appreFinal!=''";
            $objectifDejaApprecierNiveau = DB::select($req);

            $req = "select * from objectifs where employe_id=$evaluation->employe_id and niveauExecFinal!='NULL' and niveauExecFinal!=''";
            $objectifDejaApprecierExecution = DB::select($req);

            $req = "select sum(objectifs.noteObtenue) as pointExecution from objectifs where employe_id=$evaluation->employe_id and niveauExecFinal!='NULL' and niveauExecFinal!=''";
            $pointTotalObjectifExecute = DB::select($req);
            if (count($pointTotalObjectifExecute) > 0)
                $pointTotalObjectifExecute = $pointTotalObjectifExecute[0]->pointExecution;
            else
                $pointTotalObjectifExecute = 0;

            $objetifs = Objectif::where('employe_id', $request->collaborateur)->where('valider', true)->where('ignorer', false)->where('annee_id', $request->annee)->orderBy('created_at', 'desc')->get();

        }

        $critereEvaluer = [];
        $totalByCatgorieCritere = [];
        if ($request->rubrique == 'EvalCompetence') {
            $critereEvaluer = $this->getCritereEvaluer($evaluation);
            $totalByCatgorieCritere = $this->getTotalByCategorieCritere($evaluation);
        }

        $preocupation = [];
        if ($request->rubrique == 'preocupation') {
            $preocupation['avenirProf'] = $evaluation->AvenirProfs;
            $preocupation['convenanceMission'] = $evaluation->convenanceMission;
            $preocupation['difficulteGblobal'] = $evaluation->difficulteGblobal;
            $preocupation['superCompetence'] = $evaluation->superCompetence;
        }

        $tabObjectifNouvel = array();
        if ($request->rubrique == 'nouvelObjectif') {
            $annee = Annee::find($request->annee);
            $date = $annee->libelle + 1;
            $annee = Annee::where('libelle', $date)->first();
            $idAnnee = $annee->id;

            $objectifNouvel = Objectif::where('employe_id', $request->collaborateur)->where('annee_id', $idAnnee)->get();
            foreach ($objectifNouvel as $encour) {
                array_push($tabObjectifNouvel, array(
                    'id' => $encour->id,
                    'libelle' => $encour->libelle,
                    'echeance' => $encour->echeance,
                    'resultat' => $encour->resultatAttendu,
                    'actions' => $encour->actionCle,
                    'valider' => $encour->valider,
                ));
            }
        }
        $tabBesoin = array();
        if ($request->rubrique == 'developCompetence') {
            $besoinFormations = BesoinFormation::where('evaluation_id', $evaluation->id)->get();

            foreach ($besoinFormations as $besoin) {
                array_push($tabBesoin, array(
                    'libelle' => $besoin->libelle,
                    'enonce' => $besoin->problemeEnonce,
                    'resultat' => $besoin->resultatAttendu,
                ));
            }
        }

        $commentaires = [];
        if ($request->rubrique == 'commentaire') {
            $commentaires = Commentaire::where('evaluation_id', $evaluation->id)->get();
        }

        $performance = $this->getPerformanceEvaluation($evaluation, $campagne, $employe);

        return GlobalResource::make([
            'code' => 200,
            'missions' => $missions,
            'bilans' => $bilans,
            'formationSuivie' => $formationSuivie,
            'objectifs' => $objetifs,
            'pointTotalObjectifExecute' => $pointTotalObjectifExecute,
            'objectifDejaApprecierExecution' => $objectifDejaApprecierExecution,
            'objectifDejaApprecierNiveau' => $objectifDejaApprecierNiveau,
            'pointTotalByCatgorieCritere' => $totalByCatgorieCritere,
            'preocupation' => $preocupation,
            'tabObjectifNouvel' => $tabObjectifNouvel,
            'developpementCompt' => $tabBesoin,
            'evaluation' => $evaluation,
            'performance' => $performance,
            'critereEvaluer' => $critereEvaluer,
            'commentaires' => $commentaires,
        ]);

    }

    public function sendEvaluationToChief(Request $request, $idEvaluation)
    {
        try {
            $evaluation = Evaluation::find($idEvaluation);
            $evaluation->transmis = true;
            $evaluation->save();
            return GlobalResource::make(['code' => 200, 'message' => 'Votre évaluation est transmise au responsable avec succès.']);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec de transmision de évalaution',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'sendEvaluationToChief'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la transmission de votre évaluation. Contactez IT']);
        }
    }

    public function checkMissionIsvalidate($id, $idEvaluation)
    {
        $evaluation = Evaluation::find($idEvaluation);
        $employe = Employe::find($evaluation->employe_id);
        if (count(Mission::where('employe_id', $employe->id)->where('valider', true)->where('annee_id', $evaluation->annee_id)->get()) == 0) {
            return GlobalResource::make(['code' => 500, 'message' => 'Impossible de télécharger le rapport! Vos missions de cette année ne sont pas validée. Contactez votre responsable']);
        } else {
            return GlobalResource::make(['code' => 200]);
        }
    }

    public function rapportEvaluation(Request $request, $idEvaluation, $display = false)
    {
        $evaluation = Evaluation::find($idEvaluation);
        $campagne = CampagnePerformance::where('annee_id', $evaluation->annee_id)->first();
        $employe = Employe::with('categorie_professionnelle')->find($evaluation->employe_id);
        $curentDate = date_create(date('Y/m/d'));
        $dateEmbauche = date_create($employe->dateEmbauche);
        $anciennete = date_diff($curentDate, $dateEmbauche);
        $year = $anciennete->y;
        $month = $anciennete->m;
        $days = $anciennete->d;
        $anciennete = '';
        if ($year != 0) {
            $anciennete .= $year . ' an(s)';
        }

        if ($month != 0) {
            $anciennete .= ' ' . $month . ' mois';
        }

        if ($month != 0) {
            $anciennete .= ' ' . $days . ' jours';
        }

        $missions = Mission::where('employe_id', $employe->id)->where('valider', true)->where('annee_id', $evaluation->annee_id)->get();
        if (count($missions) == 0) {
            return GlobalResource::make(['code' => 500, 'message' => 'Impossible de télécharger ! Vos missions de cette année ne sont pas validée. Contactez votre responsable']);
        }

        $objectifs = Objectif::where('employe_id', $employe->id)
            ->where('annee_id', $evaluation->annee->id)
            ->where('appreFinal', '!=', 'NULL')
            ->where('niveauExecFinal', '!=', 'NULL')
            ->get();

        $data = "select sum(noteObtenue) as performance from objectifs
                             where employe_id='$employe->id' and annee_id='$evaluation->annee_id'
                             and appreFinal != 'NULL' and niveauExecFinal != 'NULL'";

        $totalperformance = 0;
        if (count(DB::select($data)) > 0) {
            $totalperformance = (DB::select($data))[0]->performance;
        }


        $req = "select categCrit.libelle as categorieCritere, categCrit.code as codeCategorie,  critEval.libelle,critEvaluer.apprFinal as appreciation,critEvaluer.pointFinal as pointObtenu
                from critere_evaluers critEvaluer
                inner join critere_evaluations critEval
                on critEvaluer.critere_evaluation_id = critEval.id
                inner join categorie_criteres categCrit
                on critEval.categorie_critere_id = categCrit.id
                where critEvaluer.evaluation_id=$evaluation->id";
        $dataCritere = DB::select($req);

        $criteres = [];
        $TabtotalCritere = array();
        foreach ($dataCritere as $value) {
            $tab1 = [];
            $tab2 = [];
            foreach ($dataCritere as $item) {
                if (!in_array($value->codeCategorie, $tab2)) {
                    if ($value->codeCategorie == $item->codeCategorie) {
                        array_push($tab1, $item);
                    }
                }
            }
            array_push($tab2, $value->codeCategorie);
            $sommeNote = 0;

            foreach ($tab1 as $item) {
                $sommeNote += (int)$item->pointObtenu;
            }
            $moyenne = round($sommeNote/count($tab1),2);
            $TabtotalCritere[] = $moyenne;
            $criteres[$value->categorieCritere.','.$moyenne] = $tab1;

        }
        $totalCritere = 0;
        foreach ($criteres as $key => $value) {
           $tab = explode(',',$key);
           $totalCritere += (float)$tab[1];
        }

        $anneeEvaluer = $evaluation->annee->libelle;
        $nouvelleAnnee = Annee::where('libelle', $anneeEvaluer + 1)->first();
        $nouvelleObjectifs = Objectif::where('employe_id', $employe->id)->where('annee_id', $nouvelleAnnee->id)->get();

        $tabAccomplissement = explode(';', $evaluation->accomplissement);
        $tabDifficulte = explode(';', $evaluation->difficulteMission);
        $tabProgres = explode(';', $evaluation->progres);
        $formationSuivie = [];
        foreach ($evaluation->formation as $item) {
            array_push($formationSuivie, [
                'id' => $item->id,
                'libelle' => $item->libelle,
                'objectif' => $item->objectifVise,
                'bilan' => $item->formationSuivie->appreciation,
                'date' => (explode(' ', $item->formationSuivie->date))[0],
            ]);
        }
        $besoinFormations = BesoinFormation::where('evaluation_id', $evaluation->id)->get();
        $sommeBonus = $this->getBonusEvaluation($evaluation);
        $commentaires = Commentaire::where('evaluation_id', $evaluation->id)->get();
        $performances = NiveauPerformance::all();

        if ($display=='true') {
            return new GlobalResource([
                'anciennete' => $anciennete,
                'missions' => $missions,
                'objectifs' => $objectifs,
                'criteres' => $criteres,
                'nouvelleObjectifs' => $nouvelleObjectifs,
                'annee' => $evaluation->annee->libelle,
                'employe' => $employe,
                'evaluation' => $evaluation,
                'tabAccomplissement' => $tabAccomplissement,
                'tabDifficulte' => $tabDifficulte,
                'tabProgres' => $tabProgres,
                'formationSuivie' => $formationSuivie,
                'besoinFormations' => $besoinFormations,
                'sommeBonus' => $sommeBonus,
                'commentaires' => $commentaires,
                'performances' => $performances,
                'totalperformance' => $totalperformance,
                'totalcompetence' => round($totalCritere,2),
                'total' => round($totalCritere + $totalperformance,2),
                'codeCategorie' => $employe->categorie_professionnelle->code
            ]);
        }

        $pdf = PDF::loadView('export.rapportEvaluation', [
            'anciennete' => $anciennete,
            'missions' => $missions,
            'objectifs' => $objectifs,
            'criteres' => $criteres,
            'nouvelleObjectifs' => $nouvelleObjectifs,
            'annee' => $evaluation->annee->libelle,
            'employe' => $employe,
            'evaluation' => $evaluation,
            'tabAccomplissement' => $tabAccomplissement,
            'tabDifficulte' => $tabDifficulte,
            'tabProgres' => $tabProgres,
            'formationSuivie' => $formationSuivie,
            'besoinFormations' => $besoinFormations,
            'sommeBonus' => $sommeBonus,
            'commentaires' => $commentaires,
            'performances' => $performances,
        ]);
        $filename = 'rapportEvaluation' . $employe->nom . '(' . $employe->matricule . ').' . 'pdf';
        return $pdf->download($filename);
    }

    public function getPerformanceService(Request $request,$idTypeEva=0)
    {
        if ($idTypeEva == 0) {
            $typeEvaluation = TypeEvaluation::where('code','EA')->first();
            $idTypeEva = $typeEvaluation->id;
        }else{
            $typeEvaluation = TypeEvaluation::find($idTypeEva);
        }
        //dd($typeEvaluation);
        $campagne = CampagnePerformance::where('cloturer', false)
            ->where('type_evaluation_id',$idTypeEva)
            ->first();
        if ($typeEvaluation->code == 'EA'){
            //$campagne = CampagnePerformance::where('annee_id', $idAnnee)->first();

            if ($campagne == '' or $campagne == null) {
                return GlobalResource::make(['code' => 500, 'message' => "Aucune campagne d'évaluation n'est lancée par les rh pour cette année"]);
            }

            $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
            $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
            $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

            if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
            } else {
                return GlobalResource::make(['code' => 500, 'message' => 'La période définie pour la campagne des performances ou de saisir d\'objectif est expirée! Contactez les RH.']);
            }
        }
        $idAnnee = $campagne->annee_id;
        $responsable = Employe::where('email', $request->user()->email)->first();
        $service = $responsable->fonction->service;

        $req = "select distinct empl.id as idUser,empl.matricule, empl.nom, empl.prenoms, serv.libelle as unite, eval.id as idEval,
                eval.performanceRealiser as performance, eval.dateEvaluation
                from evaluations eval
                inner join employes empl on eval.employe_id = empl.id
                inner join fonctions fonct on empl.fonction_id=fonct.id
                inner join services serv on fonct.service_id = serv.id
                inner join unites unit on unit.service_id = serv.id
				inner join annees on eval.annee_id = annees.id
                inner join type_evaluations typeEva on typeEva.id=eval.type_evaluation_id
				";

        if($typeEvaluation->code == 'EA'){
            $req .= " inner join validations val on val.evaluation_id = eval.id";
        }

        $req .=" where serv.id = '$service->id'
                and empl.id != $responsable->id
                and annees.id = $idAnnee
                and typeEva.id = $idTypeEva ";

        if ($typeEvaluation->code=='EA'){
            $req .=" and eval.transmis = 1";
            if ($responsable->respUnite){
                $req .=" and val.niveau1 = 0 and val.niveau2 = 0  and empl.unite_id= " .$responsable->unite_id ." and empl.respUnite = 0";
            }else{
                $req .=" and val.niveau1 = 1 and val.niveau2 = 0 ";
            }
        }

        $salaries = DB::select($req);

        $salaries = json_decode(json_encode($salaries),true);

        foreach ($salaries as $key=>$evaluation) {
            $salaries[$key]['performance'] = round($evaluation['performance'],2);
        }

        return GlobalResource::make(['code' => 200, 'salaries' => $salaries]);

    }

    public function validationPerformanceSalarie(Request $request, $idService, $idTypeEva=0)
    {
        try {
            $campagne = CampagnePerformance::where('type_evaluation_id',$idTypeEva)->where('cloturer', false)->latest()->first();
            $idAnnee = $campagne->annee_id;
            $sql = '';
            $responsable = Employe::where('email', $request->user()->email)->first();

            $tabId = array();
            foreach ($request->evaluations as $item) {
                array_push($tabId, (int)$item['idEval']);
            }

            if ($responsable->respService or $responsable->respUnite) {
                $service = $responsable->fonction->service;
                $idService = $service->id;
                if ($responsable->respService) {
                    $sql = "update
                    valid set valid.niveau2=1 ";
                    if ($responsable->fonction->service->direction->code != 'DU') {
                        $sql .= " ,valid.niveau3=1";
                    }
                } else {
                    $sql = "update
                    valid set valid.niveau1=1 ";
                }

                $sql .= " from
                    validations valid
                    inner join evaluations eval on eval.id= valid.evaluation_id
                    inner join employes empl on empl.id = eval.employe_id
                    inner join fonctions fonct on fonct.id = empl.fonction_id
                    inner join services serv on serv.id = fonct.service_id
                where serv.id = $service->id
                and eval.id in ('"
                    . implode("','", array_map('intval', $tabId))
                    . "')
                and eval.annee_id = $idAnnee";
            }

            if ($responsable->respDepartement or $responsable->isDirecteur) {
                if ($responsable->respDepartement) {
                    $sql = "update
                    valid set valid.niveau3=1 ";
                }

                if ($responsable->isDirecteur) {
                    $sql = "update
                    valid set valid.niveau3=1, valid.niveau4=1 ";
                }

                $sql .= " from
                    validations valid
                    inner join evaluations eval on eval.id= valid.evaluation_id
                    inner join employes empl on empl.id = eval.employe_id
                    inner join fonctions fonct on fonct.id = empl.fonction_id
                    inner join services serv on serv.id = fonct.service_id
                where serv.id = $idService
                and eval.id in ('"
                    . implode("','", array_map('intval', $tabId))
                    . "')
                and eval.annee_id = $idAnnee";
            }

            DB::update($sql);

            $created_at = (new \DateTime())->format('Y-d-m H:i:s');
            $update_at = (new \DateTime())->format('Y-d-m H:i:s');
            if ($request->commentaire != '' && $request->commentaire != null) {
                $profile = $this->getProfile($request, $responsable);
                $commentaire = htmlspecialchars($request->commentaire);
                $tabComentaire = explode("'", $commentaire);
                $commentaire = '';
                foreach ($tabComentaire as $item) {
                    if ($commentaire == '') {
                        $commentaire = $item;
                    } else {
                        $commentaire .= ',' . $item;
                    }
                }

                $req = "INSERT INTO commentaires(libelle,bonus,employe_id,evaluation_id,profile,created_at,updated_at)
                    (
                        select '$commentaire','$request->bonus', '$responsable->id',eval.id,'$profile','$created_at','$update_at'
                        from evaluations eval
                        inner join employes empl on empl.id = eval.employe_id
                        inner join fonctions fonct on fonct.id = empl.fonction_id
                        inner join services serv on serv.id = fonct.service_id
                        where serv.id = $idService
                        and eval.annee_id = $idAnnee
                        and eval.id in ('"
                    . implode("','", array_map('intval', $tabId))
                    . "')
                    )";
                DB::insert($req);
            }

            if ($responsable->isDirecteur) {
                /* $req = "update eval set eval.clotureResp = 1
                         from
                             evaluations eval
                             inner join employes empl on empl.id = eval.employe_id
                             inner join fonctions fonct on fonct.id = empl.fonction_id
                             inner join services serv on serv.id = fonct.service_id
                         where serv.id = $idService
                         and eval.annee_id = $idAnnee";
                 DB::update($req);*/
                foreach ($request->evaluations as $item) {
                    $evaluation = Evaluation::find($item['idEval']);
                    $sommeBonus = $this->getBonusEvaluation($evaluation);
                    $evaluation->performanceFinal = $evaluation->performanceRealiser + $sommeBonus;
                    $evaluation->clotureResp = 1;
                    $evaluation->save();
                }
            }
            return GlobalResource::make(['code' => 200, 'message' => 'Toutes les évaluations ont été validée avec succès.']);

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec validation performance service',
                'message' => $exception->getMessage() . ' à la ligne ' . $exception->getLine(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'validationPerformanceSalarie'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la validation des évaluations. Contactez IT']);

        }

    }

    public function validationCodi(Request $request, $idService, $groupe)
    {
        try {
            $campagne = CampagnePerformance::where('cloturer', false)->first();
            if ($campagne == '' or $campagne == null) {
                return response()->json(['code' => 403, 'message' => 'Aucune campagne d\'évaluation lancée par les RH'],500);
            }
            $idAnnee = $campagne->annee_id;
            //dd($idService,$groupe,$request->all());
            $responsable = Employe::where('email', $request->user()->email)->first();

            if ($groupe == 'true') {
                $tabId = array();
                foreach ($request->evaluations as $item) {
                    array_push($tabId, (int)$item['id']);
                }
            } else {
                $tabId = [(int)$request->evaluations];
            }

            /*$req = "update eval set eval.clotureCodi = 1
                        from
                            evaluations eval
                            inner join employes empl on empl.id = eval.employe_id
                            inner join fonctions fonct on fonct.id = empl.fonction_id
                            inner join services serv on serv.id = fonct.service_id
                        where serv.id = $idService
                        and eval.annee_id = $idAnnee
                        and eval.id in ('"
                . implode("','", array_map('intval', $tabId))
                . "')";
            DB::update($req);*/

            $profile = $this->getProfile($request, $responsable);

            $created_at = (new \DateTime())->format('Y-d-m H:i:s');
            $update_at = (new \DateTime())->format('Y-d-m H:i:s');
            if ($request->commentaire != '' && $request->commentaire != null) {
                $commentaire = htmlspecialchars($request->commentaire);
                $tabComentaire = explode("'", $commentaire);
                $commentaire = '';
                foreach ($tabComentaire as $item) {
                    if ($commentaire == '') {
                        $commentaire = $item;
                    } else {
                        $commentaire .= ',' . $item;
                    }
                }

                $req = "INSERT INTO commentaires(libelle,bonus,employe_id,evaluation_id,profile,created_at,updated_at,isCodi)
                    (
                        select '$commentaire','$request->bonus', '$responsable->id',eval.id,'$profile','$created_at','$update_at','1'
                        from evaluations eval
                        inner join employes empl on empl.id = eval.employe_id
                        inner join fonctions fonct on fonct.id = empl.fonction_id
                        inner join services serv on serv.id = fonct.service_id
                        where serv.id = $idService
                        and eval.annee_id = $idAnnee
                        and eval.id in ('"
                    . implode("','", array_map('intval', $tabId))
                    . "')
                    )";
                DB::insert($req);

                if ($groupe=='true') {
                    foreach ($request->evaluations as $item) {
                        $evaluation = Evaluation::find($item['id']);
                        $sommeBonus = $this->getBonusEvaluation($evaluation, true);
                        $evaluation->performanceFinal = $evaluation->performanceFinal + $request->bonus;
                        $evaluation->notePondere = $sommeBonus;
                        $evaluation->save();
                    }
                } else {
                    $evaluation = Evaluation::find($request->evaluations);
                    $sommeBonus = $this->getBonusEvaluation($evaluation, true);
                    $evaluation->performanceFinal = $evaluation->performanceFinal + $request->bonus;
                    $evaluation->notePondere = $sommeBonus;
                    $evaluation->save();
                }

            }
            if ($groupe=='true') {
                $message = 'La pondération a été effectuée pour tous les collaborateurs du service';
            }else{
                $employe = Employe::find($evaluation->employe_id);
                $message = 'La pondération a été effectuée avec succès pour '.$employe->nom.' '.$employe->prenoms;
            }
            return GlobalResource::make(['code' => 200, 'message' => $message]);

        } catch (\Exception $exception) {
            dd($exception->getMessage().$exception->getLine());
        }
    }

    public function calculCagnote(Request $request)
    {
        try {
            if ($request->taux == 0 or $request->taux== null){
                return response()->json(['message'=>'Taux obligatoire'],500);
            }

            $dataTogetBenificiaire = $this->mouchard->dataTogetBenificiaire();
            $campagne = $dataTogetBenificiaire['campagne'];

            if ($campagne == '' or $campagne == null) {
                return response()->json(['code' => 403, 'message' => 'Aucune campagne d\'évaluation lancée par les RH'],500);
            }
            $idAnnee = $dataTogetBenificiaire['idAnnee'];
            $categorieCadre = $dataTogetBenificiaire['categorieCadre'];
            $categorieMaitrise = $dataTogetBenificiaire['categorieMaitrise'];
            $categorieExecutant = $dataTogetBenificiaire['categorieExecutant'];
            $typeEvaluation = $dataTogetBenificiaire['typeEvaluation'];

            $tauxResulat = $request->taux;
            $salaireBase = $request->salaireBase;
            $budgetBonus = $request->BudgetBonus;

            /*$req =  "select sum(catepro.smc) as totalSB
                    from evaluations eval
                    inner join employes empl on eval.employe_id=empl.id
                    inner join categorie_professionnelles catepro on empl.categorie_professionnelle_id=catepro.id
                    inner join annees on eval.annee_id=annees.id
                    where annee_id= $idAnnee
                    and eval.clotureCodi='true'";
            $totalSB = 0;*/
            /* if (count(DB::select($req)) > 0) {
                 $totalSB = round((DB::select($req))[0]->totalSB,2);
             }*/
            $troispercentSB = round(($salaireBase*3)/100,2);
            $cagnotteBase = round(($troispercentSB*$tauxResulat)/100,2);

            $niveauperfA = $dataTogetBenificiaire['niveauperfA'];
            $niveauperfB = $dataTogetBenificiaire['niveauperfB'];

            $totalSMCPerfA = 0;
            $totalSMCPerfB = 0;
            $reqperfA = "select sum(catepro.smc) as totalsmc
                    from evaluations eval
                    inner join employes empl on eval.employe_id=empl.id
                    inner join categorie_professionnelles catepro on empl.categorie_professionnelle_id=catepro.id
                    where eval.performanceFinal between '$niveauperfA->borneInf' and '$niveauperfA->borneSup'";
            if (count(DB::select($reqperfA)) > 0) {
                $totalSMCPerfA = round((DB::select($reqperfA))[0]->totalsmc,2);
            }

            $reqperfB = "select sum(catepro.smc) as totalsmc
                    from evaluations eval
                    inner join employes empl on eval.employe_id=empl.id
                    inner join categorie_professionnelles catepro on empl.categorie_professionnelle_id=catepro.id
                    where eval.performanceFinal between '$niveauperfB->borneInf' and '$niveauperfB->borneSup'";
            if (count(DB::select($reqperfB)) > 0) {
                $totalSMCPerfB = round((DB::select($reqperfB))[0]->totalsmc,2);
            }

            if ($request->tauxPerformanceA !=0 && $request->tauxPerformanceB==0){
                $tauxPerformanceA = $request->tauxPerformanceA/100;
                $tauxPerformanceB = ($cagnotteBase - ($totalSMCPerfA*$tauxPerformanceA))/$totalSMCPerfB;
            }else{
                $tauxPerformanceB = $request->tauxPerformanceB/100;
                $tauxPerformanceA = ($cagnotteBase - ($totalSMCPerfB*$tauxPerformanceB))/$totalSMCPerfA;
            }

            $reqCadre = $this->mouchard->nbreEvaluerParCategorie($idAnnee,$categorieCadre->id,$typeEvaluation->id);
            $reqMaitrise = $this->mouchard->nbreEvaluerParCategorie($idAnnee,$categorieMaitrise->id,$typeEvaluation->id);
            $reqExecutant = $this->mouchard->nbreEvaluerParCategorie($idAnnee,$categorieExecutant->id,$typeEvaluation->id);

            $nbrCadreBeneficiaire = 0;
            if (count($reqCadre) > 0) {
                $nbrCadreBeneficiaire = (int) round(($reqCadre[0]->nombreCadreEvaluer * 30)/100);
            }

            $nbrMaitriseBeneficiaire = 0;
            if (count($reqMaitrise) > 0) {
                $nbrMaitriseBeneficiaire = (int) round(($reqMaitrise[0]->nombreCadreEvaluer * 30)/100);
            }

            $nbrExecutantBeneficiaire = 0;
            if (count($reqExecutant) > 0) {
                $nbrExecutantBeneficiaire = (int) round(($reqExecutant[0]->nombreCadreEvaluer * 30)/100);
            }
            $listeCadresBeneficiares = json_decode(json_encode($this->mouchard->listeBeneficiaires($nbrCadreBeneficiaire,$idAnnee,$categorieCadre->id,$typeEvaluation->id,$niveauperfA,$niveauperfB)),true);
            $listeMaitrisesBeneficiares = json_decode(json_encode($this->mouchard->listeBeneficiaires($nbrMaitriseBeneficiaire,$idAnnee,$categorieMaitrise->id,$typeEvaluation->id,$niveauperfA,$niveauperfB)),true);
            $listeExecutantsBeneficiares = json_decode(json_encode($this->mouchard->listeBeneficiaires($nbrExecutantBeneficiaire,$idAnnee,$categorieExecutant->id,$typeEvaluation->id,$niveauperfA,$niveauperfB)),true);

            $beneficiaires = [];
            $sommeBonusBeneficiaire = 0;

            foreach ($listeCadresBeneficiares as $beneficiare){
                if ($beneficiare['niveauPerformanceApresPonderation'] = 'A'){
                    $tauxApplicable = $tauxPerformanceA;
                }else{
                    $tauxApplicable = $tauxPerformanceB;
                }
                $smc = (CategorieProfessionnelle::where('code',$beneficiare['categorie'])->first())->smc;
                $beneficiare['smc'] = round($smc,2);
                $beneficiare['performanceRealiser'] = round($beneficiare['performanceRealiser'],2);
                $beneficiare['performanceFinal'] = round($beneficiare['performanceFinal'],2);
                $beneficiare['tauxAppliqueSmc'] = $tauxApplicable*$smc;
                $beneficiare['montantBonus'] = $beneficiare['tauxAppliqueSmc']*24;
                $beneficiare['isBonus'] = (boolean)$beneficiare['isBonus'];
                $sommeBonusBeneficiaire += $beneficiare['montantBonus'];

                $laureat = LaureatEvaluation::where('isPrimeExcept',false)
                    ->where('matricule',$beneficiare['matricule'])
                    ->where('annee_id',$dataTogetBenificiaire['idAnnee'])->first();

                $laureat->montantAppliquer = $beneficiare['tauxAppliqueSmc'];
                $laureat->montantBonus = $beneficiare['montantBonus'];
                $laureat->service = $beneficiare['service'];
                $laureat->idEval = $beneficiare['idEval'];
                $laureat->noteObtenue = $beneficiare['performanceRealiser'];
                $laureat->perfObtenue = $beneficiare['niveauPerformance'];
                $laureat->tauxPerfA = round($tauxPerformanceA*100,2);
                $laureat->tauxPerfB = round($tauxPerformanceB*100,2);
                $laureat->tauxResultat = $tauxResulat;
                $laureat->sommeSB = $salaireBase;
                $laureat->budget = $budgetBonus;
                $laureat->montantTroisPercent = $troispercentSB;
                $laureat->montantCagnotte = $cagnotteBase;
                $laureat->totalSMCperfA = $totalSMCPerfA;
                $laureat->totalSMCperfB = $totalSMCPerfB;
                $laureat->save();

                $beneficiaires[] = $beneficiare;
            }

            foreach ($listeMaitrisesBeneficiares as $beneficiare){
                if ($beneficiare['niveauPerformanceApresPonderation'] = 'A'){
                    $tauxApplicable = $tauxPerformanceA;
                }else{
                    $tauxApplicable = $tauxPerformanceB;
                }
                $smc = (CategorieProfessionnelle::where('code',$beneficiare['categorie'])->first())->smc;
                $beneficiare['smc'] = round($smc,2);
                $beneficiare['performanceRealiser'] = round($beneficiare['performanceRealiser'],2);
                $beneficiare['performanceFinal'] = round($beneficiare['performanceFinal'],2);
                $beneficiare['tauxAppliqueSmc'] = $tauxApplicable*$smc;
                $beneficiare['montantBonus'] = $beneficiare['tauxAppliqueSmc']*24;
                $sommeBonusBeneficiaire += $beneficiare['montantBonus'];

                $laureat = LaureatEvaluation::where('isPrimeExcept',false)
                    ->where('matricule',$beneficiare['matricule'])
                    ->where('annee_id',$dataTogetBenificiaire['idAnnee'])->first();

                $laureat->montantAppliquer = $beneficiare['tauxAppliqueSmc'];
                $laureat->montantBonus = $beneficiare['montantBonus'];
                $laureat->service = $beneficiare['service'];
                $laureat->idEval = $beneficiare['idEval'];
                $laureat->noteObtenue = $beneficiare['performanceRealiser'];
                $laureat->perfObtenue = $beneficiare['niveauPerformance'];
                $laureat->tauxPerfA = round($tauxPerformanceA*100,2);
                $laureat->tauxPerfB = round($tauxPerformanceB*100,2);
                $laureat->tauxResultat = $tauxResulat;
                $laureat->sommeSB = $salaireBase;
                $laureat->budget = $budgetBonus;
                $laureat->montantTroisPercent = $troispercentSB;
                $laureat->montantCagnotte = $cagnotteBase;
                $laureat->totalSMCperfA = $totalSMCPerfA;
                $laureat->totalSMCperfB = $totalSMCPerfB;
                $laureat->save();

                $beneficiaires[] = $beneficiare;
            }

            foreach ($listeExecutantsBeneficiares as $beneficiare){
                if ($beneficiare['niveauPerformanceApresPonderation'] = 'A'){
                    $tauxApplicable = $tauxPerformanceA;
                }else{
                    $tauxApplicable = $tauxPerformanceB;
                }
                $smc = (CategorieProfessionnelle::where('code',$beneficiare['categorie'])->first())->smc;
                $beneficiare['smc'] = round($smc,2);
                $beneficiare['performanceRealiser'] = round($beneficiare['performanceRealiser'],2);
                $beneficiare['performanceFinal'] = round($beneficiare['performanceFinal'],2);
                $beneficiare['tauxAppliqueSmc'] = $tauxApplicable*$smc;
                $beneficiare['montantBonus'] = $beneficiare['tauxAppliqueSmc']*24;
                $sommeBonusBeneficiaire += $beneficiare['montantBonus'];

                $laureat = LaureatEvaluation::where('isPrimeExcept',false)
                    ->where('matricule',$beneficiare['matricule'])
                    ->where('annee_id',$dataTogetBenificiaire['idAnnee'])->first();

                $laureat->montantAppliquer = $beneficiare['tauxAppliqueSmc'];
                $laureat->montantBonus = $beneficiare['montantBonus'];
                $laureat->service = $beneficiare['service'];
                $laureat->idEval = $beneficiare['idEval'];
                $laureat->noteObtenue = $beneficiare['performanceRealiser'];
                $laureat->perfObtenue = $beneficiare['niveauPerformance'];
                $laureat->tauxPerfA = round($tauxPerformanceA*100,2);
                $laureat->tauxPerfB = round($tauxPerformanceB*100,2);
                $laureat->tauxResultat = $tauxResulat;
                $laureat->sommeSB = $salaireBase;
                $laureat->budget = $budgetBonus;
                $laureat->montantTroisPercent = $troispercentSB;
                $laureat->montantCagnotte = $cagnotteBase;
                $laureat->totalSMCperfA = $totalSMCPerfA;
                $laureat->totalSMCperfB = $totalSMCPerfB;
                $laureat->save();

                $beneficiaires[] = $beneficiare;
            }

            $listeBeneficiairePrimeExeptionnelles = LaureatEvaluation::where('isPrimeExcept',true)->where('annee_id',$dataTogetBenificiaire['idAnnee'])->get();

            $sommePrimeExcept = 0;
            foreach ($listeBeneficiairePrimeExeptionnelles as $beneficiareprime){
                $beneficiaires[] = array(
                    'salarie'=>$beneficiareprime['salarie'],
                    'performanceRealiser'=>round($beneficiareprime['noteObtenue'],2),
                    'performanceFinal'=>round($beneficiareprime['notePonderer'],2),
                    'matricule'=>$beneficiareprime['matricule'],
                    'categorie'=>$beneficiareprime['categorie'],
                    'poste'=>$beneficiareprime['poste'],
                    'service'=>$beneficiareprime['service'],
                    'smc'=>$beneficiareprime['smc'],
                    'tauxAppliqueSmc'=>0,
                    'montantBonus'=>($beneficiareprime['montantBonus']==null)?0:$beneficiareprime['montantBonus'],
                    'montantPrime'=>($beneficiareprime['montantPrime']==null)?0:round($beneficiareprime['montantPrime'],2),
                    'isBonus'=>false,
                    'niveauPerformance'=>$beneficiareprime['perfObtenue'],
                    'niveauPerformanceApresPonderation'=>$beneficiareprime['PerfApresPondereration'],
                );
                $sommePrimeExcept +=($beneficiareprime['montantPrime']==null)?0:round($beneficiareprime['montantPrime'],2);
            }

            return  response()->json([
                'troispercentSB'=>$troispercentSB,
                'cagnotteBase'=>$cagnotteBase,
                'totalSMCPerfA'=>$totalSMCPerfA,
                'totalSMCPerfB'=>$totalSMCPerfB,
                'tauxPerformanceA'=>round($tauxPerformanceA*100,2),
                'tauxPerformanceB'=>round($tauxPerformanceB*100,2),
                'beneficiaires'=>$beneficiaires,
                'sommeBonusBeneficiaire'=>round($sommeBonusBeneficiaire,2),
                'sommePrimeExcept'=>round($sommePrimeExcept,2),
            ]);
        }catch (\Exception $exception){
            dd($exception->getMessage(),' à la ligne '.$exception->getLine());
        }
    }

    public function definedBeneficiarePrimeExceptionnelle(Request $request)
    {
        try {
            $datas = $this->mouchard->dataTogetBenificiaire();
            $categorieProf = CategorieProfessionnelle::where('code',$request->cateprofe)->first();
            $laureatSave = LaureatEvaluation::where('matricule',$request->matricule)->where('annee_id',$datas['idAnnee'])->first();
            if ($laureatSave=='' or $laureatSave == null) {
                LaureatEvaluation::create([
                    'matricule'=>$request->matricule,
                    'salarie'=>$request->nom.' '.$request->prenoms,
                    'poste'=>$request->poste,
                    'categorie'=>$request->cateprofe,
                    'idEval'=>$request->idEval,
                    'noteObtenue'=>$request->performanceRealiser,
                    'service'=>$request->service,
                    'smc'=>$categorieProf->smc,
                    'notePonderer'=>$request->performanceFinal,
                    'perfObtenue'=>$request->niveauPerf,
                    'perfApresPondereration'=>$request->niveauPerfApresPonde,
                    'isPrimeExcept'=> true,
                    'annee_id'=> $datas['idAnnee']
                ]);
            }

            return response()->json(['code'=>'201','message'=>'Opération réussie'],201);

        }catch (\Exception $exception){
            dd($exception->getMessage().$exception->getLine());
        }

    }

    public function removeBeneficiairePrimeExceptionnelle(Request $request)
    {
        $datas = $this->mouchard->dataTogetBenificiaire();
        $laureatPrime = LaureatEvaluation::where('matricule',$request->matricule)->where('annee_id',$datas['idAnnee'])->first();
        $laureatPrime->delete();

        return response()->json(['code'=>'200','message'=>'Opération réussie'],200);
    }

    public function setValeurPrimeExceptionnelle(Request $request)
    {
        $laureatBonus = LaureatEvaluation::where('matricule',$request->matricule)->first();
        $laureatBonus->montantPrime = $request->valeurPrime;
        $laureatBonus->save();

        return response()->json(['code'=>'201','message'=>'Opération réussie'],201);
    }

    public function checkListeValider()
    {
        $data = $this->mouchard->dataTogetBenificiaire();
        $idAnnee = $data['idAnnee'];
        $listeValider = false;
        $beneficiaires = [];
        $laureatEvaluations = LaureatEvaluation::where('annee_id',$idAnnee)
            ->where('valider',true)
            ->get();
        if (count($laureatEvaluations)>0) {
            $listeValider = true;
        }
        $unbeneficiare = LaureatEvaluation::where('annee_id',$idAnnee)
            ->where('valider',true)->where('isPrimeExcept',false)->first();
        if ($unbeneficiare!='' && $unbeneficiare!=null){
            $dataCagnotte = array(
                'tautResultat'=>round($unbeneficiare->tauxResultat,2),
                'sommeSB'=>round($unbeneficiare->sommeSB,2),
                'budget'=>round($unbeneficiare->budget,2),
                'tauxPerfA'=>round($unbeneficiare->tauxPerfA,2),
                'tauxPerfB'=>round($unbeneficiare->tauxPerfB,2),
                'montantTroisPercent'=>round($unbeneficiare->montantTroisPercent,2),
                'montantCagnotte'=>round($unbeneficiare->montantCagnotte,2),
                'totalSMCperfA'=>round($unbeneficiare->totalSMCperfA,2),
                'totalSMCperfB'=>round($unbeneficiare->totalSMCperfB,2),
            );
        }

        $tabBeneficiaire = [];
        $sommeBonus = 0;
        $sommePrime = 0;
        foreach ($laureatEvaluations as $beneficiaire) {
            $sommeBonus +=round($beneficiaire->montantBonus,2);
            $sommePrime +=round($beneficiaire->montantPrime,2);
            $tabBeneficiaire[] = array(
                'categorie'=>$beneficiaire->categorie,
                'idEval'=>$beneficiaire->idEval,
                'isBonus'=> $beneficiaire->isPrimeExcept ?false:true,
                'matricule'=>$beneficiaire->matricule,
                'montantBonus'=>round($beneficiaire->montantBonus,2),
                'montantPrime'=>round($beneficiaire->montantPrime,2),
                'niveauPerformance'=>$beneficiaire->perfObtenue,
                'niveauPerformanceApresPonderation'=>$beneficiaire->PerfApresPondereration,
                'performanceFinal'=>round($beneficiaire->notePonderer,2),
                'performanceRealiser'=>round($beneficiaire->noteObtenue,2),
                'poste'=>$beneficiaire->poste,
                'salarie'=>$beneficiaire->salarie,
                'service'=>$beneficiaire->service,
                'smc'=>round($beneficiaire->smc,2),
                'tauxAppliqueSmc'=>round($beneficiaire->montantAppliquer,2),
            );
        }
        $dataCagnotte['sommeBonus'] = $sommeBonus;
        $dataCagnotte['sommePrime'] = $sommePrime;

        return response()->json([
            'listeValider'=>$listeValider,
            'beneficiaires'=>$tabBeneficiaire,
            'dataCagnotte'=>$dataCagnotte,
            'annee'=>$data['campagne']->annee->libelle
        ]);
    }

    public function validerListeBeneficiaire()
    {
        $data = $this->mouchard->dataTogetBenificiaire();
        $idAnnee = $data['idAnnee'];
        $laureatPrimeExcep = LaureatEvaluation::where('isPrimeExcept',true)
            ->where('montantPrime',NULL)
            ->where('annee_id',$idAnnee)
            ->get();
        if (count($laureatPrimeExcep)){
            return response()->json(['code'=>'500','message'=>'Tous les béneficiaires de la prime exceptionnelle n\'ont pas encore reçu un montant'],500);
        }

        DB::update("update laureat_evaluations set valider=1 where annee_id=$idAnnee");

        return response()->json(['code'=>'201','message'=>'Opération réussie'],201);
    }

    public function downloadListeBeneficiaire(Request $request)
    {
        $data = $this->mouchard->dataTogetBenificiaire();
        $idAnnee = $data['idAnnee'];
        $laureatEvaluations = LaureatEvaluation::where('annee_id',$idAnnee)
            ->where('valider',true)
            ->get();
        if (count($laureatEvaluations)>0) {
            $listeValider = true;
        }
        $unbeneficiare = LaureatEvaluation::where('annee_id',$idAnnee)
            ->where('valider',true)->where('isPrimeExcept',false)->first();
        if ($unbeneficiare!='' && $unbeneficiare!=null){
            $dataCagnotte = array(
                'tautResultat'=>round($unbeneficiare->tauxResultat,2),
                'sommeSB'=>round($unbeneficiare->sommeSB,2),
                'budget'=>round($unbeneficiare->budget,2),
                'tauxPerfA'=>round($unbeneficiare->tauxPerfA,2),
                'tauxPerfB'=>round($unbeneficiare->tauxPerfB,2),
                'montantTroisPercent'=>round($unbeneficiare->montantTroisPercent,2),
                'montantCagnotte'=>round($unbeneficiare->montantCagnotte,2),
                'totalSMCperfA'=>round($unbeneficiare->totalSMCperfA,2),
                'totalSMCperfB'=>round($unbeneficiare->totalSMCperfB,2),
            );
        }

        $tabBeneficiaire = [];
        $sommeBonus = 0;
        $sommePrime = 0;
        foreach ($laureatEvaluations as $beneficiaire) {
            $sommeBonus +=round($beneficiaire->montantBonus,2);
            $sommePrime +=round($beneficiaire->montantPrime,2);
            $tabBeneficiaire[] = array(
                'categorie'=>$beneficiaire->categorie,
                'idEval'=>$beneficiaire->idEval,
                'isBonus'=> $beneficiaire->isPrimeExcept ?false:true,
                'matricule'=>$beneficiaire->matricule,
                'montantBonus'=>round($beneficiaire->montantBonus,2),
                'montantPrime'=>round($beneficiaire->montantPrime,2),
                'niveauPerformance'=>$beneficiaire->perfObtenue,
                'niveauPerformanceApresPonderation'=>$beneficiaire->PerfApresPondereration,
                'performanceFinal'=>round($beneficiaire->notePonderer,2),
                'performanceRealiser'=>round($beneficiaire->noteObtenue,2),
                'poste'=>$beneficiaire->poste,
                'salarie'=>$beneficiaire->salarie,
                'service'=>$beneficiaire->service,
                'smc'=>round($beneficiaire->smc,2),
                'tauxAppliqueSmc'=>round($beneficiaire->montantAppliquer,2),
            );
        }
        $dataCagnotte['sommeBonus'] = $sommeBonus;
        $dataCagnotte['sommePrime'] = $sommePrime;
        //dd($tabBeneficiaire,$dataCagnotte);

        return view("export.beneficiaire", [
            'beneficiaires'=>$tabBeneficiaire,
            'dataCagnotte'=>$dataCagnotte,
            'annee'=>$data['campagne']->annee->libelle
        ]);

    }

    public function getServiceByDirection(Request $request, $idDirection, $type = 'false')
    {
        try {
            $blocUsine = [];
            if ($type != 'false') {
                $reqbloc = "select depart.libelle, depart.id from departements  depart
                    inner join directions dir on dir.id = depart.direction_id
                    where dir.id = $idDirection";
                $blocUsine = DB::select($reqbloc);

                $sql = "select serv.libelle,serv.id from services serv
                inner join directions dir on dir.id = serv.direction_id
                where dir.id = $idDirection
                and serv.isBloc = 0";
                $services = DB::select($sql);
            } else {
                $sql = "select serv.libelle,serv.id from services serv
                inner join departements depart on depart.id = serv.departement_id
                where depart.id = $idDirection
                and serv.isBloc = 0";
                $services = DB::select($sql);
            }


            return GlobalResource::make(['code' => 200, 'services' => $services, 'blocs' => $blocUsine]);

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec recupération service par direction',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'getServiceByDirection'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la récupération des services de la direction. Contactez IT']);
        }
    }

    public function getPerformanceValidate(Request $request, $idService, $idAnnee)
    {
        $campagne = CampagnePerformance::where('annee_id', $idAnnee)->first();

        if ($campagne == '' or $campagne == null) {
            return GlobalResource::make(['code' => 500, 'message' => "Aucune campagne d'évaluation n'est lancée par les rh pour l'année choisir"]);
        }

        $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
        $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
        $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

        if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
        } else {
            return GlobalResource::make(['code' => 500, 'message' => 'La période définie pour la campagne des performances ou de saisir d\'objectif est expirée! Contactez les RH.']);
        }

        $req = "select empl.matricule, empl.nom, empl.prenoms, serv.libelle as unite, eval.performanceRealiser as performance, eval.id as idEval from evaluations eval
                inner join employes empl on eval.employe_id = empl.id
                inner join fonctions fonct on empl.fonction_id=fonct.id
                inner join services serv on fonct.service_id = serv.id
                inner join unites unit	on unit.service_id = serv.id
				inner join annees on eval.annee_id = annees.id
				inner join validations val on val.evaluation_id = eval.id
                where serv.id = $idService
                and eval.clotureResp = 1
                and eval.clotureCodi = 0
                and annees.id = $idAnnee";

        return GlobalResource::make(['code' => 200, 'evaluations' => DB::select($req)]);

    }

    public function getRapportByRh($idAnnee, $idEmploye)
    {
        $evaluation = Evaluation::where('annee_id', $idAnnee)->where('employe_id', $idEmploye)->first();
        if ($evaluation->clotureResp) {
            return GlobalResource::make(['code' => 200, 'evaluation' => $evaluation->id]);
        } else {
            return GlobalResource::make(['code' => 500]);
        }
    }

    public function calculBonus(Request $request)
    {
        if ($request->totalSalaireBase != 0 and $request->percentSmc != 0 and
            $request->totalSmcPerformanceA != 0 and $request->totalSmcPerformanceB != 0
            and $request->percenteApplicable != 0) {
            $montantTheoriqueCagnotte = ($request->totalSalaireBase * $request->percentSmc) / 100;
            $montantCagnotte = ($montantTheoriqueCagnotte * $request->percenteApplicable) / 100;
            $c3 = ($request->totalSmcPerformanceA * 3) / 100;
            $cD = $request->totalSmcPerformanceA + $request->totalSmcPerformanceB;
            $tauxPerformanceB = (($montantCagnotte - $c3) / $cD) * 100;
            $tauxPerformanceA = $tauxPerformanceB + 3;
            $ecart = (($tauxPerformanceA * $request->totalSmcPerformanceA) / 100) + (($tauxPerformanceB * $request->totalSmcPerformanceB) / 100) - $montantCagnotte;
            $bonusTotal = ((($tauxPerformanceA * $request->totalSmcPerformanceA) / 100) + (($tauxPerformanceB * $request->totalSmcPerformanceB) / 100)) * 24;
            $primeExcept = $request->bonusPrevisionnel - $bonusTotal;

            return GlobalResource::make(
                ['code' => 200,
                    'montantTheoriqueCagnotte' => round($montantTheoriqueCagnotte, 2),
                    'montantCagnotte' => round($montantCagnotte, 2),
                    'tauxPerformanceB' => round($tauxPerformanceB, 2),
                    'tauxPerformanceA' => round($tauxPerformanceA, 2),
                    'ecart' => round($ecart, 2),
                    'primeExcept' => round($primeExcept, 2),
                    'bonusTotal' => round($bonusTotal, 2),
                ]
            );
        } else {
            return GlobalResource::make(['code' => 500, 'message' => 'Veuilez envoyer des paramètres non nul pour la détermination des taux']);
        }
    }

    public function invalidEvaluation(Request $request, $idEvaluation)
    {
        try {
            $responsable = Employe::where('email', $request->user()->email)->first();
            $evaluation = Evaluation::find($idEvaluation);
            $validation = Validation::where('evaluation_id', $evaluation->id)->first();
            if ($responsable->respService) {
                $evaluation->clotureEvaluer = false;
                $evaluation->transmis = false;
                $evaluation->save();
                $validation->niveau1 = false;
            }
            $validation->niveau2 = false;
            if ($responsable->isDirecteur) {
                if ($responsable->direction->code != 'DU') {
                    $validation->niveau3 = false;
                }
            }
            $profile = $this->getProfile($request, $responsable);

            $commentaire = Commentaire::create([
                'libelle' => $request->commentaireUnitaire,
                'bonus' => $request->bonusUnitaire,
                'evaluation_id' => $evaluation->id,
                'employe_id' => $responsable->id,
                'profile' => $profile
            ]);
            $commentaire->save();

            $validation->save();
            return GlobalResource::make(['code' => 200]);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec rejet evaluation',
                'message' => $exception->getMessage() . ' à la ligne ' . $exception->getLine(),
                'user' => $request->user()->name,
                'controller' => 'Evaluation',
                'methode' => 'invalidEvaluation'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors du l\'évaluations. Contactez IT']);

        }
    }

    public function verifyCloture()
    {
        $campagne = CampagnePerformance::where('cloturer', false)->first();
        if ($campagne == '' or $campagne == null) {
            return response()->json(['code' => 403, 'message' => 'Aucune campagne d\'évaluation lancée par les RH'],500);
        }

        $idAnnee = $campagne->annee_id;
        $typeEvaluation = TypeEvaluation::where('code','EA')->first();

        $req = "select * from evaluations where annee_id=$idAnnee and type_evaluation_id=$typeEvaluation->id and clotureCodi=0";

        $clotuer = true;
        if(count(DB::select($req))>0){
            $clotuer = false;
        }

        return response()->json($clotuer);

    }

    public function cloturerAllEvaluationsByCodi(Request $request)
    {
        $campagne = CampagnePerformance::where('cloturer', false)->first();
        if ($campagne == '' or $campagne == null) {
            return response()->json(['code' => 403, 'message' => 'Aucune campagne d\'évaluation lancée par les RH'],500);
        }

        $idAnnee = $campagne->annee_id;
        $typeEvaluation = TypeEvaluation::where('code','EA')->first();

        $req = "update evaluations set clotureCodi=1 where annee_id=$idAnnee and type_evaluation_id=$typeEvaluation->id";
        DB::update($req);

        return response()->json(['code' => 200,'message'=>'Toutes les évaluations ont été cloturée']);
    }

    public function getEvaluationMipacoursService(Request $request,$idService)
    {
        $data = $this->mouchard->dataTogetBenificiaire();
        $idAnnee = $data['idAnnee'];
        $typeEvaluation = TypeEvaluation::where('code','EMP')->first();
        $idTypeEva = $typeEvaluation->id;
//        and eval.transmis = 1
//    and eval.clotureResp = 1
        $req1 = "select distinct empl.matricule, empl.haveComputer, empl.nom, empl.prenoms, serv.libelle as unite, eval.id as idEval, eval.performanceRealiser as performance
                from evaluations eval
                inner join employes empl on eval.employe_id = empl.id
                inner join fonctions fonct on empl.fonction_id=fonct.id
                inner join services serv on fonct.service_id = serv.id
                inner join unites unit	on unit.service_id = serv.id
				inner join annees on eval.annee_id = annees.id
                inner join type_evaluations typeEva on typeEva.id=eval.type_evaluation_id
                where serv.id = $idService
                and annees.id = $idAnnee
                and typeEva.id = $idTypeEva
                ";

        return response()->json(['evaluations'=>DB::select($req1)]);
    }

    private function getPerformanceEvaluation($evaluation, $campagne, $employe)
    {
        $req = "select cateCrit.code,sum (critEvaluer.pointFinal) as pointTotal from critere_evaluers critEvaluer
                inner join critere_evaluations critEval
                on critEval.id = critEvaluer.critere_evaluation_id
                inner join categorie_criteres cateCrit
                on cateCrit.id = critEval.categorie_critere_id
                where critEvaluer.evaluation_id='$evaluation->id'
                group by cateCrit.code";

        $pointTotalEvaluation = DB::select($req);
        $total = 0;
        foreach ($pointTotalEvaluation as $point) {
            $cateEmploye = $employe->categorie_professionnelle->categorie;
            if ($cateEmploye->code == 'AE' or $cateEmploye->code == 'E') {
                //les agents de maitrise et exécutant ont les mêmes critères d'évaluation
                $categorieprof = CategorieProfessionnelle::where('code', 'M1')->first();
                $cateEmploye = $categorieprof->categorie->id;
            }else
                $cateEmploye = $cateEmploye->id;
            $nbrCritere = DB::table('critere_evaluations')
                ->join('categorie_criteres', 'critere_evaluations.categorie_critere_id', '=', 'categorie_criteres.id')
                ->join('categorie_employes', 'critere_evaluations.categorie_employe_id', '=', 'categorie_employes.id')
                ->where('categorie_employes.id', $cateEmploye)
                ->where('categorie_criteres.code', $point->code)
                ->count();
            $val = floatval($point->pointTotal) / $nbrCritere;
            $total += $val;
        }

        $req = "select sum(obj.noteObtenue) as totalObjectifEvaluer from objectifs obj
                    where obj.annee_id=$campagne->annee_id
                    and obj.employe_id=$employe->id";
        $notObjectifEvaluer = DB::select($req);

        return [
            'totalCompetence' => round(floatval($total),2),
            'totalPerformance' => round(floatval($notObjectifEvaluer[0]->totalObjectifEvaluer),2),
            'bonus' => $this->getBonusEvaluation($evaluation)
        ];
    }

    private function getCritereEvaluer($evaluation)
    {
        $req = "select * from critere_evaluers where evaluation_id=$evaluation->id";

        return DB::select($req);
    }

    private function getTotalByCategorieCritere($evaluation)
    {
        $req = "select cateCrit.code,count(cateCrit.code) as nbreCritere,sum(critEvaluer.pointFinal) as pointTotal
                from critere_evaluers critEvaluer
                inner join critere_evaluations critEval
                on critEval.id = critEvaluer.critere_evaluation_id
                inner join categorie_criteres cateCrit
                on cateCrit.id = critEval.categorie_critere_id
                where critEvaluer.evaluation_id='$evaluation->id'
                group by cateCrit.code";

        return DB::select($req);
    }

    private function verifyEvaluationValider($request, $employe, $evaluation, $responsable)
    {
        $sql = "select eval.id,eval.performanceRealiser,empl.prenoms,empl.nom,val.niveau1
                from evaluations eval
                inner join employes empl on empl.id = eval.employe_id
                inner join validations val on val.evaluation_id = eval.id
                where empl.id = $employe->id
                and val.evaluation_id = $evaluation->id";

        $data = (DB::select($sql))[0];

        if ($responsable->respService) {
            $sql .= " and val.niveau1= 1 ";
        }

        if ($responsable->respDepartement) {
            $sql .= " and val.niveau1= 1 and val.niveau2 = 1 ";
        }

        if ($responsable->isDirecteur) {
            $sql .= " and val.niveau1= 1 and val.niveau2 = 1 and niveau3 = 1";
        }

        if ($request->user()->hasRole('ROLE_RH') && $data->niveau1==0) {
            $sql .= " and val.niveau1= 1 and val.niveau2 = 1 and niveau3 = 1 and niveau4 = 1";
        }

        return DB::select($sql);

    }

    private function getProfile($request, $responsable)
    {
        $profile = '';
        if ($request->user()->hasRole('ROLE_RESPONSABLE') or $request->user()->hasRole('ROLE_DIRECTEUR')) {
            if ($responsable->respUnite) {
                $profile = 'Responsable unite';
            }
            if ($responsable->respService) {
                $profile = 'Responsable service';
            }
            if ($responsable->respDepartement) {
                $profile = 'Responsable pôle';
            }
            if ($responsable->isDirecteur) {
                $profile = 'Directeur ' . $responsable->direction->code;
            }

            if ($request->user()->hasRole('ROLE_CODI')) {
                $profile = 'CODI';
            }

            if ($request->user()->hasRole('ROLE_RH')) {
                $profile = 'RH';
            }
        }

        return $profile;
    }

    private function checkStatusEvaluation($evaluation, $employe)
    {
        $validation = [];
        if ($employe->respService) {
            $validation = Validation::where('evaluation_id', $evaluation->id)
                ->where('niveau1', true)->where('niveau2', true)->get();
        }

        if ($employe->respDepartement) {
            $validation = Validation::where('evaluation_id', $evaluation->id)
                ->where('niveau1', true)->where('niveau2', true)->where('niveau3', true)->get();
        }

        if ($employe->isDirecteur) {
            $validation = Validation::where('evaluation_id', $evaluation->id)
                ->where('niveau1', true)->where('niveau2', true)
                ->where('niveau3', true)->where('niveau4', true)->get();
        }

        if (count($validation) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function getBonusEvaluation($evaluation, $codi = false)
    {
        $req = "select sum(comm.bonus) as bonus from commentaires comm
                        where evaluation_id = '$evaluation->id'  ";
        if ($codi) {
            $req .= " and isCodi = 1";
        } else {
            $req .= " and isCodi = 0";
        }
        $sommeBonus = (DB::select($req))[0]->bonus;
        if ($sommeBonus == null)
            $sommeBonus = 0;

        return (float)$sommeBonus;
    }



    /* private function authorizeEditEvaluation($idEvaluation,$user){
         $evaluation = Evaluation::find($idEvaluation);
         $responsable = Employe::where('email',$user->email)->first();
         $salarie = Employe::find($evaluation->employe_id);
         $nameResp = $responsable->nom.''.$responsable->prenoms;
         $authorize = true;
         $hasRespUnite = false;
         $message = '';
         if ($nameResp!=$evaluation->evaluateur){
             $authorize = false;
             $req = "select * from employes
                             where employes.respUnite =1
                             and employes.unite_id=$salarie->unite_id";
             if (count(DB::select($req))>0){
                 $hasRespUnite = true;
             }
         }

         if ($hasRespUnite){
             $message = 'Impossible de modifier cette évaluation car vous n\'êtes pas l\'évaluateur direct. A cet effet vous devez rejeter l\'évaluation pour notifier à son responsable d\'apporter les différentes modifications en commun accord avec le collaborateur';
         }else{

         }

         return ['authorize'=>$authorize,'hasRespUnite'=>$hasRespUnite];
     }*/

}
