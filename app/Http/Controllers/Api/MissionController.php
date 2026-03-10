<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\Annee;
use App\Models\CampagnePerformance;
use App\Models\Employe;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MissionController extends Controller
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request,$nbre=10)
    {
        $employe = Employe::where('email', $request->user()->email)->first();

        if ($employe==''){
            return GlobalResource::collection([]);
        }
        return $this->refresh($nbre,$employe);
    }

    public function MissionAReconduire(Request $request){
        $annee = Annee::find($request->annne_id);
        $var = $annee->libelle-1;
        $anneePrecedent = Annee::where('libelle',$var)->first();
        $user = User::find($request->user_id);
        $employe = Employe::where('email',$user->email)->first();
        $missions = Mission::where('annee_id',$anneePrecedent->id)->where('employe_id',$employe->id)->get();

        return response()->json($missions);
    }

    public function filtre(Request $request){
        $employe = Employe::where('email',$request->user()->email)->first();
        return GlobalResource::collection(Mission::with('annee')->where('employe_id',$employe->id)->where('annee_id',$request->annee)->get());
    }

    public function search(Request $request,$q)
    {
        $employe = Employe::where('email', $request->user()->email)->first();

        if ($employe==''){
            return GlobalResource::collection([]);
        }
        if ($q != null) {
            $search = Mission::with('annee')->where('employe_id', $employe->id)->where('libelle', 'like', '%' . $q . '%')->get();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return GlobalResource
     */
    public function store(Request $request)
    {
        try {
            $datas = $request->all();
            if (count($datas) == 0) {
                return GlobalResource::make(['code' => 500, 'message' => 'Aucune mission saisir.']);
            }

            $tabMission = array();
            foreach ($datas['missions'] as $data) {
                if ($datas['idColab']=="0")
                    $employe = Employe::where('email', $request->user()->email)->first();
                else
                   $employe = Employe::find($datas['idColab']);

                if ($data['libelle'] == '' or $data['libelle'] == null) {
                    return GlobalResource::make(['code' => 500, 'message' => 'Veuillez ne pas envoyer une mission sans libellé']);
                }
                array_push($tabMission, [
                    'libelle' => $data['libelle'],
                    'employe_id' => $employe->id,
                    'annee_id' => $datas['annee'],
                    'created_at' => (new \DateTime())->format('Y-d-m H:i:s'),
                    'updated_at' => (new \DateTime())->format('Y-d-m H:i:s')
                ]);
            }

            DB::table('missions')->insert($tabMission);

            return GlobalResource::make(['code' => 201, 'message' => 'Enregistrement effectué avec succès!']);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement missions',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Mission',
                'methode' => 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Mission $mission
     * @return GlobalResource
     */
    public function show(Mission $mission)
    {
        return GlobalResource::make($mission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Mission $mission
     * @return GlobalResource
     */
    public function update(Request $request, Mission $mission)
    {
        $request->validate([
            'libelle' => 'required',
        ]);

        try {
            $mission->update($request->only(['libelle','annee_id']));

            return new GlobalResource($mission);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec modification mission',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Mission',
                'methode' => 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }
    }

    public function validateMission(Request $request)
    {
        try {
            $data = $request->all();
            if ((boolean)$data['full'] == true) {
                foreach ($data['tabId'] as $id) {
                    $mission = Mission::find($id);
                    $mission->valider = true;
                    $mission->save();
                }

            } else {
                $mission = Mission::find($data['id']);
                $mission->valider = true;
                $mission->save();
            }

            return GlobalResource::make(['code' => 200, 'message' => 'Validation effectué avec succès!']);

        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec validation mission',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Mission',
                'methode' => 'validateMission'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la valiadation. Contactez IT']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Mission $mission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mission $mission)
    {
        $mission->delete();
    }

    private function refresh($nbre,Employe $employe){
        return GlobalResource::collection(Mission::with('annee')->where('employe_id', $employe->id)->paginate($nbre));
    }

}
