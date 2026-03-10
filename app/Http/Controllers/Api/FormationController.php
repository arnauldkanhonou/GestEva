<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\Annee;
use App\Models\Formation;
use App\Models\TypeFormation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormationController extends Controller
{
    protected $mouchard;

    public function __construct(MouchardApp $mouchardApp)
    {
        $this->mouchard = $mouchardApp;
        $this->middleware(['admin'])->except(['typeFormation']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return GlobalResource::collection(Formation::with('type_formation')->latest()->get());
    }

    public function typeFormation(Request $request)
    {
        return GlobalResource::collection(TypeFormation::latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return GlobalResource
     */
    public function store(Request $request)
    {
        try {
            $datas = $request->all();

            if (count($datas) == 0) {
                return GlobalResource::make(['code' => 500, 'message' => 'Aucune mission saisir.']);
            }
            $date = (new \DateTime())->format('Y');
            $annee = Annee::where('libelle', $date)->first();
            $formations_employe = array();
            $tabInsert = array();
            foreach ($datas as $data) {
                $formation = Formation::create([
                    'libelle' => $data['libelle'],
                    'dateFormation' => $data['dateFormation'],
                    'objectifVise' => $data['objectifVise'],
                    'created_at' => (new \DateTime())->format('Y-d-m H:i:s'),
                    'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'),
                    'annee_id' => $annee->id,
                    'type_formation_id' => $data['typeFormation'],
                ]);
                $formation->save();
                foreach ($data['tabEmploye'] as $item) {
                    array_push($formations_employe, [
                        'employe_id' => $item,
                        'formation_id' => $formation->id,
                        'created_at' => (new \DateTime())->format('Y-d-m H:i:s'),
                        'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'),
                    ]);
                }
                array_push($tabInsert, $formations_employe);
            }

            foreach ($tabInsert as $value) {
                DB::table('formation_employe')->insert($value);
            }

            return GlobalResource::make(['code' => 201, 'message' => 'Enregistrement effectué avec succès!']);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement formation',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Formation',
                'methode' => 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Formation $formation
     * @return GlobalResource
     */
    public function show(Formation $formation)
    {
        $format = Formation::with('employes')->where('id', $formation->id)->first();

        return GlobalResource::make($format);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Formation $formation
     * @return GlobalResource
     */
    public function update(Request $request, Formation $formation)
    {
        $request->validate([
            'libelle' => 'required',
            'dateFormation' => 'required',
            'objectifVise' => 'required',
        ]);

        try {
            $formation->update($request->only(['libelle', 'dateFormation', 'objectifVise','type_formation_id']));
            $formation->type_formation_id= $request->type_formation_id;
            $formation->save();

            foreach ($request->unites as $item) {
                $formation->employes()->attach([$item => array('created_at' => (new \DateTime())->format('Y-d-m H:i:s'), 'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'))]);

            }

            //$formation->employes()->sync();

            return new GlobalResource($formation);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec modification Formation',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Formation',
                'methode' => 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Formation $formation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Formation $formation)
    {
        $formation->employes()->detach();
        $formation->delete();
        return response()->noContent();
    }
}
