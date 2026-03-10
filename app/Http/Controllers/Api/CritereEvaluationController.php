<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Http\Traits\CustormRequestSqlTrait;
use App\Models\CategorieCritere;
use App\Models\CritereEvaluation;
use Illuminate\Http\Request;

class CritereEvaluationController extends Controller
{
    protected $mouchard;
    public function __construct(MouchardApp $mouchardApp)
    {
        $this->mouchard = $mouchardApp;
        $this->middleware(['admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return GlobalResource::collection(CritereEvaluation::with(['categorieEmploye','categorieCritere'])->latest()->get());
    }

    public function ressource($nbre)
    {
        return $this->refresh($nbre);
    }

    public function search($q)
    {
        if ($q != null) {
            $search = CritereEvaluation::with(['categorieEmploye','categorieCritere'])->where('code', 'like', '%' . $q . '%')->get();
            if (count($search) > 0) {
                $services['data'] = $search;
                goto finish;
            }
            $search = CritereEvaluation::with(['categorieEmploye','categorieCritere'])->where('libelle', 'like', '%' . $q . '%')->get();
            if (count($search) > 0) {
                $services['data'] = $search;
                goto finish;
            }

            $services = $this->refresh();
            goto finish;

            finish:

            return response()->json($services);
        } else {
            return $this->refresh();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GlobalResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required|max:5|unique:critere_evaluations',
            'libelle'=>'required',
            'categorieCritere'=>'required',
            'categorieEmploye'=>'required',
        ], [
            'code.required'=>'Le code est obligatoire',
            'categorieCritere.required'=>'La catégorie du critère est obligatoire',
            'categorieEmploye.required'=>'La catégorie employé est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.unique'=>'Le code existe déjà en base de données',
            'code.max'=>'Le code doit être au maximum de 5 caractères',
            //'libelle.unique'=>'Il existe déjà en base de données un critère ayant nom'
        ]);

        try {
            $critere = CritereEvaluation::create([
                'code'=>strtoupper($request->code),
                'libelle'=>$request->libelle,
                'categorie_critere_id'=>$request->categorieCritere,
                'categorie_employe_id'=>$request->categorieEmploye,
            ]);
            $critere->save();

            return  GlobalResource::make($critere);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement critère evaluation',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CirtereEvaluation',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CritereEvaluation  $critereEvaluation
     * @return GlobalResource
     */
    public function show(CritereEvaluation $critereEvaluation,$id)
    {
        return GlobalResource::make(CritereEvaluation::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CritereEvaluation  $critereEvaluation
     * @return GlobalResource
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code'=>'required|max:5',
            'libelle'=>'required'
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.max'=>'Le code doit être au maximum de 5 caractères',
        ]);

        try {
            $critereEvaluation = CritereEvaluation::find($id);
            $critereEvaluation->code = strtoupper($request->code);
            $critereEvaluation->libelle = $request->libelle;
            $critereEvaluation->categorie_critere_id = $request->categorieCritere;
            $critereEvaluation->save();

            return GlobalResource::make($critereEvaluation);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification critère evaluation',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CritereEvaluation',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CritereEvaluation  $critereEvaluation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $critere = CritereEvaluation::find($id);
        $critere->delete();
        return response()->noContent();
    }

    private function refresh($nbre = 10){
        return GlobalResource::collection(CritereEvaluation::with(['categorieEmploye','categorieCritere'])->latest()->paginate($nbre));
    }
}
