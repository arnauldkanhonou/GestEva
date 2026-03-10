<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\Direction;
use App\Models\Service;
use App\Models\Unite;
use Illuminate\Http\Request;

class ServiceController extends Controller
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
        $datas = Service::orderBy('created_at', 'DESC')->get();

        return GlobalResource::collection($datas);
    }

    public function ressource($nbre)
    {
        return $this->refresh($nbre);
    }

    public function search($q)
    {
        if ($q != null) {
            $search = Service::with('departement,direction')->where('code', 'like', '%' . $q . '%')->get();
            if (count($search) > 0) {
                $services['data'] = $search;
                goto finish;
            }
            $search = Service::with(['departement','direction'])->where('libelle', 'like', '%' . $q . '%')->get();
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
            'code'=>'required|max:20|unique:services',
            'libelle'=>'required|unique:services',
            'direction'=>'required',
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'direction.required'=>'Le departement du service est obligatoire',
            'code.unique'=>'Le code existe déjà en base de données',
            'code.max'=>'Le code doit être au maximum de 4 caractères',
            'libelle.unique'=>'Il existe déjà en base de données un service ayant nom'
        ]);

        try {
            $direction = Direction::where('code',$request->direction)->first();
            $departement = NULL;
            if ($request->departementUsine===true){
                if ($request->pole !='aucun'){
                    $departement = $request->pole;
                }
            }
            $service = Service::create([
                'code'=>strtoupper($request->code),
                'libelle'=>$request->libelle,
                'direction_id'=>$direction->id,
                'departement_id'=>$departement,
            ]);
            /*if ($request->unite){
                Unite::create([
                    'code'=>strtoupper($request->code),
                    'libelle'=>$request->libelle,
                    'service_id'=>$service->id,
                ]);
            }*/
            return  GlobalResource::make($service);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement service',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Service',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return GlobalResource
     */
    public function show(Service $service)
    {
        $direction = Direction::find($service->direction_id);
        return GlobalResource::make([
            'id'=>$service->id,
            'code'=>$service->code,
            'libelle'=>$service->libelle,
            'codeDirection'=>$direction->code,
            'departement_id'=>$service->departement_id,
            'direction_id'=>$service->direction_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return GlobalResource
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'code'=>'required|max:20',
            'libelle'=>'required'
        ], [
            'code.required'=>'Le code est obligatoire',
            'libelle.required'=>'Le libelle est obligatoire',
            'code.max'=>'Le code doit être au maximum de 4 caractères',
        ]);

        try {
            $code = strtoupper($request->code);
            $direction = Direction::find($request->direction);
            $service->code = $code;
            $service->libelle = $request->libelle;
            $service->departement_id = $request->departement;
            $service->direction_id = $direction->id;
            $service->save();
            return GlobalResource::make($service);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification service',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Service',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return GlobalResource
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return GlobalResource::make(['code'=>200]);
    }

    private function refresh($nbre = 10){
        return GlobalResource::collection(Service::with('departement','direction')->latest()->paginate($nbre));
    }
}
