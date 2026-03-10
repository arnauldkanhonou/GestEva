<?php


namespace App\Http\Traits;


use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Mail\Notification;
use App\Models\Annee;
use App\Models\CampagnePerformance;
use App\Models\CategorieEmploye;
use App\Models\CategorieProfessionnelle;
use App\Models\Departement;
use App\Models\Employe;
use App\Models\Evaluation;
use App\Models\LaureatEvaluation;
use App\Models\Mission;
use App\Models\NiveauPerformance;
use App\Models\Objectif;
use App\Models\Service;
use App\Models\TypeEvaluation;
use App\Models\Unite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

trait CustormRequestSqlTrait
{
    public $mouchardApp;

    public function __construct(MouchardApp $mouchardApp)
    {
        $this->mouchardApp = $mouchardApp;
    }

    public function sendMailNotification(array $tabMail, array $datas)
    {
        Mail::to($tabMail)->send(new Notification($datas));
    }

    public function getEvaluateurs()
    {
        $sql = "select employes.id,services.libelle as service,employes.matricule,employes.nom, employes.email, employes.dateEmbauche, employes.prenoms,fonctions.libelle as poste,
                    employes.respUnite, employes.respService, employes.respDepartement
                    from employes,fonctions,services
                    where employes.fonction_id=fonctions.id
                    and fonctions.service_id=services.id
                    and employes.fonction_id is not NULL
                    and(respUnite='1'or respService='1' or respDepartement='1' or isDirecteur='1')";

        return GlobalResource::make(DB::select($sql));
    }

    public function getEvaluateursByService($id)
    {
        $sql = "select employes.id,services.libelle as service,employes.matricule,employes.nom, employes.email, employes.dateEmbauche, employes.prenoms,fonctions.libelle as poste,
                    employes.respUnite, employes.respService, employes.respDepartement
                    from employes,fonctions,services
                    where employes.fonction_id=fonctions.id
                    and fonctions.service_id=services.id
                    and employes.fonction_id is not NULL
                    and(respUnite='1'or respService='1' or respDepartement='1' or isDirecteur='1')
                    and fonctions.service_id='$id'";

        return GlobalResource::make(DB::select($sql));
    }

    public function searchEvaluateur($req)
    {
        $sql = "select employes.id,services.libelle as service,employes.matricule,employes.nom, employes.email, employes.dateEmbauche, employes.prenoms,fonctions.libelle as poste,
                    employes.respUnite, employes.respService, employes.respDepartement
                    from employes,fonctions,services
                    where employes.fonction_id=fonctions.id
                    and fonctions.service_id=services.id
                    and employes.fonction_id is not NULL
                    and(respUnite='1'or respService='1' or respDepartement='1' or isDirecteur='1')
                    ";

        $sql .= " and employes.nom like '%$req%'";

        if (count(DB::select($sql)) > 0) {
            return GlobalResource::make(DB::select($sql));
        }

        $sql .= " and employes.prenoms like '%$req%'";
        if (count(DB::select($sql)) > 0) {
            return GlobalResource::make(DB::select($sql));
        }

        $sql .= " and employes.matricule like '%$req%'";
        if (count(DB::select($sql)) > 0) {
            return GlobalResource::make(DB::select($sql));
        }

        return GlobalResource::make(DB::select($sql));
    }

    public function getIdcategorieService($idEmploye)
    {
        $sql = "select catEm.id as idCategorie,serv.id as idService  from employes e
                LEFT JOIN categorie_professionnelles as catpro on e.categorie_professionnelle_id = catpro.id
                LEFT JOIN categorie_employes as catEm on catEm.id = catpro.categorie_employe_id
                LEFT JOIN fonctions as fonc on e.fonction_id=fonc.id
                LEFT JOIN services as serv on serv.id=fonc.service_id
                where e.id = $idEmploye";
        $data = DB::select($sql);
        $employe = Employe::find($idEmploye);
        return GlobalResource::make(['employe' => $employe, 'dataSql' => $data[0]]);

    }

    public function searchEmploye(Request $request, $requestValue)
    {
        if ($request->user()->hasRole('ROLE_ADMIN')) {
            $employes = Employe::where('matricule', $requestValue)->get();
        }

        $employe = Employe::where('email', $request->user()->email)->first();
        $fonctionResp = $employe->fonction;
        if ($request->user()->hasRole('ROLE_RESPONSABLE') && $employe->respService) {
            $employes = DB::table('employes')
                ->join('unites', 'employes.unite_id', '=', 'unites.id')
                ->join('services', 'unites.service_id', '=', 'services.id')
                ->where('service_id', $fonctionResp->service_id)
                ->where('matricule', $requestValue)->get();
        }

        if ($request->user()->hasRole('ROLE_RESPONSABLE') && $employe->respDepartement) {
            //$departement = $employe->departement;
            $employes = $this->collaborateurRespDepartement($employe, $requestValue);
        }

        if ($request->user()->hasRole('ROLE_DIRECTEUR') && $employe->isDirecteur) {
            $employes = $this->collaboteurDirecteur($employe, $requestValue);
        }

        return GlobalResource::make(['liste' => $employes]);
    }

