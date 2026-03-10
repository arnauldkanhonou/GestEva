<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\CategorieEmploye;
use Illuminate\Http\Request;

class CategorieEmployeController extends Controller
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
        return GlobalResource::collection(CategorieEmploye::all());
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
            'code'=>'required|max:3|unique:categorie_employes',
            'libelle'=>'required|unique:categorie_employes'
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.unique'=>'Le code existe déjà en base de données',
            'code.max'=>'Le code doit être au maximum de 3 caractères',
            'libelle.unique'=>'Il existe déjà en base de données une catégorie ayant le libellé saisir'
        ]);

        try {
            $categorie = CategorieEmploye::create([
                'code'=>strtoupper($request->code),
                'libelle'=>$request->libelle
            ]);
            $categorie->save();
            return  GlobalResource::make($categorie);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement categorie employe',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CategorieEmploye',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategorieEmploye  $categorieEmploye
     * @return GlobalResource
     */
    public function show(CategorieEmploye $categorieEmploye,$id)
    {
        return GlobalResource::make(CategorieEmploye::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategorieEmploye  $categorieEmploye
     * @return GlobalResource
     */
    public function update(Request $request,$id )
    {
        $request->validate([
            'code'=>'required|max:3',
            'libelle'=>'required'
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.max'=>'Le code doit être au maximum de 3 caractères',
        ]);

        try {

            $categorieEmploye = CategorieEmploye::find($id);
            /**impossible de modifier le code car c'est déjà utilisé pour effectuer certaines
             requetes. changer entrainerait un disfonctionnement de certains module*/
            $categorieEmploye->code = strtoupper($request->code);
            $categorieEmploye->libelle = $request->libelle;
            //$categorieEmploye->save();

            return GlobalResource::make($categorieEmploye);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification categorie critère',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CategorieEmploye',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de modification. Contactez IT']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategorieEmploye  $categorieemploye
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategorieEmploye $categorieemploye)
    {
        $categorieemploye->delete();
        return response()->noContent();
    }
}
