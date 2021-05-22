<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $admin=new User([
         	'name' => "divya",
            'email' => 'sridivyapervela357@gmail.com',
            'role'=>'admin',
            'email_verified_at' => now(),
            'password' =>Hash::make('password1'), // password
            'remember_token' => Str::random(10),
        ]);
        $admin->save();
        $admin->givePermissionTo('read projects');
        $admin->givePermissionTo('create projects');
        $admin->givePermissionTo('edit projects');
        $admin->givePermissionTo('delete projects');
    }

}