    public function statistiqueNumber(Request $request, $idAnnee)
    {
        if ($idAnnee != 0) {
            $campagne = CampagnePerformance::where('annee_id', $idAnnee)->first();
        } else {
            $campagne = CampagnePerformance::latest()->first();
        }

        if ($campagne == null) {
            return GlobalResource::make(['employes' => Employe::all()->count(), 'services' => Service::all()->count(), 'code' => 500, 'message' => 'Vous êtes bien connecté mais les chiffres de votre de tableau de bord seront à 0 car aucune donnée ou camapgne n\'existe encore dans la base']);
        }

        if ($request->user()->hasRole('ROLE_USER') && !$request->user()->hasRole('ROLE_ADMIN') && !$request->user()->hasRole('ROLE_RESPONSABLE')) {
            $employe = Employe::where('email', $request->user()->email)->first();
            $collaborateurs = DB::table('employes')->where('unite_id', $employe->unite->id)->where('id', '!=', $employe->id)->count();
            $evaluations = DB::table('evaluations')->where('employe_id', $employe->id)->count();
            $missions = DB::table('missions')->where('employe_id', $employe->id)->count();
            return GlobalResource::make(['evaluations' => $evaluations, 'collaborateurs' => $collaborateurs, 'missions' => $missions]);
        }
        $employe = Employe::where('email', $request->user()->email)->first();
        if ($request->user()->hasRole('ROLE_ADMIN') or $request->user()->hasRole('ROLE_CODI')) {
            $employes = DB::table('employes')->where('isDirecteur', 0)->count();
            $unites = DB::table('unites')->count();
            $services = DB::table('services')->count();
            $departements = DB::table('departements')->count();

            $salarierEvaluer = $this->getSalaierEvaluer($request, $campagne, $employe);
            $serviceEvaluer = $this->getServiceEvaluer($campagne, true);
            $performance = $this->getPerformanceRealiser($request, $campagne, $employe);

            return GlobalResource::make(['employes' => $employes,
                'unites' => $unites, 'services' => $services,
                'departements' => $departements, 'salarierEvaluer' => $salarierEvaluer,
                'serviceEvaluer' => $serviceEvaluer, 'performance' => $performance,
                'campagne' => $campagne
            ]);

        }

        if ($request->user()->hasRole('ROLE_RESPONSABLE') && $employe->respService) {
            $posteResp = $employe->fonction;
            $serviceResp = $posteResp->service;
            $collaborateurs = DB::table('employes')
                ->join('fonctions', 'employes.fonction_id', '=', 'fonctions.id')
                ->join('services', 'fonctions.service_id', '=', 'services.id')
                ->where('services.id', $serviceResp->id)
                ->where('employes.id', '!=', $employe->id)
                ->count();
            $unites = DB::table('unites')->where('service_id', $serviceResp->id)->count();
            $evaluations = DB::table('evaluations')->where('employe_id', $employe->id)->count();
            $salarierEvaluer = $this->getSalaierEvaluer($request, $campagne, $employe);
            $performance = $this->getPerformanceRealiser($request, $campagne, $employe);

            return GlobalResource::make(['employes' => $collaborateurs,
                'unites' => $unites, 'evaluations' => $evaluations,
                'salarierEvaluer' => $salarierEvaluer, 'performance' => $performance,
                'campagne' => $campagne
            ]);
        }
        if ($request->user()->hasRole('ROLE_RESPONSABLE') && $employe->respUnite) {
            $uniteResp = $employe->unite;
            $collaborateurs = DB::table('employes')->where('unite_id', $uniteResp->id)->where('id', '!=', $employe->id)->count();
//            $unites = DB::table('unites')->where('service_id', $serviceResp->id)->count();
            $evaluations = DB::table('evaluations')->where('employe_id', $employe->id)->count();
            $salarierEvaluer = $this->getSalaierEvaluer($request, $campagne, $employe);
            $performance = $this->getPerformanceRealiser($request, $campagne, $employe);

            return GlobalResource::make(['employes' => $collaborateurs,
                'unites' => 0, 'evaluations' => $evaluations,
                'salarierEvaluer' => $salarierEvaluer, 'performance' => $performance,
                'campagne' => $campagne
            ]);
        }

        if ($request->user()->hasRole('ROLE_RESPONSABLE') && $employe->respDepartement) {
            $collaborateurs = $this->collaborateurRespDepartement($employe);
            $services = DB::table('services')
                ->where('services.departement_id', $employe->departement->id)->where('services.id', '!=', $employe->fonction->service->id)->count();
            $evaluations = DB::table('evaluations')->where('employe_id', $employe->id)->count();
            $salarierEvaluer = $this->getSalaierEvaluer($request, $campagne, $employe);
            $performance = $this->getPerformanceRealiser($request, $campagne, $employe);

            return GlobalResource::make(['employes' => count($collaborateurs),
                'services' => $services, 'evaluations' => $evaluations,
                'salarierEvaluer' => $salarierEvaluer, 'performance' => $performance,
                'campagne' => $campagne
            ]);
        }

        if ($request->user()->hasRole('ROLE_DIRECTEUR') && $employe->isDirecteur && !$request->user()->hasRole('ROLE_CODI')) {
            $direction = $employe->direction;
            $collaborateurs = $this->collaboteurDirecteur($employe);
            $services = DB::table('services')
                ->where('services.direction_id', $employe->direction->id)->count();

            $salarierEvaluer = $this->getSalaierEvaluer($request, $campagne, $employe);
            $performance = $this->getPerformanceRealiser($request, $campagne, $employe);

            return GlobalResource::make(['employes' => count($collaborateurs), 'services' => $services,
                'salarierEvaluer' => $salarierEvaluer, 'performance' => $performance,
                'campagne' => $campagne
            ]);
        }
    }

    public function listeSalarierEvaluer(Request $request, $idAnnee, $type)
    {
        $campagne = CampagnePerformance::where('annee_id', $idAnnee)->first();
        $employe = Employe::where('email', $request->user()->email)->first();
        if ($campagne == null) {
            return GlobalResource::make(['code' => 500, 'message' => 'Aucune camapgne retrouvée pour l\'année choisie']);
        }

        if ($type == 'evaluer') {
            $liste = $this->getSalaierEvaluer($request, $campagne, $employe, false);
        } else {
            $req = "SELECT
                     empl.id,empl.matricule,empl.nom,empl.prenoms,unites.libelle as unite, 0 as performance
                    FROM
                     employes empl
                    INNER JOIN
                     unites on unites.id = empl.unite_id
                    WHERE empl.id not in (select emp.id
                                            from employes emp
                                            inner join evaluations eval on emp.id = eval.employe_id
                                            inner join fonctions f on f.id = emp.fonction_id
                                            inner join validations valid on valid.evaluation_id = eval.id
                                            where valid.niveau1='1' and valid.niveau2='1' and valid.niveau3='1'
                                            and valid.niveau4='1'and eval.clotureEvaluer='1' and eval.clotureResp='1'
                                            and eval.annee_id = 1 and emp.isDirecteur = '0'
                                            )
                    AND empl.isDirecteur = 0;";

            $liste = DB::select($req);
        }

        return GlobalResource::make(['code' => 200, 'listes' => $liste]);
    }

    public function listeServiceEvaluer($idAnnee, $type)
    {
        $campagne = CampagnePerformance::where('annee_id', $idAnnee)->first();
        if ($campagne == null) {
            return GlobalResource::make(['code' => 500, 'message' => 'Aucune camapgne retrouvée pour l\'année choisie']);
        }

        $liste = [];
        if ($type == 'evaluer') {
            $liste = $this->getServiceEvaluer($campagne, false);
        } else {
            $req = "SELECT serv.id,serv.code,serv.libelle
                    FROM services serv

                    WHERE serv.id not in(
                        SELECT TEMPORAIRE.idService
                        FROM
                            (SELECT idService,
                                (
                                select count(*) from employes empl
                                inner join fonctions fonct on fonct.id = empl.fonction_id
                                where empl.isDirecteur = 0
                                and fonct.service_id = TP.idService
                                ) AS NbrePService
                            FROM services,
                                        (
                                            SELECT count(*) as Nbre,f.service_id as idService from evaluations eval
                                            inner join employes empl on empl.id = eval.employe_id
                                            inner join fonctions f on f.id = empl.fonction_id
                                            inner join validations valid on valid.evaluation_id = eval.id
                                            where valid.niveau1='1' and valid.niveau2='1' and valid.niveau3='1'
                                            and valid.niveau4='1'and eval.clotureEvaluer='1' and eval.clotureResp='1'
                                            and eval.annee_id = 1
                                            group by f.service_id
                                        ) TP

                                        WHERE services.id = TP.idService
                                        and (
                                            select count(*) from employes empl
                                            inner join fonctions fonct on fonct.id = empl.fonction_id
                                            where empl.isDirecteur = 0
                                            and fonct.service_id = TP.idService
                                            ) - Nbre = 0
                                ) TEMPORAIRE

                    );";
            $datas = json_encode(DB::select($req));
            $datas = json_decode($datas, true);
            foreach ($datas as $key => $data) {
                $idService = $data['id'];
                $req = "select count(*) as nbreTotal,serv.id
                            from employes emp
                            inner join fonctions ft on ft.id = emp.fonction_id
                            inner join services serv on serv.id = ft.service_id
                            where serv.id = '$idService'
                            group by serv.id";
                $recordFull = DB::select($req);
                //dd($recordFull,$datas);

                $req1 = "select fct.service_id,services.libelle as service,
                        count(*) as nbreEvaluer
                        from employes
                        inner join evaluations evaluat on evaluat.employe_id=employes.id
                        inner join fonctions fct on fct.id = employes.fonction_id
                        inner join services on services.id =fct.service_id
                        where evaluat.clotureResp = '1'
                        and evaluat.annee_id = '1'
                        and services.id = '$idService'
                        group by fct.service_id,services.libelle";
                $record = DB::select($req1);

                $datas[$key]['nbreEvaluer'] = 0;
                $datas[$key]['nbreTotal'] = $recordFull[0]->nbreTotal;
                if (count($record)) {
                    $datas[$key]['nbreEvaluer'] = $record[0]->nbreEvaluer;
                }
                continue;
            }
            $liste = $datas;
        }


        return GlobalResource::make(['code' => 200, 'listes' => $liste]);
    }

