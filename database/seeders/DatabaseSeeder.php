<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::findOrCreate('invitado');

        /* $role =   Role::findOrCreate('admin'); */
        /* $permission = Permission::findOrCreate('crear roles'); */
        /* $role->givePermissionTo('crear roles'); */
        /* $permission->assignRole($role); */

        // $this->call(RoleSeeder::class);
        // $this->call(PermisosSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
