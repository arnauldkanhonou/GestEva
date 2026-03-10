<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\Annee;
use App\Models\CampagneObjectif;
use App\Models\CampagnePerformance;
use App\Models\Employe;
use App\Models\Evaluation;
use App\Models\Objectif;
use App\Models\TypeEvaluation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class ObjectifController extends Controller
{
    protected $mouchard;
    public function __construct(MouchardApp $mouchardApp)
    {
        $this->mouchard = $mouchardApp;
        $this->middleware(['user']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(Request $request,$nbre=10)
    {
        $employe = Employe::where('email', $request->user()->email)->first();

        if ($employe==''){
            return GlobalResource::collection([]);
        }
        $verify_campagne = false;
        $annee_sys = (new \DateTime())->format('Y');
        $have_campagne = false;
        $typeEva = TypeEvaluation::where('code','EMP')->first();
        $campagne = CampagnePerformance::where('type_evaluation_id',$typeEva->id)->latest()->first();
        if ($campagne!=null && $campagne !=''){
            $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
            $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
            $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

            if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
                $verify_campagne = true;
            }
            $annee_campagne = $campagne->annee->libelle;

            if ($verify_campagne && $annee_campagne==$annee_sys) {
                $have_campagne = true;
            }
        }

        $objectifs = Objectif::with('annee')->where('employe_id', $employe->id)->paginate($nbre);
        return ['dataObjectifs'=>$objectifs,'have_campagne'=>$have_campagne,'annee_sys'=>$annee_sys];
    }

    public function search(Request $request,$q)
    {
        $employe = Employe::where('email', $request->user()->email)->first();

        if ($employe==''){
            return GlobalResource::collection([]);
        }
        if ($q != null) {
            $search = Objectif::with('annee')->where('employe_id', $employe->id)->where('libelle', 'like', '%' . $q . '%')->get();
            if (count($search) > 0) {
                $employes['data'] = $search;
                goto finish;
            }

            $employes = GlobalResource::collection([]);
            goto finish;

            finish:
            return response()->json($employes);
        } else {
            return $this->refresh(10,$employe);
        }
    }

    public function filtre(Request $request){
        $verify_campagne = false;
        $annee_sys = (new \DateTime())->format('Y');
        $have_campagne = false;
        $typeEva = TypeEvaluation::where('code','EMP')->first();
        $campagne = CampagnePerformance::where('type_evaluation_id',$typeEva->id)->latest()->first();
        if ($campagne!=null && $campagne !=''){
            $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
            $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
            $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

            if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
                $verify_campagne = true;
            }
            $annee_campagne = $campagne->annee->libelle;

            if ($verify_campagne && $annee_campagne==$annee_sys) {
                $have_campagne = true;
            }
        }
        $employe = Employe::where('email',$request->user()->email)->first();
        $objectifs = Objectif::with('annee')->where('employe_id',$employe->id)->where('annee_id',$request->annee)->paginate(10);
        return ['dataObjectifs'=>$objectifs,'have_campagne'=>$have_campagne,'annee_sys'=>$annee_sys];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return GlobalResource
     */
    public function store(Request $request)
    {
        try {
            if ($request->type == 'formObjectif') {
                $campagne = CampagneObjectif::where('cloturer', false)->first();
                if ($campagne=='' or $campagne==null){
                    return GlobalResource::make(['code' => 500, 'message' => 'Aucune campagne de saisir d\'objectif ouverte. Contactez les RH.']);
                }
                //$idAnnee = $campagne->annee_id;
                $annee = Annee::where('libelle',(new \DateTime())->format('Y'))->first();
                $idAnnee = $annee->id;
                $datas = $request->all();
                if ($datas['idColab']=="0")
                    $employe = Employe::where('email', $request->user()->email)->first();
                else
                    $employe = Employe::find($datas['idColab']);
            } else {
                //objectif envoyé depuis le formulaire d'auto_évaluation
                $campagne = CampagnePerformance::where('cloturer', false)->first();
                //$anneeCampagne = Annee::find($campagne->annee_id);
                $anneeCampagne = Annee::where('libelle',(new \DateTime())->format('Y'))->first();
                $date = $anneeCampagne->libelle + 1;
                $annee = Annee::where('libelle', $date)->first();
                $idAnnee = $annee->id;
                $evaluation = Evaluation::find($request->idEvaluation);
                $employe = Employe::where('id', $evaluation->employe_id)->first();
            }

            /*if ($campagne == null and $campagne == '') {
                return GlobalResource::make(['code' => 500, 'message' => 'Aucune campagne de saisir d\'objectif lancée']);
            }*/
            if ($campagne != null and $campagne != '') {
                $plage1 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage1))->format('d/m/Y H:i:s'));
                $plage2 = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime($campagne->plage2))->format('d/m/Y H:i:s'));
                $dateCurrent = Carbon::createFromFormat('d/m/Y H:i:s', (new \DateTime())->format('d/m/Y H:i:s'));

                if ($plage1->lte($dateCurrent) && $dateCurrent->lte($plage2)) {
                } else {
                    return GlobalResource::make(['code' => 500, 'message' => 'La période définie pour la campagne des performances ou de saisir d\'objectif est expirée! Contactez les RH.']);
                }
            }

            $objectifExistantPourLaCampagneEncours = Objectif::where('employe_id', $employe->id)->where('annee_id', $idAnnee)->get();
            if (count($objectifExistantPourLaCampagneEncours) >= 5) {
                return GlobalResource::make(['code' => 500, 'message' => 'Vous avez déjà définis les 05 objectifs  pour la nouvelle année']);
            }

            $datas = $request->tabObjectif;
            $typeForm = $request->type;

            if (count($datas) == 0) {
                return GlobalResource::make(['code' => 500, 'message' => 'Aucun objectif saisir.']);
            }

            //$employe = Employe::where('email', $request->user()->email)->first();
            $tabObjctifs = array();

            foreach ($datas as $data) {
                if ($data['libelle'] == '' or $data['libelle'] == null) {
                    return GlobalResource::make(['code' => 500, 'message' => 'Veuillez ne pas envoyer un objectif sans libellé']);
                }
                array_push($tabObjctifs, [
                    'isjson' => true,
                    'annee_id' => $idAnnee,
                    'resultatAttendu' => json_encode($data['resultats']),
                    'echeance' => $data['echeance'],
                    'actionCle' => json_encode($data['actions']),
                    'libelle' => $data['libelle'],
                    'valider' => ($typeForm == 'formEntretien') ? true : false,
                    'employe_id' => $employe->id,
                    'created_at' => (new \DateTime())->format('Y-d-m H:i:s'),
                    'updated_at' => (new \DateTime())->format('Y-d-m H:i:s')
                ]);
            }

            DB::table('objectifs')->insert($tabObjctifs);

            return GlobalResource::make(['code' => 200, 'message' => 'Enregistrement effectué avec succès!']);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement objectif',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Objectif',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Objectif $objectif
     * @return GlobalResource
     */
    public function show(Objectif $objectif)
    {
        $objectif = Objectif::with('employe')->find($objectif->id);
        $objectif->actionCle = json_decode($objectif->actionCle);
        $objectif->resultatAttendu = json_decode($objectif->resultatAttendu);
        return GlobalResource::make($objectif);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Objectif $objectif
     * @return GlobalResource
     */
    public function update(Request $request, Objectif $objectif)
    {
        try {
            $campagneObjectif = CampagneObjectif::where('cloturer', false)->first();
            /*if ($campagneObjectif == null and $campagneObjectif == '') {
                return GlobalResource::make(['code' => 500, 'message' => 'Aucune campagne de saisir d\'objectif lancée']);
            }*/
            if (!$objectif->valider) {
                $request->validate([
                    'libelle' => 'required',
                ]);
                //$employe = Employe::where('email', $request->user()->email)->first();
                $objectif->libelle = $request->libelle;
                $objectif->resultatAttendu = json_encode($request->resultats);
                $objectif->echeance = $request->echeance;
                $objectif->actionCle = json_encode( $request->actions);

                $objectif->save();
            }

            return new GlobalResource($objectif);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification objectif',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Objectif',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }
    }

    public function validateObjectif(Request $request)
    {
        $data = $request->all();
        if ((boolean)$data['full'] == true) {
            foreach ($data['tabId'] as $id) {
                $mission = Objectif::find($id);
                $mission->valider = true;
                $mission->save();
            }

        } else {
            $mission = Objectif::find($data['id']);
            $mission->valider = true;
            $mission->save();
        }

        return GlobalResource::make(['code' => 200, 'message' => 'Validation effectué avec succès!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Objectif $objectif
     * @return \Illuminate\Http\Response
     */
    public function destroy(Objectif $objectif)
    {
        $objectif->delete();

        return response()->noContent();
    }

    public function destroyByLibelle(Request $request, $idCollaborateur, $idEvaluation,$from='')
    {
        try {
            if ($idEvaluation == 0) {
                if ($from=='autoEvaluationSalarieByResp'){
                    $employe = Employe::find($idCollaborateur);
                }else{
                    $user = User::find($idCollaborateur);
                    $employe = Employe::where('email', $user->email)->first();
                }
            } else {
                $evaluation = Evaluation::find($idEvaluation);
                $employe = Employe::find($evaluation->employe_id);
            }
            $objectif = Objectif::where('libelle', $request->libelle)->where('employe_id', $employe->id)->first();
            $objectif->delete();
            return GlobalResource::make(['code' => 200, 'message' => 'success!']);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec suppression objectif',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Objectif',
                'methode'=> 'destroyByLibelle'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la suppression de l\'objectif. Contactez IT']);

        }
    }

    private function refresh($nbre,Employe $employe){
        return GlobalResource::collection(Objectif::with('annee')->where('employe_id', $employe->id)->paginate($nbre));
    }

}