    public function getAnnee()
    {
        $date = date('Y');
        $date_prev = date('Y') - 1;
        $annees = DB::table('annees')->whereIn('libelle', [$date, $date_prev])->get();

        return GlobalResource::collection(['annees' => $annees]);
    }

    public function getCollaborateurs(Request $request, $idUser, $option = 'user')
    {
        $user = $request->user();
        if ($option != 'user') {
            $resp = Employe::find($idUser);
            $user = User::where('email', $resp->email)->first();
            if ($user == null) {
                return GlobalResource::make([]);
            }
            $idUser = $user->id;
        }

        if ($user->id == $idUser) {
            $hasRole = false;
            $collaborateurs = array();
            foreach ($user->roles as $role) {
                if ($role->name == 'ROLE_RESPONSABLE') {
                    $hasRole = true;
                    break;
                }
            }
            $employe = Employe::where('email', $user->email)->first();
            if ($user->hasRole('ROLE_DIRECTEUR') && $employe->isDirecteur) {
                $collaborateurs = $this->collaboteurDirecteur($employe);
                if ($employe->direction->code == 'DU') {
                    $tabCollab = array();
                    /* inner join departements as depart on depart.id=serv.departement_id
                     * and emp.haveRespDepart=0
                    and emp.haveCollab=0*/
                    $direction = $employe->direction;
                    $sql = "SELECT emp.id, emp.haveComputer, emp.nom, emp.prenoms, emp.dateEmbauche,emp.email,emp.telephone,emp.matricule  from employes as emp
                        inner join fonctions as fonct on fonct.id = emp.fonction_id
                        inner join services as serv on serv.id = fonct.service_id
                        inner join directions as direct on direct.id=serv.direction_id
                        where direct.id =$direction->id
                        and emp.respService=1
                        and emp.id!=$employe->id
                        and emp.matricule in ('769','5606','452','445','496','429')";
                    foreach (DB::select($sql) as $item) {
                        array_push($collaborateurs, $item);
                    }
                    /*dd($collaborateurs);
                    array_push($tabCollab,$collaborateurs);
                    $collaborateurs = $tabCollab;*/
                }

            } else {
                $fonction = $employe->fonction;
                if ($hasRole && $employe->respUnite) {
                    $collaborateurs = Employe::where('unite_id', $employe->unite_id)->where('respUnite', false)->where('respService', false)->where('respDepartement', false)->where('isDirecteur', false)->get();
                }

                if ($hasRole && $employe->respService) {
                    $unites = Unite::where('service_id', $fonction->service_id)->get();
                    $tabRespUnite = array();
                    foreach ($unites as $unite) {
                        $responsablesUnite = Employe::where('unite_id', $unite->id)->where('respUnite', true)->where('respService', false)->where('respDepartement', false)->get();
                        if (count($responsablesUnite) > 0) {
                            foreach ($responsablesUnite as $item) {
                                array_push($tabRespUnite, $item);
                            }
                            continue;
                        } else {
                            $salariers = Employe::where('unite_id', $unite->id)->where('respUnite', false)->where('respService', false)->where('respDepartement', false)->where('isDirecteur', 0)->get();
                            foreach ($salariers as $salarier) {
                                array_push($tabRespUnite, $salarier);
                            }
                            continue;
                        }
                    }

                    $collaborateurs = $tabRespUnite;
                }

                if ($hasRole && $employe->respDepartement) {
                    $collaborateurs = $this->collaborateurRespDepartement($employe);
                }
            }

            return GlobalResource::make($collaborateurs);
        } else {
            return abort(403, 'Votre session est expirée');
        }

    }

    public function getObjectifsValider(Request $request, $idEvaluation, $from = 'param')
    {
        $campagne = CampagnePerformance::where('cloturer', false)->first();
        if ($from == 'param') {
            $employe = Employe::where('email', $request->user()->email)->first();
            if ($employe == '') {
                return GlobalResource::collection([]);
            }
            return GlobalResource::collection(Objectif::where('employe_id', $employe->id)->where('valider', true)->where('ignorer', false)->where('annee_id', $campagne->annee_id)->orderBy('created_at', 'desc')->get());
        } else {
            if ($from == 'autoEvaluationSalarieByResp' or $from == 'evaluationMiparcoursSalarieByResp') {
                $employe = Employe::find($idEvaluation);
                $idemploye = $employe->id;
            } else {
                $evaluation = Evaluation::find($idEvaluation);
                $idemploye = $evaluation->employe_id;
            }
            if ($idemploye == '' or $idemploye == null) {
                return GlobalResource::collection([]);
            }
            return GlobalResource::collection(Objectif::where('employe_id', $idemploye)->where('valider', true)->where('ignorer', false)->where('annee_id', $campagne->annee_id)->orderBy('created_at', 'desc')->get());
        }

    }

    public function getFormationForEvaluationEncours(Request $request, $idEvaluation, $from = '')
    {
        $campagne = CampagnePerformance::where('cloturer', false)->first();

        if ($idEvaluation == 0) {
            $employe = Employe::where('email', $request->user()->email)->first();
        } else {
            if ($from == 'autoEvaluationSalarieByResp') {
                $employe = Employe::find($idEvaluation);
            } else {
                $evaluation = Evaluation::find($idEvaluation);
                $employe = Employe::find($evaluation->employe_id);
            }

        }

        $sql = "select form.id,form.objectifVise,form.dateFormation,form.libelle from formations as form
                inner join formation_employe formEmpl on formEmpl.formation_id =form.id
                inner join employes on employes.id = formEmpl.employe_id
                where employes.id =$employe->id
                and form.annee_id=$campagne->annee_id;";
        $formation = DB::select($sql);

        return GlobalResource::make($formation);
    }

    public function getObjectifCollaborateur($idCollaborateur)
    {
        return GlobalResource::collection(Objectif::with('annee', 'employe')->where('employe_id', $idCollaborateur)->where('ignorer', false)->orderBy('created_at', 'DESC')->get());
    }

    public function getMissionCollaborateur($idCollaborateur)
    {
        return GlobalResource::collection(Mission::with('annee')->where('employe_id', $idCollaborateur)->orderBy('created_at', 'desc')->get());
    }

    public function getFormationSuivieCollaborateur($idCollaborateur)
    {
        $req = "select form.libelle, form.objectifVise, form.id, annees.libelle as annee, suivre.appreciation from formations form
                inner join annees on form.annee_id = annees.id
                inner join suivre on form.id = suivre.formation_id
                inner join evaluations eval on eval.id = suivre.evaluation_id
                inner join employes empl on empl.id = eval.employe_id
                where empl.id = $idCollaborateur";

        return GlobalResource::make(DB::select($req));
    }

