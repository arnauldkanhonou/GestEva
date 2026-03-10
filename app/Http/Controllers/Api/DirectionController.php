<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\DirectionResource;
use App\Http\Resources\GlobalResource;
use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DirectionController extends Controller
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
       return DirectionResource::collection(Direction::latest()->get());
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
            'code'=>'required|max:3|unique:directions',
            'libelle'=>'required|unique:directions'
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.unique'=>'Le code existe déjà en base de données',
            'code.max'=>'Le code doit être au maximum de 3 caractères',
            'libelle.unique'=>'Il existe déjà en base de données une direction ayant nom'
        ]);

        try {
            $direction = Direction::create([
                'code'=>strtoupper($request->code),
                'libelle'=>$request->libelle
            ]);
            $direction->save();
            return  GlobalResource::make($direction);

        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement direction',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'DirectionController',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Direction  $direction
     * @return DirectionResource
     */
    public function show(Direction $direction)
    {
        return DirectionResource::make($direction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Direction  $direction
     * @return GlobalResource
     */
    public function update(Request $request, Direction $direction)
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

            $code = strtoupper($request->code);
            /**impossible de modifier le code car c'est déjà utilisé pour effectuer certaines
            requetes. changer entrainerait un disfonctionnement de certains module*/
           // $direction->code = $code;
            $direction->libelle = $request->libelle;
            $direction->save();

            return GlobalResource::make($direction);

        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification direction',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'DirectionController',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Direction  $direction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Direction $direction)
    {
        $direction->delete();
        return response()->noContent();
    }
}
