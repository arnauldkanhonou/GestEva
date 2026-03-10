<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Http\Traits\CustormRequestSqlTrait;
use App\Models\CampagneObjectif;
use App\Tools\DateConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\Glob;

class CampagneObjectifController extends Controller
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
        return GlobalResource::collection(CampagneObjectif::with('annee')->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return GlobalResource
     */
    public function store(Request $request)
    {
        $campagneExit = CampagneObjectif::where('cloturer',false)->get();
        if (count($campagneExit)>0){
            return GlobalResource::make(['code'=>500,'message'=>'Impossible de lancer une campagne de saisir des objectif. Il existe dans la base certaines non cloturées']);
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
        ]);

        try {
            DB::beginTransaction();
            $campagne = CampagneObjectif::create([
                'plage1' => DateConverter::convertDate($request->plage1),
                'plage2' => DateConverter::convertDate($request->plage2),
                'libelle' => $request->libelle,
                'annee_id' => $request->annee,
            ]);

            $datas = [
                'title'=>' Salut cher tous,',
                'date1'=>$request->plage2,
                'date2'=>$request->plage2,
                'message'=>'La plateforme des évaluations de fin d\'année est ouverte de '.(new \DateTime($request->plage1))->format('d/m/Y'). ' au ' .(new \DateTime($request->plage2))->format('d/m/Y').' .',
                'message1'=>'Veuillez vous connecté à votre espace pour saisir vos objectifs et faites les validés par votre supérieur hiérachique.'
            ];
            $this->sendMailNotification(['_g_ono@scb-lafarge.bj'],$datas);
            DB::commit();
            return GlobalResource::make($campagne);

        }catch (\Exception $exception){
            DB::rollBack();
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement campagne objectif',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CampagneObjectif',
                'methode'=> 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\CampagneObjectif $campagneObjectif
     * @return GlobalResource
     */
    public function show(CampagneObjectif $campagneObjectif, $id)
    {
        $campagneObjectif = CampagneObjectif::with('annee')->find($id);
        return GlobalResource::make($campagneObjectif);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CampagneObjectif $campagneObjectif
     * @return GlobalResource
     */
    public function update(Request $request, CampagneObjectif $campagneObjectif,$id)
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
            $campagneObjectif = CampagneObjectif::find($id);
            $campagneObjectif->plage1 = DateConverter::convertDate($request->plage1);
            $campagneObjectif->plage2 = DateConverter::convertDate($request->plage2);
            $campagneObjectif->libelle = $request->libelle;
            $campagneObjectif->annee_id = $request->annee;
            $campagneObjectif->cloturer = $request->cloturer;

            $campagneObjectif->save();
            return GlobalResource::make($campagneObjectif);

        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification campagne objectif',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'CampagneController',
                'methode'=> 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors demodification. Contactez IT']);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CampagneObjectif $campagneObjectif
     * @return \Illuminate\Http\Response
     */
    public function destroy(CampagneObjectif $campagneObjectif,$id)
    {
        $campagneObjectif = CampagneObjectif::find($id);
        $campagneObjectif->delete();

        return response()->noContent();
    }
}