    public function getCritereEvaluationEmploye($idCollaborateur, $idEvaluation, $from = '')
    {
        if ($idEvaluation == 0) {
            if ($from == 'autoEvaluationSalarieByResp') {
                $employe = Employe::find($idCollaborateur);
            } else {
                $user = User::find($idCollaborateur);
                $employe = Employe::where('email', $user->email)->first();
            }

        } else {
            $evaluation = Evaluation::find($idEvaluation);
            $employe = Employe::find($evaluation->employe_id);
        }
        $categorie = CategorieProfessionnelle::with('categorie')->find($employe->categorie_professionnelle_id);
        //on utilise les critères d'évaluation des agents de maitrise pour les agents d'exécution
        if ($categorie->categorie->code == 'AE' or $categorie->categorie->code == 'E') {
            $categorie = CategorieProfessionnelle::where('code', 'M1')->first();
        }
        $req = "select crit.id, crit.libelle,cateCrit.libelle as categorieCritere,trim(cateCrit.code)as codeCategorie from critere_evaluations crit
                inner join categorie_employes cateE
                on cateE.id = crit.categorie_employe_id
                inner join categorie_criteres cateCrit
                on cateCrit.id = crit.categorie_critere_id
                where cateE.id =$categorie->categorie_employe_id
                group by crit.id, crit.libelle,cateCrit.libelle,cateCrit.code ";
        $datas = DB::select($req);

        $tabData = [];
        $criteres = [];
        foreach ($datas as $value) {
            $tab1 = [];
            $tab2 = [];
            $tabData = [];
            foreach ($datas as $item) {
                if (!in_array($value->codeCategorie, $tab2)) {
                    if ($value->codeCategorie == $item->codeCategorie) {
                        array_push($tab1, $item);
                    }
                }
            }
            array_push($tab2, $value->codeCategorie);
            $tabData[$value->categorieCritere] = $tab1;
            $criteres[$value->codeCategorie] = $tabData;

        }
        $req = "select * from critere_evaluations crit where crit.categorie_employe_id=1";
        $tabCritere = DB::select($req);

        return GlobalResource::make(['criteresFormater' => $criteres, 'tabCritere' => $tabCritere]);
    }

    public function getAnnees()
    {
        return GlobalResource::collection(Annee::all());
    }

    public function getServiceDepartement(Request $request, $depart = 'false')
    {
        $employe = Employe::where('email', $request->user()->email)->first();

        if ($depart == 'false') {
            //dd($employe,$employe->departement);
            //->where('services.id', '!=', $employe->fonction->service->id)
            $services = DB::table('services')
                ->where('services.departement_id', $employe->departement->id)
                ->get();
        } else {
            $services = DB::table('services')
                ->where('services.direction_id', $employe->direction->id)
                ->get();
        }


        return GlobalResource::make(['code' => 200, 'services' => $services]);
    }

