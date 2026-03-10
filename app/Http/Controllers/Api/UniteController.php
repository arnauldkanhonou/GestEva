<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\Unite;
use Illuminate\Http\Request;

class UniteController extends Controller
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
        $datas = Unite::with('service')->orderBy('created_at', 'DESC')->get();

        return GlobalResource::collection($datas);

    }

    public function ressource($nbre)
    {
        return $this->refresh($nbre);
    }

    public function search($q)
    {
        if ($q != null) {
            $search = Unite::with('service')->where('code', 'like', '%' . $q . '%')->get();
            if (count($search) > 0) {
                $services['data'] = $search;
                goto finish;
            }
            $search = Unite::with(['service'])->where('libelle', 'like', '%' . $q . '%')->get();
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
            'code'=>'required|max:20|unique:unites',
            'libelle'=>'required|unique:unites',
            'service'=>'required',
        ], [
            'code.required'=>'Le code est obligatoire',
            'service.required'=>'Veuillez renseigner le service dans lequel vous créez la fonction',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.unique'=>'Le code existe déjà en base de données',
            'code.max'=>'Le code doit être au maximum de 5 caractères',
            'libelle.unique'=>'Il existe déjà en base de données une unité ayant le libelle saisir'
        ]);

        try {
            $unite = Unite::create([
                'code'=>strtoupper($request->code),
                'libelle'=>$request->libelle,
                'service_id'=>$request->service,
            ]);
            $unite->save();

            return  GlobalResource::make($unite);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification unite',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Unite',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unite  $unite
     * @return GlobalResource
     */
    public function show(Unite $unite)
    {
        return GlobalResource::make($unite);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unite  $unite
     * @return GlobalResource
     */
    public function update(Request $request, Unite $unite)
    {
        $request->validate([
            'code'=>'required|max:20',
            'libelle'=>'required',
            'service'=>'required'
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'service.required'=>'Le service dans lequel on créé la fonction est obligatoire',
            'code.max'=>'Le code doit être au maximum de 5 caractères',
        ]);

        try {
            $unite->code = strtoupper($request->code);
            $unite->libelle = $request->libelle;
            $unite->service_id = $request->service;
            $unite->save();

            return GlobalResource::make($unite);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification unite',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Unite',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unite  $unite
     * @return GlobalResource
     */
    public function destroy(Unite $unite)
    {
        $unite->delete();
        return GlobalResource::make(['code'=>200]);
    }

    public function getUniteByService(Request $request,$id){
        return GlobalResource::collection(Unite::with('service')->where('service_id',$id)->get());
    }

    private function refresh($nbre = 10){
        return GlobalResource::collection(Unite::with('service')->latest()->paginate($nbre));
    }
}
