<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      //   \App\Models\User::factory(3)->create();
       $tab = [
            [
                'name'=>'ROLE_RH',
                'description'=>'Ce rôle est destiné aux rh pour effectuer des actions spécifique dans le logiciel'
            ],
            [
                'name'=>'ROLE_CODI',
                'description'=>'Rôle permettant de valider les évaluations au CODI'
            ],
            [
                'name'=>'ROLE_USER',
                'description'=>'Ce rôle est destiné est aux utilisateurs simple du logiciel pour faire des consultations des ressources et créer certaines ressources dans la base de donnée'
            ],
            [
                'name'=>'ROLE_ADMIN',
                'description'=>'Ce rôle est destiné est aux administrateurs fonctionnel du logiciel pour faire des consultations des ressources et créer certaines ressources dans la base de donnée en plus des actions spécifique'
            ],
            [
                'name'=>'ROLE_ADMIN_TECHNIQUE',
                'description'=>'Ce rôle est destiné est aux administrateurs technique du logiciel pour faire des consultations des ressources et créer certaines ressources dans la base de donnée en plus des actions technique'
            ],
            [
                'name'=>'ROLE_RESPONSABLE',
                'description'=>'Ce rôle est destiné aux responsables (d\'unités, de service et département)'
            ],
            [
                'name'=>'ROLE_DIRECTEUR',
                'description'=>'Ce rôle est destiné aux directeurs'
            ],

        ];

        DB::table('roles')->insert($tab);

        DB::table('users')->insert([
            'name' => 'VICHOEDO Prosper',
            'email' => 'proviche@gmail.com',
            'password' => Hash::make('lafarge1'),
        ]);

        $roleRh = Role::where('name','ROLE_RH')->first();
        $roleUser = Role::where('name','ROLE_USER')->first();
        $roleAdmine = Role::where('name','ROLE_ADMIN')->first();
        $rolecodi    = Role::where('name','ROLE_CODI')->first();
        $roleAdmineTech = Role::where('name','ROLE_ADMIN_TECHNIQUE')->first();
        $user = User::where('email','proviche@gmail.com')->first();

        DB::table('role_user')->insert([
            'role_id'=>$roleUser->id,
            'user_id'=>$user->id,
        ]);

        DB::table('role_user')->insert([
            'role_id'=>$roleRh->id,
            'user_id'=>$user->id,
        ]);

        DB::table('role_user')->insert([
            'role_id'=>$roleAdmine->id,
            'user_id'=>$user->id,
        ]);

        DB::table('role_user')->insert([
            'role_id'=>$roleAdmineTech->id,
            'user_id'=>$user->id,
        ]);

        DB::table('annees')->insert([
            [
                'libelle'=>'2021',
            ],
            [
                'libelle'=>'2022',
            ],
            [
                'libelle'=>'2023',
            ],
            [
                'libelle'=>'2024',
            ],
            [
                'libelle'=>'2025',
            ],
            [
                'libelle'=>'2026',
            ],
        ]);

        DB::table('users')->insert([
            'name' => 'CAPO-CHICHI Ghislain',
            'email' => 'ghislain.capo-chichi@scb-lafarge.bj',
            'password' => Hash::make('lafarge1'),
        ]);

        $user1 = User::where('email','ghislain.capo-chichi@scb-lafarge.bj')->first();

        DB::table('role_user')->insert([
            'role_id'=>$roleRh->id,
            'user_id'=>$user1->id,
        ]);

        DB::table('role_user')->insert([
            'role_id'=>$roleAdmine->id,
            'user_id'=>$user1->id,
        ]);
        DB::table('role_user')->insert([
            'role_id'=>$roleUser->id,
            'user_id'=>$user1->id,
        ]);

      /*  DB::table('role_user')->insert([
            'role_id'=>$rolecodi->id,
            'user_id'=>$user1->id,
        ]);*/



    }
}