    public function getPerformanceGlobale()
    {
        $dataTogetBenificiaire = $this->mouchardApp->dataTogetBenificiaire();
        $campagne = $dataTogetBenificiaire['campagne'];

        if ($campagne == '' or $campagne == null) {
            return GlobalResource::make(['code' => 403, 'message' => 'Aucune campagne d\'évaluation lancée par les RH']);
        }
        $typeEvaluation = TypeEvaluation::where('code', 'EA')->first();
        $idTypeEva = $typeEvaluation->id;
        $idAnnee = $campagne->annee_id;
        $idtypeAgent = '';
        $services = Service::all();
        $categories = CategorieEmploye::all();
        $datas = array();
        //$cateEmploye = CategorieEmploye::where('code', $idtypeAgent)->first();
        $totalPerforamnceCateEmplAC = 0;
        $totalPerforamnceCateEmplAM = 0;
        $moyenneService = 0;
        $someNoteSalarie = 0;
        $sommeNotes = 0;
        $performanceUsine = 0;
        $effectifEvalue = 0;
        $moyenneServiceApresPonde = 0;
        $cadres = [];
        $maitrises = [];
        $executions = [];
        foreach ($categories as $category) {
            $req = "select distinct empl.id,empl.matricule, empl.nom, empl.prenoms, serv.libelle as service,eval.id as idEval,
                 eval.performanceRealiser as performanceRealiser, eval.performanceFinal as performanceFinal, eval.notePondere as notePondere,fonct.libelle as poste,
                cateProf.code as cateprofe
                from evaluations eval
                inner join type_evaluations typeEva on eval.type_evaluation_id=typeEva.id
                inner join employes empl on eval.employe_id = empl.id
                inner join fonctions fonct on empl.fonction_id=fonct.id
                inner join services serv on fonct.service_id = serv.id
				inner join annees on eval.annee_id = annees.id
				inner join validations val on val.evaluation_id = eval.id
				inner join categorie_professionnelles as cateProf on cateProf.id = empl.categorie_professionnelle_id
				inner join categorie_employes cateEmpl on cateEmpl.id = cateProf.categorie_employe_id
                where cateEmpl.id = '$category->id'
                and annees.id = $idAnnee
                and typeEva.id = $idTypeEva
                and eval.transmis = 1
                and val.niveau1 = 1 and val.niveau2 = 1 and val.niveau3 = 1
                and val.niveau4=1 and eval.clotureResp=1
                 ";
            /*if ($cateEmploye->code == 'AC') {
                $req .= " and cateEmpl.code ='AC'";
            } else {
                $req .= " and (cateEmpl.code ='AM' or cateEmpl.code='AE')";
            }*/
            $liste = DB::select($req);
            /*if ($cateEmploye->code != 'AC') {

            } else {
                if ($liste != '' && $liste != null && count($liste) != 0) {
                    foreach ($liste as $item) {
                        $totalPerforamnceCateEmplAC += $item->performanceFinal;
                    }
                    array_push($datas, $liste);
                }

            }*/

            if (count($liste) == 0) {
                continue;
            }

            if ($liste != '' && $liste != null && count($liste) != 0) {
                $tab1 = array();
                $tabTempons = json_decode(json_encode($liste), true);
                foreach ($tabTempons as $key => $item) {

                    $tabTempons[$key]['performanceRealiser'] = round($item['performanceRealiser'], 2);
                    $tabTempons[$key]['performanceFinal'] = round($item['performanceFinal'], 2);
                    $tabTempons[$key]['notePondere'] = round($item['notePondere'], 2);

                    $someNoteSalarie += $item['performanceFinal'];
                    $note = (int)$item['performanceFinal'];
                    $sql = "select * from niveau_performances where $note between borneInf and borneSup";
                    $niveauPerf = '-';
                    if (count(DB::select($sql)) > 0) {
                        $niveauPerf = DB::select($sql)[0]->libelle;
                    }
                    $tabTempons[$key]['benieficiaire'] = false;
                    $tabTempons[$key]['havePrimeExcept'] = false;
                    $tabTempons[$key]['niveauPerf'] = $niveauPerf;
                    $tabTempons[$key]['niveauPerfApresPonde'] = $niveauPerf;

                }

                $effectifEvalue += count($tabTempons);
                $moyenne = 0;
                if (count($tabTempons) > 0)
                    $moyenne = round($someNoteSalarie / count($tabTempons), 2);

                $tab1['' . $moyenne . ''] = $tabTempons;
                $datas[$category->code] = $tab1;

                $sommeNotes += $someNoteSalarie;
                $someNoteSalarie = 0;
                $moyenneService = $moyenneServiceApresPonde = 0;
            }
        }

        if ($effectifEvalue > 0)
            $performanceUsine = round($sommeNotes / $effectifEvalue, 2);

        $idAnnee = $dataTogetBenificiaire['idAnnee'];
        $categorieCadre = $dataTogetBenificiaire['categorieCadre'];
        $categorieMaitrise = $dataTogetBenificiaire['categorieMaitrise'];
        $categorieExecutant = $dataTogetBenificiaire['categorieExecutant'];
        $typeEvaluation = $dataTogetBenificiaire['typeEvaluation'];
        $niveauperfA = $dataTogetBenificiaire['niveauperfA'];
        $niveauperfB = $dataTogetBenificiaire['niveauperfB'];

        $reqCadre = $this->mouchardApp->nbreEvaluerParCategorie($idAnnee, $categorieCadre->id, $typeEvaluation->id);
        $reqMaitrise = $this->mouchardApp->nbreEvaluerParCategorie($idAnnee, $categorieMaitrise->id, $typeEvaluation->id);
        $reqExecutant = $this->mouchardApp->nbreEvaluerParCategorie($idAnnee, $categorieExecutant->id, $typeEvaluation->id);

        $nbrCadreBeneficiaire = 0;
        if (count($reqCadre) > 0) {
            $nbrCadreBeneficiaire = (int)round(($reqCadre[0]->nombreCadreEvaluer * 30) / 100);
        }

        $nbrMaitriseBeneficiaire = 0;
        if (count($reqMaitrise) > 0) {
            $nbrMaitriseBeneficiaire = (int)round(($reqMaitrise[0]->nombreCadreEvaluer * 30) / 100);
        }

        $nbrExecutantBeneficiaire = 0;
        if (count($reqExecutant) > 0) {
            $nbrExecutantBeneficiaire = (int)round(($reqExecutant[0]->nombreCadreEvaluer * 30) / 100);
        }

        $listeCadresBeneficiares = json_decode(json_encode($this->mouchardApp->listeBeneficiaires($nbrCadreBeneficiaire, $idAnnee, $categorieCadre->id, $typeEvaluation->id, $niveauperfA, $niveauperfB)), true);
        $listeMaitrisesBeneficiares = json_decode(json_encode($this->mouchardApp->listeBeneficiaires($nbrMaitriseBeneficiaire, $idAnnee, $categorieMaitrise->id, $typeEvaluation->id, $niveauperfA, $niveauperfB)), true);
        $listeExecutantsBeneficiares = json_decode(json_encode($this->mouchardApp->listeBeneficiaires($nbrExecutantBeneficiaire, $idAnnee, $categorieExecutant->id, $typeEvaluation->id, $niveauperfA, $niveauperfB)), true);
        $listeBeneficiairePrimeExeptionnelles = LaureatEvaluation::where('isPrimeExcept', true)->where('annee_id', $idAnnee)->get();

        DB::beginTransaction();

        foreach ($listeCadresBeneficiares as $cadreBeneficiare) {
            foreach ($datas['AC'] as $key1 => $values) {
                foreach ($values as $key2 => $value) {
                    if ($cadreBeneficiare['matricule'] == $value['matricule']) {
                        $datas['AC'][$key1][$key2]['beneficiaire'] = true;
                        break;
                    }

                }
                if ($datas['AC'][$key1][$key2]['beneficiaire'] == true) {
                    break;
                }
            }
            $laureatSave = LaureatEvaluation::where('matricule', $cadreBeneficiare['matricule'])->first();
            $categorieProf = CategorieProfessionnelle::where('code', $cadreBeneficiare['categorie'])->first();
            if ($laureatSave == '' || $laureatSave == null) {
                LaureatEvaluation::create([
                    'matricule' => $cadreBeneficiare['matricule'],
                    'salarie' => $cadreBeneficiare['salarie'],
                    'poste' => $cadreBeneficiare['poste'],
                    'categorie' => $cadreBeneficiare['categorie'],
                    'smc' => $categorieProf->smc,
                    'notePonderer' => (float)$cadreBeneficiare['performanceFinal'],
                    'perfApresPondereration' => $cadreBeneficiare['niveauPerformanceApresPonderation'],
                    'annee_id' => $idAnnee
                ]);
            }
            continue;

        }

        foreach ($listeMaitrisesBeneficiares as $matriseBeneficiare) {
            foreach ($datas['AM'] as $key1 => $values) {
                foreach ($values as $key2 => $value) {
                    if ($matriseBeneficiare['matricule'] == $value['matricule']) {
                        $datas['AM'][$key1][$key2]['beneficiaire'] = true;
                        break;
                    }

                }
                if ($datas['AM'][$key1][$key2]['beneficiaire'] == true) {
                    break;
                }
            }
            $laureatSave = LaureatEvaluation::where('matricule', $matriseBeneficiare['matricule'])->first();
            $categorieProf = CategorieProfessionnelle::where('code', $matriseBeneficiare['categorie'])->first();
            if ($laureatSave == '' or $laureatSave == null) {
                LaureatEvaluation::create([
                    'matricule' => $matriseBeneficiare['matricule'],
                    'salarie' => $matriseBeneficiare['salarie'],
                    'poste' => $matriseBeneficiare['poste'],
                    'categorie' => $matriseBeneficiare['categorie'],
                    'smc' => $categorieProf->smc,
                    'notePonderer' => (float)$matriseBeneficiare['performanceFinal'],
                    'perfApresPondereration' => $matriseBeneficiare['niveauPerformanceApresPonderation'],
                    'annee_id' => $idAnnee
                ]);
            }
            continue;

        }

        foreach ($listeExecutantsBeneficiares as $executantBeneficiare) {
            foreach ($datas['AE'] as $key1 => $values) {
                foreach ($values as $key2 => $value) {
                    if ($executantBeneficiare['matricule'] == $value['matricule']) {
                        $datas['AE'][$key1][$key2]['beneficiaire'] = true;
                        break;
                    }

                }
                if ($datas['AE'][$key1][$key2]['beneficiaire'] == true) {
                    break;
                }
            }
            $laureatSave = LaureatEvaluation::where('matricule', $executantBeneficiare['matricule'])->first();
            $categorieProf = CategorieProfessionnelle::where('code', $executantBeneficiare['categorie'])->first();
            if ($laureatSave == null or $laureatSave == '') {
                LaureatEvaluation::create([
                    'matricule' => $executantBeneficiare['matricule'],
                    'salarie' => $executantBeneficiare['salarie'],
                    'poste' => $executantBeneficiare['poste'],
                    'categorie' => $executantBeneficiare['categorie'],
                    'smc' => $categorieProf->smc,
                    'notePonderer' => (float)$executantBeneficiare['performanceFinal'],
                    'perfApresPondereration' => $executantBeneficiare['niveauPerformanceApresPonderation'],
                    'annee_id' => $idAnnee
                ]);
            }
            continue;

        }

        foreach ($listeBeneficiairePrimeExeptionnelles as $beneficiairePrimeExept) {
            $category = CategorieProfessionnelle::where('code', $beneficiairePrimeExept['categorie'])->first();
            if ($category->categorie->code == 'AE') {
                foreach ($datas['AE'] as $key1 => $values) {
                    foreach ($values as $key2 => $value) {
                        if ($beneficiairePrimeExept->matricule == $value['matricule']) {
                            $datas['AE'][$key1][$key2]['havePrimeExcept'] = true;
                            break;
                        }

                    }
                    if ($datas['AE'][$key1][$key2]['havePrimeExcept']) {
                        break;
                    }
                }
            }

            if ($category->categorie->code == 'AM') {
                foreach ($datas['AM'] as $key1 => $values) {
                    foreach ($values as $key2 => $value) {
                        if ($beneficiairePrimeExept->matricule == $value['matricule']) {
                            $datas['AM'][$key1][$key2]['havePrimeExcept'] = true;
                            break;
                        }

                    }
                    if ($datas['AM'][$key1][$key2]['havePrimeExcept'] == true) {
                        break;
                    }
                }
            }

            if ($category->categorie->code == 'AC') {
                foreach ($datas['AC'] as $key1 => $values) {
                    foreach ($values as $key2 => $value) {
                        if ($beneficiairePrimeExept->matricule == $value['matricule']) {
                            $datas['AC'][$key1][$key2]['havePrimeExcept'] = true;
                            break;
                        }

                    }
                    if ($datas['AC'][$key1][$key2]['havePrimeExcept'] == true) {
                        break;
                    }
                }
            }


        }

        DB::commit();
        $listeBeneficiaireValider = false;
        $verificationClotureListeBeneficiaire = LaureatEvaluation::where('valider', true)->get();
        if (count($verificationClotureListeBeneficiaire) > 0) {
            $listeBeneficiaireValider = true;
        }

        return [
            'code' => 200,
            'liste' => $datas, 'moyenneService' => $moyenneService,
            'moyenneServiceApresPonde' => $moyenneServiceApresPonde,
            'performanceUsine' => $performanceUsine,
            'listeBeneficiaireValider' => $listeBeneficiaireValider,
            'annee' => $dataTogetBenificiaire['campagne']->annee->libelle
        ];
    }

