<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\Departement;
use App\Models\Direction;
use Illuminate\Http\Request;

class DepartementController extends Controller
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
        $datas = Departement::with('direction')->orderBy('created_at', 'DESC')->get();

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
            'code'=>'required|max:4|unique:departements',
            'libelle'=>'required|unique:departements',
            //'direction'=>'required',
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            //'direction.required'=>'La direction du département est obligatoire',
            'code.unique'=>'Le code existe déjà en base de données',
            'code.max'=>'Le code doit être au maximum de 4 caractères',
            'libelle.unique'=>'Il existe déjà en base de données un département ayant nom'
        ]);

        $directionUsine = Direction::where('code','DU')->first();

        try {
            $departement = Departement::create([
                'code'=>strtoupper($request->code),
                'libelle'=>$request->libelle,
                'direction_id'=>$directionUsine->id,
            ]);

            $departement->save();

            return  GlobalResource::make($departement);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement département',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Departement',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Departement  $departement
     * @return GlobalResource
     */
    public function show(Departement $departement)
    {
        return GlobalResource::make($departement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Departement  $departement
     * @return GlobalResource
     */
    public function update(Request $request, Departement $departement)
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
            $directionUsine = Direction::where('code','DU')->first();
            $code = strtoupper($request->code);
            $departement->code = $code;
            $departement->libelle = $request->libelle;
            $departement->direction_id = $directionUsine->id;
            $departement->save();
            return GlobalResource::make($departement);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification departement',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Departement',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Departement  $departement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Departement $departement)
    {
       $departement->delete();

       return response()->noContent();
    }
}
