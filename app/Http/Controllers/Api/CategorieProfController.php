<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\CategorieProfessionnelle;
use Illuminate\Http\Request;

class CategorieProfController extends Controller
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
        $datas = CategorieProfessionnelle::with('categorie')->orderBy('created_at', 'DESC')->get();
        return GlobalResource::collection($datas);
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
            'code'=>'required|max:2|unique:categorie_professionnelles',
            'libelle'=>'required|unique:categorie_professionnelles',
            'categorieEmploye'=>'required',
            'smc'=>'required',
        ], [
            'code.required'=>'Le code est obligatoire',
            'smc.required'=>'Le smc est obligatoire',
            'categorieEmploye.required'=>'La catégorie de l\'employé est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.unique'=>'Le code existe déjà en base de données',
            'code.max'=>'Le code doit être au maximum de 2 caractères',
            'libelle.unique'=>'Il existe déjà en base de données une catégorie ayant le libellé saisir'
        ]);

        try {
            $categorie = CategorieProfessionnelle::create([
                'code'=>strtoupper($request->code),
                'libelle'=>$request->libelle,
                'smc'=>$request->smc,
                'categorie_employe_id'=>$request->categorieEmploye,
            ]);
            $categorie->save();
            return  GlobalResource::make($categorie);

        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement categorie professionnelle',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CategorieProf',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategorieProfessionnelle  $categorieprofessionnelle
     * @return GlobalResource
     */
    public function show(CategorieProfessionnelle $categorieprofessionnelle,$id)
    {
        return GlobalResource::make(CategorieProfessionnelle::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategorieProfessionnelle  $categorieprofessionnelle
     * @return GlobalResource
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'code'=>'required|max:2',
            'libelle'=>'required'
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.max'=>'Le code doit être au maximum de 2 caractères',
        ]);

        try {
            $categorieprofessionnelle = CategorieProfessionnelle::find($id);
            $categorieprofessionnelle->code = strtoupper($request->code);
            $categorieprofessionnelle->libelle = $request->libelle;
            $categorieprofessionnelle->smc = $request->smc;
            $categorieprofessionnelle->categorie_employe_id = $request->categorieEmploye;
            $categorieprofessionnelle->save();

            return GlobalResource::make($categorieprofessionnelle);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification categorie professionnelle',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CategorieProf',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de modification. Contactez IT']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategorieProfessionnelle  $categorieprofessionnelle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categorieprofessionnelle = CategorieProfessionnelle::find($id);
        $categorieprofessionnelle->delete();
        return response()->noContent();
    }

    public function getCateProfByCategorie($id){
        return GlobalResource::collection(CategorieProfessionnelle::where('categorie_employe_id',$id)->get());
    }
}
