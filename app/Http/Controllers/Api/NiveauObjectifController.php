<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\NiveauExecutionObjectif;
use Illuminate\Http\Request;

class NiveauObjectifController extends Controller
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
        return GlobalResource::collection(NiveauExecutionObjectif::latest()->get());
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
            'libelle'=>'required|unique:niveau_execution_objectifs',
            'point'=>'required','appreciation'=>'required',
        ], [
            'libelle.required'=>'Le champs libelle est obligatoire',
            'point.required'=>'Le champs point est obligatoire',
            'appreciation.required'=>'Le champs appreciation est obligatoire',
            'libelle.unique'=>'Il existe déjà en base de données un niveau d\'exécution ayant le libelle saisir'
        ]);

        try {
            $niveauExecution = NiveauExecutionObjectif::create([
                'libelle'=>$request->libelle,
                'point'=>$request->point,
                'appreciation'=>$request->appreciation,
            ]);

            $niveauExecution->save();

            return  GlobalResource::make($niveauExecution);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement niveau execution objectif',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'NiveauObjectif',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NiveauExecutionObjectif  $niveauExecutionObjectif
     * @return GlobalResource
     */
    public function show(NiveauExecutionObjectif $niveauExecutionObjectif,$id)
    {
        $niveauExecutionObjectif = NiveauExecutionObjectif::find($id);
        return GlobalResource::make($niveauExecutionObjectif);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NiveauExecutionObjectif  $niveauExecutionObjectif
     * @return GlobalResource
     */
    public function update(Request $request, NiveauExecutionObjectif $niveauExecutionObjectif,$id)
    {
        $request->validate([
            'libelle'=>'required',
            'point'=>'required',
        ], [
            'libelle.required'=>'Le champs libelle est obligatoire',
            'point.required'=>'Le champs point est obligatoire',
        ]);
        try {
            $niveauExecutionObjectif = NiveauExecutionObjectif::find($id);
            $niveauExecutionObjectif->libelle = $request->libelle;
            $niveauExecutionObjectif->point = $request->point;
            $niveauExecutionObjectif->appreciation = $request->appreciation;
            $niveauExecutionObjectif->save();

            return GlobalResource::make($niveauExecutionObjectif);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modifiation niveau objectif',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'NiveauObjectif',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NiveauExecutionObjectif  $niveauexecutionobjectif
     * @return \Illuminate\Http\Response
     */
    public function destroy(NiveauExecutionObjectif $niveauexecutionobjectif)
    {
        $niveauexecutionobjectif->delete();
        return response()->noContent();
    }
}
