<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\NiveauPerformance;
use Illuminate\Http\Request;

class PerformanceController extends Controller
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
        return GlobalResource::collection(NiveauPerformance::latest()->get());
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
            'appreciation'=>'required|unique:niveau_performances',
            'libelle'=>'required|unique:niveau_performances',
            'borneInf'=>'required',
            'borneSup'=>'required',
        ], [
            'appreciation.required'=>'Le champs appreciation est obligatoire',
            'libelle.required'=>'Le champs libelle est obligatoire',
            'borneInf.required'=>'Le champs borne inférieur est obligatoire',
            'borneSup.required'=>'Le champs borne supérieur est obligatoire',
            'appreciation.unique'=>'L\'appréciation existe déjà en base de données',
            'libelle.unique'=>'Il existe déjà en base de données une performance ayant le libelle saisir'
        ]);

        try {
            $niveauPerf = NiveauPerformance::create([
                'appreciation'=>$request->appreciation,
                'libelle'=>$request->libelle,
                'borneInf'=>$request->borneInf,
                'borneSup'=>$request->borneSup,
            ]);

            return  GlobalResource::make($niveauPerf);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement performance',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Performance',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NiveauPerformance  $niveauPerformance
     * @return GlobalResource
     */
    public function show(NiveauPerformance $niveauPerformance,$id)
    {
        $niveauPerformance = NiveauPerformance::find($id);
        return GlobalResource::make($niveauPerformance);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GlobalResource
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'appreciation'=>'required',
            'libelle'=>'required',
            'borneInf'=>'required',
            'borneSup'=>'required',
        ], [
            'appreciation.required'=>'Le champs appreciation est obligatoire',
            'libelle.required'=>'Le champs libelle est obligatoire',
            'borneInf.required'=>'Le champs borne inférieur est obligatoire',
            'borneSup.required'=>'Le champs borne supérieur est obligatoire',
        ]);

        try {
            $niveauPerformance = NiveauPerformance::find($id);
            $niveauPerformance->appreciation = $request->appreciation;
            $niveauPerformance->libelle = $request->libelle;
            $niveauPerformance->borneSup = $request->borneSup;
            $niveauPerformance->borneInf = $request->borneInf;
            $niveauPerformance->save();

            return GlobalResource::make($niveauPerformance);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification performance',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Objectif',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NiveauPerformance  $niveauperformance
     * @return \Illuminate\Http\Response
     */
    public function destroy(NiveauPerformance $niveauperformance)
    {
        $niveauperformance->delete();
        return response()->noContent();
    }
}
