<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\PonderationCritere;
use Illuminate\Http\Request;

class PonderationCritereController extends Controller
{
    protected $mouchard;
    public function __construct(MouchardApp $mouchardApp)
    {
        $this->mouchard = $mouchardApp;
        $this->middleware(['admin'])->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return GlobalResource::collection(PonderationCritere::latest()->get());
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
            'libelle'=>'required|unique:ponderation_criteres',
            'point'=>'required',
        ], [
            'libelle.required'=>'Le champs libelle est obligatoire',
            'point.required'=>'Le champs point est obligatoire',
            'libelle.unique'=>'Il existe déjà en base de données une ponderation ayant le libelle saisir'
        ]);

        try {
            $ponderation = PonderationCritere::create([
                'libelle'=>$request->libelle,
                'point'=>$request->point,
            ]);

            return  GlobalResource::make($ponderation);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification ponderation critere',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Ponderation',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PonderationCritere  $ponderationCritere
     * @return GlobalResource
     */
    public function show(PonderationCritere $ponderationCritere,$id)
    {
        $ponderationCritere = PonderationCritere::find($id);
        return GlobalResource::make($ponderationCritere);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PonderationCritere  $ponderationCritere
     * @return GlobalResource
     */
    public function update(Request $request, PonderationCritere $ponderationCritere,$id)
    {
        $request->validate([
            'libelle'=>'required',
            'point'=>'required',
        ], [
            'libelle.required'=>'Le champs libelle est obligatoire',
            'point.required'=>'Le champs point est obligatoire',
        ]);

        try {
            $ponderationCritere = PonderationCritere::find($id);
            $ponderationCritere->libelle = $request->libelle;
            $ponderationCritere->point = $request->point;
            $ponderationCritere->save();

            return GlobalResource::make($ponderationCritere);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modifiation ponderation critere',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Ponderation',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PonderationCritere  $ponderationcritere
     * @return \Illuminate\Http\Response
     */
    public function destroy(PonderationCritere $ponderationcritere)
    {
        $ponderationcritere->delete();
        return response()->noContent();
    }
}
