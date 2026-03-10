<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\CategorieCritere;
use Illuminate\Http\Request;

class CategorieCritereController extends Controller
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
        return GlobalResource::collection(CategorieCritere::latest()->get());
    }

    public function ressource($nbre)
    {
        return $this->refresh($nbre);
    }

    public function search($q)
    {
        if ($q != null) {
            $search = CategorieCritere::where('code', 'like', '%' . $q . '%')->get();
            if (count($search) > 0) {
                $services['data'] = $search;
                goto finish;
            }
            $search = CategorieCritere::where('libelle', 'like', '%' . $q . '%')->get();
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
            'code'=>'required|max:3|unique:categorie_criteres',
            'libelle'=>'required|unique:categorie_criteres'
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.unique'=>'Le code existe déjà en base de données',
            'code.max'=>'Le code doit être au maximum de 3 caractères',
            'libelle.unique'=>'Il existe déjà en base de données une catégorie ayant nom'
        ]);

        try {
            $categorie = CategorieCritere::create([
                'code'=>strtoupper($request->code),
                'libelle'=>$request->libelle
            ]);
            $categorie->save();
            return  GlobalResource::make($categorie);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement categorie critère',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CategorieCritere',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategorieCritere  $categorieCritere
     * @return GlobalResource
     */
    public function show(CategorieCritere $categorieCritere,$id)
    {
        return GlobalResource::make(CategorieCritere::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategorieCritere  $categorieCritere
     * @return GlobalResource
     */
    public function update(Request $request, CategorieCritere $categorieCritere,$id)
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
            $categorieCritere = CategorieCritere::find($id);
            $categorieCritere->code = strtoupper($request->code);
            $categorieCritere->libelle = $request->libelle;
            $categorieCritere->save();

            return GlobalResource::make($categorieCritere);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification categorie critère',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CategorieCritere',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategorieCritere  $categoriecritere
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategorieCritere $categoriecritere)
    {
        $categoriecritere->delete();
        return response()->noContent();
    }

    private function refresh($nbre = 10){
        return GlobalResource::collection(CategorieCritere::latest()->paginate($nbre));
    }
}
