<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\TypeEvaluation;
use Illuminate\Http\Request;

class TypeEvaluationController extends Controller
{
    protected $mouchard;
    public function __construct(MouchardApp $mouchardApp)
    {
        $this->mouchard = $mouchardApp;
        $this->middleware(['admin'])->except(['index','show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return GlobalResource::collection(TypeEvaluation::latest()->get());
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
            'code'=>'required|max:5|unique:type_evaluations',
            'libelle'=>'required|unique:type_evaluations'
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.unique'=>'Le code existe déjà en base de données',
            'code.max'=>'Le code doit être au maximum de 5 caractères',
            'libelle.unique'=>'Il existe déjà en base de données un type évaluation ayant nom'
        ]);

        try {
            $typeEval = TypeEvaluation::create([
                'code'=>strtoupper($request->code),
                'libelle'=>$request->libelle
            ]);

            return  GlobalResource::make($typeEval);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement type evaluation',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'TypeEvaluation',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypeEvaluation  $typeEvaluation
     * @return GlobalResource
     */
    public function show(TypeEvaluation $typeEvaluation,$id)
    {
        return GlobalResource::make(TypeEvaluation::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypeEvaluation  $typeEvaluation
     * @return GlobalResource
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'code'=>'required|max:4',
            'libelle'=>'required'
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.max'=>'Le code doit être au maximum de 4 caractères',
        ]);

        try {
            $typeEvaluation = TypeEvaluation::find($id);
            //$typeEvaluation->code = strtoupper($request->code);
            $typeEvaluation->libelle = $request->libelle;
            $typeEvaluation->save();

            return GlobalResource::make($typeEvaluation);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification type evaluation',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'TypeEvaluation',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypeEvaluation  $typeevaluation
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeEvaluation $typeevaluation)
    {
        $typeevaluation->delete();
        return response()->noContent();
    }
}
