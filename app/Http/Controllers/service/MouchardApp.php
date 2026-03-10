<?php


namespace App\Http\Controllers\service;


use App\Models\CampagnePerformance;
use App\Models\CategorieEmploye;
use App\Models\Log;
use App\Models\NiveauPerformance;
use App\Models\Service;
use App\Models\TypeEvaluation;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MouchardApp
{
    public function saveLogAction($tabInfo){
        $logs = Log::create([
            'login'=>$tabInfo['login'],
            'action'=>$tabInfo['action'],
            'message'=>$tabInfo['message'],
            'user'=>$tabInfo['user'],
            'controller'=>$tabInfo['controller'],
            'methode'=>$tabInfo['methode'],
        ]);
        $logs->save();
    }

    public function getStatisticService($employe,$idService,$idAnnee){
        $sql = "select count(*) as nbreCollaborateur from employes empl
                inner join fonctions fonct on fonct.id = empl.fonction_id
                inner join services serv on serv.id = fonct.service_id
                where serv.id='$idService'";

        $nbreCollaborateur = (DB::select($sql))[0]->nbreCollaborateur;

        $sql = "select count(*) as salarieEvaluer from employes empl
                inner join fonctions fonct on fonct.id = empl.fonction_id
                inner join services serv on serv.id = fonct.service_id
                inner join evaluations eval on eval.employe_id=empl.id
                inner join validations vald on vald.evaluation_id = eval.id
                where serv.id='$idService' and eval.annee_id ='$idAnnee'
                and vald.niveau1 = '1' and vald.niveau2 = '1' ";

        $service = Service::find($idService);
        if ($employe->isDirecteur && $service->departement_id!=null) {
            if ($service->departement->code == 'DFLT') {
                $sql .= " and vald.niveau3=0";
            }else{
                $sql .= " and vald.niveau3=1";
            }
        }

        $nbrSalarieEvaluer = (DB::select($sql))[0]->salarieEvaluer;

        return [
            'nbreCollaborateur'=>$nbreCollaborateur,
            'nbrSalarieEvaluer'=>$nbrSalarieEvaluer
        ];
    }

    public function nbreEvaluerParCategorie($idAnnee, $idCategorie,$idTypeEva)
    {
        $req =  "select count(*) as nombreCadreEvaluer
                from evaluations eval
                inner join type_evaluations typeEva on eval.type_evaluation_id=typeEva.id
                inner join employes empl on eval.employe_id = empl.id
                inner join categorie_professionnelles catepro on empl.categorie_professionnelle_id=catepro.id
                inner join categorie_employes cateempl on catepro.categorie_employe_id = cateempl.id
                inner join annees on eval.annee_id=annees.id
                inner join validations val on val.evaluation_id = eval.id
                where cateempl.id=$idCategorie
                and typeEva.id = $idTypeEva
                and annees.id = $idAnnee
                and val.niveau1 = 1 and val.niveau2 = 1 and val.niveau3 = 1
                and val.niveau4= 1 and eval.clotureResp= 1
                ";

        return DB::select($req);
    }

    public function listeBeneficiaires($nbre,$idAnnee,$idCategorie,$idTypeEva,$niveauPerfA,$niveauPerfB)
    {
        $req =  "select top($nbre) concat(empl.nom,' ',empl.prenoms) as salarie, eval.id idEval, eval.performanceRealiser,eval.performanceFinal,
                empl.matricule, catepro.code as categorie,fonct.libelle as poste,serv.libelle as service, 'true' as isBonus,
                (select libelle from niveau_performances where eval.performanceRealiser between borneInf and borneSup) as niveauPerformance,
                (select libelle from niveau_performances where eval.performanceFinal between borneInf and borneSup) as niveauPerformanceApresPonderation
                from evaluations eval
                inner join type_evaluations typeEva on eval.type_evaluation_id=typeEva.id
                inner join employes empl on eval.employe_id = empl.id
                inner join categorie_professionnelles catepro on empl.categorie_professionnelle_id=catepro.id
                inner join categorie_employes cateempl on catepro.categorie_employe_id = cateempl.id
                inner join annees on eval.annee_id=annees.id
                inner join fonctions fonct on fonct.id=empl.fonction_id
                inner join services serv on fonct.service_id=serv.id
                inner join validations val on val.evaluation_id = eval.id
                where cateempl.id=$idCategorie
                and typeEva.id = $idTypeEva
                and annees.id = $idAnnee
                and val.niveau1 = 1 and val.niveau2 = 1 and val.niveau3 = 1
                and val.niveau4= 1 and eval.clotureResp= 1
                and ((eval.performanceFinal between $niveauPerfA->borneInf and $niveauPerfA->borneSup) or (eval.performanceFinal between $niveauPerfB->borneInf and $niveauPerfB->borneSup))
                order by eval.performanceFinal desc";"
               /* and eval.clotureCodi='true'*/";

        return DB::select($req);
    }

    public function dataTogetBenificiaire()
    {
        $campagne = CampagnePerformance::where('cloturer', false)->latest()->first();
        $idAnnee = '';
        if ($campagne !='' && $campagne != null) {
            $idAnnee = $campagne->annee_id;
        }
        $categorieCadre = CategorieEmploye::where('code','AC')->first();
        $categorieMaitrise = CategorieEmploye::where('code','AM')->first();
        $categorieExecutant = CategorieEmploye::where('code','AE')->first();
        $typeEvaluation = TypeEvaluation::where('code','EA')->first();
        $niveauperfA = NiveauPerformance::where('libelle','A')->first();
        $niveauperfB = NiveauPerformance::where('libelle','B')->first();

        return [
            'campagne' => $campagne,
            'idAnnee' => $idAnnee,
            'categorieCadre' => $categorieCadre,
            'categorieMaitrise' => $categorieMaitrise,
            'categorieExecutant' => $categorieExecutant,
            'typeEvaluation' => $typeEvaluation,
            'niveauperfA' => $niveauperfA,
            'niveauperfB' => $niveauperfB,
        ];
    }


}
