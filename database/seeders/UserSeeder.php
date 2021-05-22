<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Pro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rol=Role::all();
        if(count($rol)==0)
        {
        $role1 = Role::create(['name' => 'user']);
        $role2 = Role::create(['name' => 'writer']);
        }
        $role1=Role::where('name', '=', 'user');
        $role2=Role::where('name', '=', 'writer');
        $roles=array($role1,$role2);
        User::factory()->count(20)->create()
        ->assignRole($roles[mt_rand(0,1)])
        ->each(function ($user)
        	{
        		Pro::factory()->count(rand(1,5))->create(
        			['user_id'=> $user->id])
        		->each(function ($pro)
        		{   
        			$tag_ids = range(1,8);
        			shuffle($tag_ids);
        			$assign = array_slice($tag_ids,0,rand(1,8));
        			foreach ($assign as $tag_id ) {
        				DB::table('pro_tag')
        				->insert(
        					['pro_id'=>$pro->id,
        					'tag_id'=>$tag_id,
        					'created_at'=>Now(),
        					'updated_at'=>Now(),
        					]);

        			}
        		});
        	});
        $this->call([
            PermissionSeeder::class,
        ]);
    }
}
