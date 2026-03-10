<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Controllers\service\MouchardApp;
use App\Http\Resources\GlobalResource;
use App\Models\Employe;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends controller
{
    protected $mouchard;
    public function __construct(MouchardApp $mouchardApp)
    {
        $this->mouchard = $mouchardApp;
        $this->middleware(['admin'])->except(['authenticate','logout','resetPassword','miseAjour']);
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|',
        ], [
            'email.required'=>'Le champ email est obligatoire',
            'password.required'=>'Le champ mot de passe est obligatoire',
            'email.email'=>'Veuillez saisir une adresse email valide'
        ]);

        if (Auth::attempt($credentials)) {
            $success = true;
            $message = 'User login successfully';
            $user = User::with('roles')->where('email',$request->email)->first();
            $employe = Employe::with('unite')->where('email',$user->email)->first();
        } else {
            $success = false;
            $message = 'E-mail ou mot de passe incorrect';
            $user = '';
            $employe = '';
        }
        return response()->json(['success'=>$success,'message'=>$message,'user'=>$user,'employe'=>$employe]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->noContent();
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ],
        [
            'email.required'=>'Le champ email est obligatoire',
            'password.required'=>'Le champ mot de passe est obligatoire',
            'email.email'=>'Veuillez saisir une adresse email valide'
        ]);

        try {
            $user = User::where('email',$request->email)->first();
            /* if(!Hash::check($request->passwordAncien,$user->password)){
                $code = 500;
                $message = 'Ancien mot de passe incorrect';
                goto finish;
            }*/

            if($request->passwordConfirm != $request->password){
                $code = 500;
                $message = 'Veuillez bien confirmé le nouveau mot de passe';
                goto finish;
            }

            $user->isFistLogin = false;
            $user->password = Hash::make($request->password);
            $user->save();
            $code = 201;
            $message = '';
            goto finish;

            finish:
            return response()->json(['code'=>$code,'message'=>$message]);
        }catch (\Exception $exception){
            $tabInfo = [
                'login' => $request->user()->email,
                'action' => 'Echec changement de mot de passe',
                'message' => $exception->getMessage(),
                'user' => $request->user()->name,
                'controller' => 'Login',
                'methode' => 'resetPassword'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la réinitialisation. Contactez IT']);

        }

    }

    public function createUser(Request $request){
        try {
            $tabUser = $request->all();
            if (count($tabUser)==0){
                return GlobalResource::make(['code'=>500,'message'=>'Veuillez choisir des salarié pour qui on veut créer le compte']);
            }

            foreach ($tabUser as $value){
                $employe = Employe::where('email',$value['email'])->first();
                User::create([
                    'name'=>$employe->nom.' '.$employe->prenoms,
                    'email'=>$employe->email,
                    'password'=>Hash::make('lafarge1')
                ]);

                $user = User::where('email',$employe->email)->first();
                foreach ($value['tabRole'] as $item){
                    $user->roles()->attach([(int)$item => array('created_at' => (new \DateTime())->format('Y-d-m H:i:s'), 'updated_at' => (new \DateTime())->format('Y-d-m H:i:s'))]);
                }
                //$user->roles()->sync($value['tabRole']);
            }

            return GlobalResource::make(['code'=>201,'message'=>'compte créer avec succès !']);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec enregistrement compte utilisateur',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Login',
                'methode'=> 'createUser'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la création du compte. Contactez IT']);

        }
    }

    public function show($id){
        $user = User::with('roles')->find((int)$id);
        return GlobalResource::make($user);
    }

    public function editCompte(Request $request, $id){
        try {
            $data = $request->all();
            if (empty($data) or is_null($data)){
                return GlobalResource::make(['code'=>500,'message'=>'Aucun compte envoyé pour modification']);
            }
            if ($request->password != $request->passwordRepat){
                return GlobalResource::make(['code'=>500,'message'=>'Veuillez bien confirmer le mot de passe']);
            }
            $user = User::find($id);
            if ($request->password !='' & $request->password !=null){
                $user->password = Hash::make($request->password);
                $user->isFistLogin = true;
                $user->save();
            }

            $user->roles()->detach();
            $tabRoles = array();

            foreach ($request->roles as $role){
                array_push($tabRoles,[
                    'user_id'=>$user->id,
                    'role_id'=>$role,
                    'created_at'=>(new \DateTime())->format('Y-d-m H:i:s'),
                    'updated_at'=>(new \DateTime())->format('Y-d-m H:i:s')
                ]);
            }
            DB::table('role_user')->insert($tabRoles);
            // $user->roles()->sync($request->roles);

            return GlobalResource::make(['code'=>200,'message'=>'Modification effectuée avec succès']);
        }catch (\Exception $exception){
            $tabInfo = [
                'login'=> $request->user()->email,
                'action'=>'Echec modification compte user',
                'message'=>$exception->getMessage(),
                'user'=>$request->user()->name,
                'controller'=>'Login',
                'methode'=> 'editCompte'
            ];
            $this->mouchard->saveLogAction($tabInfo);

            return GlobalResource::make(['code' => 500, 'message' => 'Erreur ! Le serveur a rencontré un problème lors de la modification. Contactez IT']);

        }
    }

    public function listeUser(){
        return GlobalResource::collection(User::with('roles')->get());
    }

    public function destroy($id){

        $user = User::find((int)$id);
        $user->roles()->detach();
        $user->delete();

        return response()->noContent();
    }

    public function miseAjour()
    {
        $employes = Employe::all();
        DB::beginTransaction();
        $p = 0;
        foreach ($employes as $item){
            $employe = Employe::find($item->id);
            $employe->email = strtolower($employe->email);
            $employe->save();
            $p+=1;
        }
        DB::commit();
        dd($p.' lignes mise à jour');
    }
}
