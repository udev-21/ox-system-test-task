<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@example.com',
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@example.com',
            ],
        ];
        foreach($users as $user){
            \App\Models\User::factory()->create($user);
        }

        $role_permissions = [
            'admin' => [
                'create'=> [
                    'feature',
                    'category',
                    'category_feature',
                    'product',
                    'product_feature',
                    'product_feature_value',
                ],
                'read'=> [
                    'feature',
                    'category',
                    'category_feature',
                    'product',
                    'product_feature',
                    'product_feature_value',
                ],
                'update'=> [
                    'feature',
                    'category',
                    'category_feature',
                    'product',
                    'product_feature',
                    'product_feature_value',
                ],
                'delete'=> [
                    'feature',
                    'category',
                    'category_feature',
                    'product',
                    'product_feature',
                    'product_feature_value',
                ],
            ] , 
            'manager' => [
                'create'=> [
                    'product',
                    'product_feature',
                    'product_feature_value',
                ],
                'read'=> [
                    'feature',
                    'category',
                    'category_feature',
                    'product',
                    'product_feature',
                    'product_feature_value',
                ],
                'update'=> [
                    'product',
                    'product_feature',
                    'product_feature_value',
                ],
                'delete'=> [
                    'product',
                    'product_feature',
                    'product_feature_value',
                ],
            ], 
            'customer' => [
                'create'=> [],
                'read'=> [
                    'product',
                    'category',
                    'product_feature',
                    'product_feature_value',
                ],
                'update'=> [],
                'delete'=> [],
            ],
        ];
        foreach($role_permissions as $role => $actions){
            $role = Role::create(['name'=>$role]);
            foreach($actions as $action => $tables) {
                foreach($tables as $table) {
                    try{
                        $permission = Permission::create(['name'=>"can_{$action}_{$table}"]);
                    }catch(PermissionAlreadyExists $e){
                        $permission = Permission::where(['name'=>"can_{$action}_{$table}"]);
                    }
                    $role->givePermissionTo($permission);
                }
            }
        }
        
        User::find(1)->assignRole('admin');
        User::find(2)->assignRole('manager');
        User::find(3)->assignRole('customer');
    }
}