    public function getPerformanceService($service_id)
    {
        $campagne = CampagnePerformance::where('cloturer', false)->first();
        if ($campagne == '' or $campagne == null) {
            return response()->json(['code' => 403, 'message' => 'Aucune campagne d\'évaluation lancée par les RH'], 500);
        }
        $idAnnee = $campagne->annee_id;
        $service = Service::find($service_id);
        $datas = array();
        $tabTempons = array();
        $someNoteSalarie = 0;
        $sommeNotes = 0;
        $moyenneService = 0;
        $performanceUsine = 0;
        $performanceInitialUsine = 0;
        $typeEvaluation = TypeEvaluation::where('code', 'EA')->first();
        $idTypeEva = $typeEvaluation->id;

        $req = "select distinct empl.id as idEmpl,empl.matricule, empl.nom, empl.prenoms, serv.libelle as service,eval.id,
                 round(eval.performanceRealiser,2) as performanceRealiser, round(eval.performanceFinal,2) as performanceFinal, round(eval.notePondere,2) as notePondere,fonct.libelle as poste,
                cateProf.code as cateprofe,eval.clotureCodi
                from evaluations eval
                inner join type_evaluations typeEva on eval.type_evaluation_id=typeEva.id
                inner join employes empl on eval.employe_id = empl.id
                inner join fonctions fonct on empl.fonction_id=fonct.id
                inner join services serv on fonct.service_id = serv.id
				inner join annees on eval.annee_id = annees.id
				inner join validations val on val.evaluation_id = eval.id
				inner join categorie_professionnelles as cateProf on cateProf.id = empl.categorie_professionnelle_id
				inner join categorie_employes cateEmpl on cateEmpl.id = cateProf.categorie_employe_id
                where serv.id = $service_id
                and annees.id = $idAnnee
                and typeEva.id = $idTypeEva
                and eval.transmis = 1
                and val.niveau1 = 1 and val.niveau2 = 1 and val.niveau3 = 1
                and val.niveau4=1 and eval.clotureResp=1";

        $liste = DB::select($req);

        if ($liste != '' && $liste != null && count($liste) != 0) {
            $tab1 = array();
            $tabTempons = json_decode(json_encode($liste), true);
            foreach ($tabTempons as $key => $item) {
                $tabTempons[$key]['performanceRealiser'] = round($item['performanceRealiser'], 2);
                $tabTempons[$key]['performanceFinal'] = round($item['performanceFinal'], 2);
                $tabTempons[$key]['notePondere'] = round($item['notePondere'], 2);

                $someNoteSalarie += $item['performanceFinal'];
                $note = (int)$item['performanceRealiser'];
                $sql = "select * from niveau_performances where $note between borneInf and borneSup";
                $niveauPerf = '-';
                if (count(DB::select($sql)) > 0) {
                    $niveauPerf = DB::select($sql)[0]->libelle;
                }
                $tabTempons[$key]['niveauPerf'] = $niveauPerf;

                $note = (int)$item['performanceFinal'];
                $sql = "select * from niveau_performances where $note between borneInf and borneSup";
                $niveauPerfAprPond = '-';
                if (count(DB::select($sql)) > 0) {
                    $niveauPerfAprPond = DB::select($sql)[0]->libelle;
                }
                $tabTempons[$key]['niveauPerfApresPonde'] = $niveauPerfAprPond;

            }

            $moyenneService = round($someNoteSalarie / count($tabTempons), 2);
            //$tab1[''.$moyenneService.''] = $tabTempons;
            //$datas[$service->libelle] = $tab1;

        }

        /* $sql = "select sum(eval.performanceFinal) as somme, count(*) as nbre,  avg(distinct eval.performanceFinal) as performanceFinal, avg(distinct eval.performanceRealiser) as performanceRealiser
                 from evaluations as eval
                 inner join type_evaluations typeEva on eval.type_evaluation_id=typeEva.id
                 inner join employes empl on eval.employe_id = empl.id
                 inner join fonctions fonct on empl.fonction_id=fonct.id
                 inner join services serv on fonct.service_id = serv.id
                 inner join unites unit on unit.service_id = serv.id
                 inner join annees on annees.id=eval.annee_id
                 inner join validations val on val.evaluation_id = eval.id
                 where annees.id = $idAnnee
                 and typeEva.id = $idTypeEva
                 and eval.transmis =1
                 and val.niveau1 = 1 and val.niveau2 = 1 and val.niveau3 = 1
                 and val.niveau4=1 and eval.clotureResp=1
                 ";*/

        $sql = "select distinct empl.id as idEmpl,empl.matricule, empl.nom, empl.prenoms, serv.libelle as service,eval.id,
                 round(eval.performanceRealiser,2) as performanceRealiser, round(eval.performanceFinal,2) as performanceFinal, round(eval.notePondere,2) as notePondere,fonct.libelle as poste,
                cateProf.code as cateprofe,eval.clotureCodi
                from evaluations eval
                inner join type_evaluations typeEva on eval.type_evaluation_id=typeEva.id
                inner join employes empl on eval.employe_id = empl.id
                inner join fonctions fonct on empl.fonction_id=fonct.id
                inner join services serv on fonct.service_id = serv.id
				inner join annees on eval.annee_id = annees.id
				inner join validations val on val.evaluation_id = eval.id
				inner join categorie_professionnelles as cateProf on cateProf.id = empl.categorie_professionnelle_id
				inner join categorie_employes cateEmpl on cateEmpl.id = cateProf.categorie_employe_id
                where annees.id = $idAnnee
                and typeEva.id = $idTypeEva
                and eval.transmis = 1
                and val.niveau1 = 1 and val.niveau2 = 1 and val.niveau3 = 1
                and val.niveau4=1 and eval.clotureResp=1";

        if (count(DB::select($sql)) > 0) {
            $datas = json_decode(json_encode(DB::select($sql)), true);
            $sommePerf = 0;
            $sommePerfInitiale = 0;
            foreach ($datas as $key => $item) {
                $sommePerf += $item['performanceFinal'];
                $sommePerfInitiale += $item['performanceRealiser'];
            }
            $performanceUsine = round($sommePerf / count($datas), 2);
            $performanceInitialUsine = round($sommePerfInitiale / count($datas), 2);
        }

        return [
            'code' => 200,
            'evaluations' => $tabTempons,
            'performanceInitialUsine' => $performanceInitialUsine,
            'performanceUsine' => $performanceUsine,
            'moyenneService' => $moyenneService,
        ];
    }

