<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
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
        return GlobalResource::collection(Role::latest()->get());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GlobalResource
     */
    public function store(Request $request)
    {
        try {
            $datas = $request->all();
            if (count($datas)==0){
                return GlobalResource::make(['code'=>500,'message'=>'Aucun rôle saisir.']);
            }

            $tabRole = array();
            foreach ($datas as $data){
                if ($data['name'] =='' or $data['name'] == null){
                    return GlobalResource::make(['code'=>500,'message'=>'Veuillez ne pas envoyer un rôle sans libellé']);
                }
                array_push($tabRole, [
                    'name'=>$data['name'],
                    'description'=>$data['description'],
                    'created_at'=>(new \DateTime())->format('Y-d-m H:i:s'),
                    'updated_at'=>(new \DateTime())->format('Y-d-m H:i:s')
                ]);
            }

            DB::table('roles')->insert($tabRole);

            return  GlobalResource::make(['code'=>201,'message'=>'Enregistrement effectué avec succès!']);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement role',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Role',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return GlobalResource
     */
    public function show(Role $role)
    {
        return GlobalResource::make($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return GlobalResource
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
        ]);

        try {
            $role->update($request->only(['name','description']));

            return new GlobalResource($role);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification role',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Role',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->noContent();
    }
}
