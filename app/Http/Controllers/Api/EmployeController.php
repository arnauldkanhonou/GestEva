<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Http\Traits\CustormRequestSqlTrait;
use App\Models\CategorieEmploye;
use App\Models\CategorieProfessionnelle;
use App\Models\Employe;
use App\Models\Fonction;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeController extends Controller
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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index($nbre)
    {
        return $this->refresh($nbre);
    }

    public function salarieByService($id,$nbre)
    {
        return DB::table('employes')
            ->join('fonctions', 'fonctions.id', '=', 'employes.fonction_id')
            ->join('services', 'services.id', '=', 'fonctions.service_id')
            ->select('employes.*', 'services.libelle as service', 'fonctions.libelle as poste')
            ->where('services.id', '=', $id)
            ->paginate($nbre);
    }

    public function search($q)
    {
        if ($q != null) {
            $search = Employe::where('matricule', 'like', '%' . $q . '%')->get();
            if (count($search) > 0) {
                $employes['data'] = $search;
                goto finish;
            }
            $search = Employe::where('nom', 'like', '%' . $q . '%')->get();
            if (count($search) > 0) {
                $employes['data'] = $search;
                goto finish;
            }
            $search = Employe::where('prenoms', 'like', '%' . $q . '%')->get();
            if (count($search) > 0) {
                $employes['data'] = $search;
                goto finish;
            }

            $employes = $this->refresh();
            goto finish;

            finish:
            return response()->json($employes);
        } else {
            return $this->refresh();
        }
    }

    public function getSalarieOnPaie(Request $request){
        $data = $request->all();
        $matricule = $data['code'];
        $value = DB::select("EXEC [dbo].[recup_detail_matricule_from_paie] @MAT = $matricule");
        $salarie=$value[0];
        $categorie = substr($salarie->Intitule,0,1);
        $sexe = 'F';
        if ($salarie->Civilite=='0'){
            $sexe='M';
        }
        $groupe= CategorieEmploye::where('code',$categorie)->first();
        $groupe_id=0;
        if ($groupe!=null){
            $groupe_id = $groupe->id;
        }
        $categorie = CategorieProfessionnelle::where('code',$salarie->Intitule)->first();
        $categorie_id=0;
        if ($categorie!=null){
            $categorie_id = $categorie->id;
        }
        $service = Service::where('libelle',$salarie->IntService)->first();
        $service_id=0;
        if ($service!=null){
            $service_id = $service->id;
        }
        $fonction = Fonction::where('libelle',$salarie->EmploiOccupe)->first();
        $fonction_id=0;
        if ($fonction!=null){
            $fonction_id = $fonction->id;
        }
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        return response()->json([
            'matricule'=>$salarie->Matricule,
            'nom'=>$salarie->Nom,
            'prenoms'=>$salarie->Prenom,
            'service'=>$service_id,
            'fonction'=>$fonction_id,
            'groupe'=>$groupe_id,
            'categorie'=>$categorie_id,
            'civilite'=>$sexe,
            'dateEmbauche'=>isset($salarie->dateEmbauche)?(new \DateTime($salarie->dateEmbauche))->format('Y-m-d'):'',
            'telephone'=>isset($salarie->telephone)?$salarie->telephone:'',
            'email'=>strtolower(str_replace($search,$replace,str_replace(' ','',$salarie->Prenom))).'.'.strtolower(str_replace($search,$replace,str_replace(' ','',$salarie->Nom))).'@scb-lafarge.bj',
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return GlobalResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'matricule' => 'required|max:5|unique:employes',
            'nom' => 'required', 'prenoms' => 'required', 'sexe' => 'required',
            'email' => 'required|unique:employes', 'telephone' => 'required|unique:employes',
            'dateEmbauche' => 'required', 'fonction' => 'required',
        ],[
            'matricule.required' => 'Le numéro matricule est obligatoire',
            'nom.required' => 'Le nom de l\'employé est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'telephone.required' => 'Le numéro téléphone est obligatoire',
            'prenoms.required' => 'Le prénom de l\'employé est obligatoire',
            'sexe.required' => 'Le sexe est obligatoire',
            'fonction.required' => 'La fonction est obligatoire',
            'dateEmbauche.required' => 'La date embauche est obligatoire',
            'matricule.unique' => 'Le matricule existe déjà en base de données',
            'matricule.max' => 'Le matricule doit être au maximum de 5 caractères',
            'email.unique' => 'L\'email existe déjà déjà en base de données',
            'telephone.unique' => 'Le numéro téléphone existe déjà en base de données'
        ]);

        try {
            if (!$request->isDirecteur) {
                if ($request->fonction == 'null' or $request->unite == 'null') {
                    return GlobalResource::make(['code' => 500, 'message' => 'La fonction ainsi que l\'unité ne sont pas renseigner.']);
                }
            }

            $filepath = NULL;
            if ($request->file('pathFile') != null && $request->file('pathFile') != 'undefined') {
                $file_name = time() . '_' . $request->nom . '.' . $request->matricule . '.' . $request->file('pathFile')->extension();
                $filepath = $request->file('pathFile')->storeAs("/employes/uploads", $file_name, 'public');
            }
            $fonction_id = $request->fonction;
            if ($request->fonction=='0' or $request->fonction === 'null'){
                $fonction_id = NULL;
            }

            $employe = Employe::create([
                'matricule' => $request->matricule,
                'nom' => $request->nom,
                'prenoms' => $request->prenoms,
                'sexe' => $request->sexe,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'dateEmbauche' => $request->dateEmbauche,
                'dateNaissance' => $request->dateNaissance,
                'lieuNaissance' => $request->lieuNaissance,
                'fonction_id' => $fonction_id,
                'unite_id' => ($request->unite === 'null') ? NULL : $request->unite,
                'respUnite' => ($request->respUnite === 'false') ? false : true,
                'respService' => ($request->respService === 'false') ? false : true,
                'respDepartement' => ($request->respDepartement === 'false') ? false : true,
                'pathFile' => $filepath,
                'categorie_professionnelle_id' => $request->categorieProf,
                'direction_id' => ($request->direction === 'null') ? NULL : $request->direction,
                'departement_id' => ($request->departement === 'null') ? NULL : $request->departement,
                'isDirecteur' => ($request->isDirecteur === 'false') ? false : true,
                'haveComputer' => ($request->haveComputer === 'false') ? false : true,
            ]);

            $employe->save();
            $userExiste = User::where('email',$employe->email)->get();
            if(count($userExiste)==0){
                User::create([
                    'name' => $employe->nom . ' ' . $employe->prenoms,
                    'email' => $employe->email,
                    'password' => Hash::make('lafarge1')
                ]);
                $user = User::where('email', $employe->email)->first();
                $tabRole = array();
                if ($employe->respUnite == true or $employe->respService == true or $employe->respDepartement == true) {
                    $role1 = Role::where('name', 'ROLE_USER')->first();
                    $role2 = Role::where('name', 'ROLE_RESPONSABLE')->first();
                    array_push($tabRole, $role1);
                    array_push($tabRole, $role2);
                } else {
                    if ($employe->isDirecteur){
                        $role1 = Role::where('name', 'ROLE_DIRECTEUR')->first();
                        array_push($tabRole, $role1);
                        if($employe->direction->code =='DRH'){
                            $role2 = Role::where('name', 'ROLE_CODI')->first();
                            array_push($tabRole, $role2);
                        }
                    }else{
                        $role = Role::where('name', 'ROLE_USER')->first();
                        array_push($tabRole, $role);
                    }

                }

                foreach ($tabRole as $item) {
                    $user->roles()->attach([$item->id => array('created_at' => (new \DateTime())->format('Y-d-m H:i:s'), 'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'))]);
                }
            }


            return GlobalResource::make($employe);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec enregistrement employe',
                'message' => $exception->getMessage().' à la ligne '.$exception->getLine(),
                'user' => $request->user()->name,
                'controller' => 'Employe',
                'methode' => 'store'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'enregistrement. Contactez IT']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Employe $employe
     * @return GlobalResource
     */
    public function show(Employe $employe)
    {
        return GlobalResource::make($employe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Employe $employe
     * @return GlobalResource
     */
    public function updateCustomize(Request $request, Employe $employe, $id)
    {
        $request->validate([
            'matricule' => 'required|max:5',
            'nom' => 'required', 'prenoms' => 'required', 'sexe' => 'required',
            //'email' => 'required',
           // 'telephone' => 'required',
            'dateEmbauche' => 'required', 'fonction' => 'required',
        ], [
            'matricule.required' => 'Le numéro matricule est obligatoire',
            'nom.required' => 'Le nom de l\'employé est obligatoire',
            //'email.required' => 'L\'email est obligatoire',
            //'telephone.required' => 'Le numéro téléphone est obligatoire',
            'prenoms.required' => 'Le prénom de l\'employé est obligatoire',
            'sexe.required' => 'Le sexe est obligatoire',
            'fonction.required' => 'La fonction est obligatoire',
            'dateEmbauche.required' => 'La date embauche est obligatoire',
            'matricule.max' => 'Le matricule doit être au maximum de 5 caractères',
        ]);

        try {
            if ($request->file('pathFile') != null && $request->file('pathFile') != 'undefined') {
                $file_name = time() . '_' . $request->nom . '.' . $request->matricule . '.' . $request->file('pathFile')->extension();
                $filepath = $request->file('pathFile')->storeAs("/employes/uploads", $file_name, 'public');
                $request->pathFile = $filepath;
            }
            $employe = Employe::find($id);

            $user = User::where('email', $employe->email)->first();

            $employe->matricule = $request->matricule;
            $employe->nom = $request->nom;
            $employe->prenoms = $request->prenoms;
            $employe->email = strtolower($request->email);
            $employe->fonction_id = $request->fonction;
            $employe->sexe = $request->sexe;
            $employe->telephone = $request->telephone;
            $employe->dateEmbauche = $request->dateEmbauche;
            $employe->pathFile = $request->pathFile;
            $employe->unite_id = $request->unite;
            $employe->respUnite = ($request->respUnite == 'false') ? false : true;
            $employe->respService = ($request->respService == 'false') ? false : true;
            $employe->respDepartement = ($request->respDepartement == 'false') ? false : true;
            $employe->categorie_professionnelle_id = $request->categorieProf;
            $employe->save();

            if ($user != '' && $user != null) {
                $user->email = $request->email;
                $user->save();
            }

            return GlobalResource::make($employe);
        } catch (\Exception $exception) {
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec modification employé',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Employe',
                'methode' => 'update'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Employe $employe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employe $employe)
    {
        $employe->delete();
        return response()->noContent();
    }

    private function refresh($nbre = 10){
       return DB::table('employes')
            ->join('fonctions', 'fonctions.id', '=', 'employes.fonction_id')
            ->join('services', 'services.id', '=', 'fonctions.service_id')
            ->select('employes.*', 'services.libelle as service', 'fonctions.libelle as poste')
            ->paginate($nbre);
//        return GlobalResource::collection(Employe::with('fonction')->paginate($nbre));
    }

}