    public function getEmployeByService(Request $request)
    {
        $idAnnee = $campagne->annee_id;
        $sql = "SELECT distinct emp.id, emp.haveComputer, emp.nom, emp.prenoms, emp.dateEmbauche,emp.email,emp.telephone,emp.matricule  from employes as emp
                        inner join fonctions as fonct on fonct.id = emp.fonction_id
                        inner join services as serv on serv.id = fonct.service_id
                        where serv.id  = $idService
                        and emp.isDirecteur =0";
    }

    public function getCollaborateurService(Request $request, $idService, $idTypeEva = 0)
    {
        $campagne = CampagnePerformance::where('cloturer', false)
            ->where('type_evaluation_id', $idTypeEva)
            ->first();
        if ($campagne == null or $campagne == '') {
            return GlobalResource::make(['code' => 500, 'message' => "La campagne de ce type d'évaluation est cloturée"]);
        }
        if ($idTypeEva == 0) {
            $typeEvaluation = TypeEvaluation::where('code', 'EA')->first();
            $idTypeEva = $typeEvaluation->id;
        } else {
            $typeEvaluation = TypeEvaluation::find($idTypeEva);
        }

        if ($typeEvaluation->code == 'EA') {
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
        }
        $idAnnee = $campagne->annee_id;
        $sql = "SELECT distinct emp.id, emp.haveComputer, emp.nom, emp.prenoms, emp.dateEmbauche,emp.email,emp.telephone,emp.matricule  from employes as emp
                        inner join fonctions as fonct on fonct.id = emp.fonction_id
                        inner join services as serv on serv.id = fonct.service_id
                        where serv.id  = $idService
                        and emp.isDirecteur =0";

        $responsable = Employe::where('email', $request->user()->email)->first();

        $req1 = "select distinct empl.matricule, empl.haveComputer, empl.nom, empl.prenoms, serv.libelle as unite, eval.id as idEval, eval.performanceRealiser as performance
                from evaluations eval
                inner join employes empl on eval.employe_id = empl.id
                inner join fonctions fonct on empl.fonction_id=fonct.id
                inner join services serv on fonct.service_id = serv.id
                inner join unites unit	on unit.service_id = serv.id
				inner join annees on eval.annee_id = annees.id
                inner join type_evaluations typeEva on typeEva.id=eval.type_evaluation_id
                ";

        if ($typeEvaluation->code == 'EA') {
            $req1 .= " inner join validations val on val.evaluation_id = eval.id";
        }

        $req1 .= " where serv.id = $idService
                and empl.id != $responsable->id
                and annees.id = $idAnnee
                and typeEva.id = $idTypeEva ";

        if ($typeEvaluation->code == 'EA') {
            if ($responsable->respDepartement) {
                $req1 .= " and val.niveau1 = 1 and val.niveau2 = 1 and val.niveau3 = 0 ";
            }

            $service = Service::find($idService);
            if ($responsable->isDirecteur && $service->departement_id != null) {
                $req1 .= "  and val.niveau1 = 1 and val.niveau2 = 1 and val.niveau3 = 1 and val.niveau4 = 0";
            } else {
                if ($responsable->isDirecteur && $service->departement_id == null) {
                    $req1 .= "  and val.niveau1 = 1 and val.niveau2 = 1 and val.niveau3 = 1 and val.niveau4 = 0";
                }
            }
        }

        $evaluations = json_decode(json_encode(DB::select($req1)), true);

        foreach ($evaluations as $key => $evaluation) {
            $evaluations[$key]['performance'] = round($evaluation['performance'], 2);
        }

        $statisticService = $this->mouchardApp->getStatisticService($responsable, $idService, $idAnnee);

        return GlobalResource::make(['code' => 200, 'salaries' => DB::select($sql), 'evaluations' => $evaluations, 'statisticService' => $statisticService]);
    }

    private function collaborateurRespDepartement($employe, $code = null)
    {
        if (!$employe->fonction->service->direction->code == 'DU') {
            $departement_id = $employe->direction->id;
            $sql = "SELECT emp.id, emp.haveComputer, emp.nom, emp.prenoms, emp.dateEmbauche,emp.email,emp.telephone,emp.matricule  from employes as emp
                        inner join fonctions as fonct on fonct.id = emp.fonction_id
                        inner join services as serv on serv.id = fonct.service_id
                        inner join directions as direct on direct.id=serv.direction_id
                        and direct.id = $departement_id
                        and emp.id !=$employe->id";
        } else {
            /*  where emp.respService  = 1*/
            $pole_id = $employe->departement->id;
            $sql = "SELECT emp.id, emp.haveComputer, emp.nom, emp.prenoms, emp.dateEmbauche,emp.email,emp.telephone,emp.matricule  from employes as emp
                        inner join fonctions as fonct on fonct.id = emp.fonction_id
                        inner join services as serv on serv.id = fonct.service_id
                        inner join departements as depart on depart.id=serv.departement_id
                        where depart.id = $pole_id
                        and emp.respService = 1
                        and emp.id !=$employe->id";
        }
        if (!is_null($code)) {
            $sql .= " and emp.matricule = $code";
        }

        return DB::select($sql);
    }

    private function collaboteurDirecteur($employe, $code = null)
    {
        $direction = $employe->direction;

        /*where emp.respDepartement  = 1*/
        if ($employe->direction->code == 'DU') {
            $sql = "SELECT emp.id, emp.haveComputer, emp.nom, emp.prenoms, emp.dateEmbauche,emp.email,emp.telephone,emp.matricule  from employes as emp
                        inner join fonctions as fonct on fonct.id = emp.fonction_id
                        inner join services as serv on serv.id = fonct.service_id
                        inner join departements as depart on depart.id=serv.departement_id
                        inner join directions as direct on direct.id=depart.direction_id
                        where direct.id =$direction->id
                        and emp.isDirecteur = 0
                        and emp.respDepartement = 1";
        } else {
            /* where emp.respService  = 1*/
            $sql = "SELECT emp.id, emp.haveComputer,emp.nom, emp.prenoms, emp.dateEmbauche,emp.email,emp.telephone,emp.matricule  from employes as emp
                        inner join fonctions as fonct on fonct.id = emp.fonction_id
                        inner join services as serv on serv.id = fonct.service_id
                        inner join directions as direct on direct.id=serv.direction_id
                        where direct.id =$direction->id
                        and emp.respService  = 1";
        }

        if (!is_null($code)) {
            $sql .= " and emp.matricule = $code";
        }
        return DB::select($sql);
    }

