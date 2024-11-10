<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $userRole = Role::create(['name' => 'user']);
        $managerRole = Role::create(['name' => 'manager']);
        $adminRole = Role::create(['name' => 'admin']);

        // Create permissions
        // $viewPosts = Permission::create(['name' => 'view posts']);
        // $createPosts = Permission::create(['name' => 'create posts']);
        // $editPosts = Permission::create(['name' => 'edit posts']);
        // $deletePosts = Permission::create(['name' => 'delete posts']);

        // // Assign permissions to roles
        // $userRole->givePermissionTo($viewPosts);
        // $managerRole->givePermissionTo([$viewPosts, $createPosts, $editPosts]);
        // $adminRole->givePermissionTo(Permission::all());
    }
}
