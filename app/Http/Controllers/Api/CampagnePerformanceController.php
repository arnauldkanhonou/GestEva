<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Http\Traits\CustormRequestSqlTrait;
use App\Models\CampagnePerformance;
use App\Tools\DateConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampagnePerformanceController extends Controller
{
    use CustormRequestSqlTrait;
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
        return GlobalResource::collection(CampagnePerformance::with('annee','type_evaluation')->latest()->get());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return GlobalResource
     */
    public function store(Request $request)
    {

        $campagneExit = CampagnePerformance::where('cloturer',false)->get();
        if (count($campagneExit)>0){
            return GlobalResource::make(['code'=>500,'message'=>'Impossible de lancer une campagne d\'évaluation des performances. Il existe dans la base certaines non cloturées']);
        }
        $request->validate([
            'plage1' => 'required',
            'plage2' => 'required',
            'libelle' => 'required',
            'annee' => 'required'
        ], [
            'plage1.required' => 'Le date de début est obligatoire',
            'plage2.required' => 'Le date de fin est obligatoire',
            'libelle.required' => 'Le libelle est obligatoire',
            'typeEvaluation.required' => 'Le type evaluation est obligatoire',
        ]);

        try {
            DB::beginTransaction();
            $campagne = CampagnePerformance::create([
                'plage1' => DateConverter::convertDate($request->plage1),
                'plage2' => DateConverter::convertDate($request->plage2),
                'libelle' => $request->libelle,
                'annee_id' => $request->annee,
                'type_evaluation_id' => $request->typeEvaluation,
            ]);

            $datas = [
                'title'=>' Salut cher tous,',
                'data1'=>$request->plage1,
                'date2'=>$request->plage2,
                'message'=>'La plateforme des évaluations de fin d\'année est ouverte de '.(new \DateTime($request->plage1))->format('d/m/Y'). ' au ' .(new \DateTime($request->plage2))->format('d/m/Y').' .',
                'message1'=>'Veuillez vous connecté à votre espace et faite votre évaluation.'
            ];
            $this->sendMailNotification(['_g_ono@scb-lafarge.bj'],$datas);

            DB::commit();
            return GlobalResource::make($campagne);
        }catch (\Exception $exception){
            DB::rollBack();
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement campagne performance',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CampagnePerformance',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CampagnePerformance  $campagnePerformance
     * @return GlobalResource
     */
    public function show(CampagnePerformance $campagnePerformance,$id)
    {
        $campagne = CampagnePerformance::with('annee')->find($id);
        return GlobalResource::make($campagne);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CampagnePerformance  $campagnePerformance
     * @return GlobalResource
     */
    public function update(Request $request, CampagnePerformance $campagnePerformance,$id)
    {
        $request->validate([
            'plage1' => 'required',
            'plage2' => 'required',
            'libelle' => 'required',
            'annee' => 'required'
        ], [
            'plage1.required' => 'Le date de début est obligatoire',
            'plage2.required' => 'Le date de fin est obligatoire',
            'libelle.required' => 'Le libelle est obligatoire',
        ]);

        try {
            $campagnePerformance = CampagnePerformance::find($id);
            $campagnePerformance->plage1 = DateConverter::convertDate($request->plage1);
            $campagnePerformance->plage2 = DateConverter::convertDate($request->plage2);
            $campagnePerformance->libelle = $request->libelle;
            $campagnePerformance->annee_id = $request->annee;
            $campagnePerformance->type_evaluation_id = $request->typeevaluation;
            $campagnePerformance->cloturer = $request->cloturer;

            $campagnePerformance->save();
            return GlobalResource::make($campagnePerformance);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec midification campagne performance',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CampagnePerformance',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CampagnePerformance  $campagneperformance
     * @return \Illuminate\Http\Response
     */
    public function destroy(CampagnePerformance $campagneperformance)
    {
        $campagneperformance->delete();

        return response()->noContent();
    }
}