    private function getSalaierEvaluer($request, $campagne, $employe, $count = true)
    {
        if ($count) {
            $req = "select count(*) as nbreEvaluer ";
        } else {
            $req = "select empl.nom,empl.prenoms,empl.matricule,unites.libelle as unite,
            eval.performanceRealiser as performance ";
        }

        $req .= " from employes empl
                inner join evaluations eval on eval.employe_id = empl.id
                inner join validations valid on valid.evaluation_id = eval.id
                inner join annees  on annees.id = eval.annee_id  ";

        if ($request->user()->hasRole('ROLE_ADMIN') or $request->user()->hasRole('ROLE_CODI')) {
            $req .= "  inner join unites on unites.id = empl.unite_id
                where valid.niveau1=1 and valid.niveau2=1 and valid.niveau3=1
                and valid.niveau4=1 and eval.clotureEvaluer=1 and eval.clotureResp=1
              ";
        }

        if ($request->user()->hasRole('ROLE_RESPONSABLE') and $employe->respUnite) {
            $service = $employe->fonction->service;
            $req .= " inner join fonctions fonct on fonct.id = empl.fonction_id
                     inner join services serv on serv.id = fonct.service_id
                    where valid.niveau1=1
                    and eval.clotureEvaluer=1 and empl.id != $employe->id
                    and serv.id =$service->id";
        }

        if ($request->user()->hasRole('ROLE_RESPONSABLE') and $employe->respService and !$request->user()->hasRole('ROLE_ADMIN')) {
            $service = $employe->fonction->service;
            $req .= " inner join fonctions fonct on fonct.id = empl.fonction_id
                     inner join services serv on serv.id = fonct.service_id
                    where valid.niveau1=1 and valid.niveau2=1
                    and eval.clotureEvaluer=1 and empl.id != $employe->id
                    and serv.id =$service->id";
        }

        if ($request->user()->hasRole('ROLE_RESPONSABLE') and $employe->respDepartement) {
            $pole_id = $employe->departement->id;
            $req .= "  inner join fonctions as fonct on fonct.id = empl.fonction_id
                inner join services as serv on serv.id = fonct.service_id
                inner join departements as depart on depart.id=serv.departement_id
                where valid.niveau1=1 and valid.niveau2=1 and valid.niveau3=1
                and eval.clotureEvaluer=1 and empl.id != $employe->id
                and depart.id = $pole_id";
        }

        if ($request->user()->hasRole('ROLE_DIRECTEUR') and $employe->isDirecteur and !$request->user()->hasRole('ROLE_CODI')) {
            $direction = $employe->direction;

            $req .= "  inner join fonctions fonct on fonct.id = empl.fonction_id
                    inner join services serv on serv.id = fonct.service_id
                    inner join directions direct on direct.id = serv.direction_id
                    where valid.niveau1=1 and valid.niveau2=1 and valid.niveau3=1
                    and valid.niveau4=1 and eval.clotureEvaluer=1 and empl.id != $employe->id
                    and direct.id = $direction->id ";
        }

        $req .= "  and annees.id = $campagne->annee_id
                 and empl.isDirecteur =0";

        $salariers = DB::select($req);
        if ($count) {
            $salariers = ($salariers[0]->nbreEvaluer == null) ? 0 : $salariers[0]->nbreEvaluer;
        }

        return $salariers;

    }

    private function getServiceEvaluer($campagne, $count = false)
    {
        $req = "SELECT
                    libelle,
                    nbreEvaluer,
                     --cette requete envoie la liste contenant le nombre de personne présente dans chaque service évalué
                     (  select count(*) from employes empl
                        inner join fonctions fonct on fonct.id = empl.fonction_id
                        where empl.isDirecteur = 0
                        and fonct.service_id = TP.service_id
                    ) AS nbreTotal
                FROM services,
                      --cette requete envoie la liste contenant le nombre de personnes evaluée par service
                        (   select count(*) AS nbreEvaluer,f.service_id from evaluations eval
                            inner join employes empl on empl.id = eval.employe_id
                            inner join fonctions f on f.id = empl.fonction_id
                            inner join validations valid on valid.evaluation_id = eval.id
                            where valid.niveau1='1' and valid.niveau2='1' and valid.niveau3='1'
                            and valid.niveau4='1'and eval.clotureEvaluer='1' and eval.clotureResp='1'
                            and eval.annee_id = $campagne->annee_id
                            group by f.service_id
                        )  TP
                WHERE services.id = TP.service_id
                --ce bloc faire une soustration entre le nombre total de personne présente dans le service évalué et le nombre de personne évaluée
                AND
                    (   select count(*) from employes empl
                        inner join fonctions fonct on fonct.id = empl.fonction_id
                        where empl.isDirecteur = 0
                        and fonct.service_id = TP.service_id
                    ) - nbreEvaluer = 0";

        $servicesEvaluer = DB::select($req);
        if ($count) {
            $servicesEvaluer = count($servicesEvaluer);
        }
        return $servicesEvaluer;
    }

    private function getPerformanceRealiser($request, $campagne, $employe)
    {
        $req = "select avg(eval.performanceFinal) as performanceUsine from evaluations eval
              inner join employes empl on empl.id = eval.employe_id
              inner join validations valid on valid.evaluation_id = eval.id";

        if ($request->user()->hasRole('ROLE_ADMIN') or $request->user()->hasRole('ROLE_CODI')) {
            $req .= " where valid.niveau1=1 and valid.niveau2=1 and valid.niveau3=1
              and valid.niveau4=1  and eval.clotureResp=1 ";
        }

        if ($request->user()->hasRole('ROLE_RESPONSABLE') and $employe->respUnite) {
            $service = $employe->fonction->service;
            $req .= " inner join fonctions fonct on fonct.id = empl.fonction_id
                     inner join services serv on serv.id = fonct.service_id
                    where valid.niveau1=1
                    and eval.clotureEvaluer=1 and empl.id != $employe->id
                    and serv.id =$service->id";
        }
        if ($request->user()->hasRole('ROLE_RESPONSABLE') and $employe->respService and !$request->user()->hasRole('ROLE_ADMIN')) {
            $service = $employe->fonction->service;
            $req .= " inner join fonctions fonct on fonct.id = empl.fonction_id
                     inner join services serv on serv.id = fonct.service_id
                    where valid.niveau1=1 and valid.niveau2=1
                    and eval.clotureEvaluer=1 and empl.id != $employe->id
                    and serv.id =$service->id";
        }

        if ($request->user()->hasRole('ROLE_RESPONSABLE') and $employe->respDepartement) {
            $pole_id = $employe->departement->id;
            $req .= "  inner join fonctions as fonct on fonct.id = empl.fonction_id
                inner join services as serv on serv.id = fonct.service_id
                inner join departements as depart on depart.id=serv.departement_id
                where valid.niveau1=1 and valid.niveau2=1 and valid.niveau3=1
                and eval.clotureEvaluer=1 and empl.id != $employe->id
                and depart.id = $pole_id";
        }

        if ($request->user()->hasRole('ROLE_DIRECTEUR') and $employe->isDirecteur and !$request->user()->hasRole('ROLE_CODI')) {
            $direction = $employe->direction;
            $req .= "  inner join fonctions fonct on fonct.id = empl.fonction_id
                    inner join services serv on serv.id = fonct.service_id
                    inner join directions direct on direct.id = serv.direction_id
                    where valid.niveau1=1 and valid.niveau2=1 and valid.niveau3=1
                    and valid.niveau4=1 and eval.clotureEvaluer=1 and empl.id != $employe->id
                    and direct.id = $direction->id ";
        }

        $req .= "  and eval.annee_id = $campagne->annee_id
                 and empl.isDirecteur =0";
        $performance = DB::select($req);

        return ($performance[0]->performanceUsine == null) ? 0 : $performance[0]->performanceUsine;
    }

}
