<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\CategorieProfessionnelle;
use App\Models\Departement;
use App\Models\Direction;
use App\Models\Employe;
use App\Models\Fonction;
use App\Models\Role;
use App\Models\Unite;
use App\Models\User;
use FontLib\Table\Type\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportationDataController extends Controller
{
    protected $mouchard;
    public function __construct(MouchardApp $mouchardApp)
    {
        $this->mouchard = $mouchardApp;
        $this->middleware(['admin']);
    }

    public function importSalarie(Request $request){
        $this->validate($request, [
            'uploaded_file' => 'required|file|mimes:xls,xlsx'
        ]);
        $the_file = $request->file('uploaded_file');
        $spreadsheet = IOFactory::load($the_file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $row_limit = $sheet->getHighestDataRow();
        $row_range = range(2, $row_limit);
        $dataSalarie = array();
        $tabUsers = array();
        foreach ($row_range as $row) {
            $codePoste = trim($sheet->getCell('J' . $row)->getValue());
            $poste = Fonction::where('code',$codePoste)->first();
            if ($poste==''or $poste==null){
                $code = 400;
                $message = 'Le code du poste d\'un salarié n\'est pas renseigné';
                goto Finish;
            }
            $codeUnite = trim($sheet->getCell('K' . $row)->getValue());
            $unite = Unite::where('code',$codeUnite)->first();
            $uniteId = NULL;
            if ($unite !='' or $unite !=null){
                $uniteId = $unite->id;
            }
            $codeCateProf = trim($sheet->getCell('N' . $row)->getValue());
            $categorieProf = CategorieProfessionnelle::where('code',$codeCateProf)->first();
            if ($categorieProf =='' or $categorieProf ==null){
                $code = 400;
                $message = 'Le code de la cotégorie professionnelle d\'un salarié n\'est pas renseigné';
                goto Finish;
            }
            $codeDirect = trim($sheet->getCell('P' . $row)->getValue());
            $direction = NULL;
            $isDirecteur = false;
            if ((int)trim($sheet->getCell('O' . $row)->getValue())==1){
                $isDirecteur = true;
                if ($codeDirect !='' && $codeDirect!=null)
                    $direction = (Direction::where('code',$codeDirect)->first())->id;
            }

            $codePole = trim($sheet->getCell('R' . $row)->getValue());
            $pole = NULL;
            $respDepart = false;
            if ((int)trim($sheet->getCell('Q' . $row)->getValue())==1){
                $respDepart = true;
                if ($codePole !='' && $codePole!=null)
                    $pole = (Departement::where('code',$codePole)->first())->id;
            }

            $respUnite = false;
            if ((int)trim($sheet->getCell('L' . $row)->getValue())==1)
                $respUnite = true;

            $respService = false;
            if ((int)trim($sheet->getCell('M' . $row)->getValue())==1)
                $respService = true;

            $hasComputer = false;
            if ((int)trim($sheet->getCell('S' . $row)->getValue())==1)
                $hasComputer = true;

            if (!$respService && (!$isDirecteur && !$respDepart)){
                if ($uniteId ==NULL){
                    $code = 400;
                    $message = 'Le code d\'unité d\'un salarié qui n\'est pas responsable service n\'est pas renseigné';
                    goto Finish;
                }
            }

            $haveRespDep = false;
            $haveCollab = false;

            if ($respService && $codeDirect=='DU'){
                if ($codePole!='' && $codePole!=null){
                    $haveRespDep = true;
                    $haveCollab = true;
                }
            }else{
                $haveRespDep = true;
                $haveCollab = true;
            }
            $lieu = NULL;
            if (trim($sheet->getCell('I' . $row)->getValue())!=''){
                $lieu = trim($sheet->getCell('I' . $row)->getValue());
            }

            $email = NULL;
            if (trim($sheet->getCell('E' . $row)->getValue())!=''){
                $email = trim($sheet->getCell('E' . $row)->getValue());
            }

            $telephone = NULL;
            if (trim($sheet->getCell('I' . $row)->getValue())!=''){
                $telephone = trim($sheet->getCell('F' . $row)->getValue());
            }
            $dateNais= NULL;
            $dateEmb = (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($sheet->getCell('G' . $row)->getValue())))->format('Y-m-d');
            if (trim($sheet->getCell('H' . $row)->getValue())!=''){
                $dateNais = (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($sheet->getCell('H' . $row)->getValue())))->format('Y-m-d');
            }

            $tab = [
                'matricule'=>trim($sheet->getCell('A' . $row)->getValue()),
                'nom'=>strtoupper(trim($sheet->getCell('B' . $row)->getValue())),
                'prenoms'=>trim($sheet->getCell('C' . $row)->getValue()),
                'sexe'=>strtoupper(trim($sheet->getCell('D' . $row)->getValue())),
                'email'=>$email,
                'telephone'=>$telephone,
                'dateEmbauche'=>$dateEmb,
                'dateNaissance'=>$dateNais,
                'lieuNaissance'=>$lieu,
                'fonction_id'=>$poste->id,
                'unite_id'=>$uniteId,
                'respUnite'=>$respUnite,
                'respService'=>$respService,
                'respDepartement'=>$respDepart,
                'departement_id'=>$pole,
                'haveRespDepart'=>$haveRespDep,
                'haveCollab'=>$haveCollab,
                'pathFile'=>NULL,
                'categorie_professionnelle_id'=>$categorieProf->id,
                'isDirecteur'=>$isDirecteur,
                'direction_id'=>$direction,
                'haveComputer'=>$hasComputer,
                'created_at' =>( new \DateTime())->format('Y-d-m H:i:s'),
                'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'),
            ];
            array_push($dataSalarie,$tab);
            if ($tab['haveComputer']==true && $tab['email']!=NULL){
                $user = [
                    'name' =>  $tab['nom']. ' ' .$tab['prenoms'],
                    'email' => strtolower($tab['email']),
                    'password' => Hash::make('lafarge1'),
                    'created_at' =>( new \DateTime())->format('Y-d-m H:i:s'),
                    'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'),
                ];
                array_push($tabUsers,$user);
            }
            continue;
        }

        try {
            DB::beginTransaction();

            DB::table('employes')->insert($dataSalarie);
            DB::table('users')->insert($tabUsers);

            foreach ($tabUsers as $user){
                $user = User::where('email', $user['email'])->first();
                $employe = Employe::where('email',$user->email)->first();
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

            DB::commit();

            $code = 201;
            $message = 'La liste des salariés contenant '.count($dataSalarie).' salarié(s) a été importée avec succès dans la base de données en production';
            goto Finish;

        }catch (\Exception $exception){
            dd($exception->getMessage());
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec importation des salaries',
                'message' => $exception->getMessage().' à la ligne '.$exception->getLine(),
                'user' => $request->user()->name,
                'controller' => 'ImportationData',
                'methode' => 'importSalarie'
            ];

            DB::rollBack();
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de l\'importation. Contactez IT']);

        }

        Finish:
        if ($code!=201){
            $message ='Importation annulée car '.$message;
        }
        return GlobalResource::make(['code'=>$code,'message'=> $message]);
    }

}
