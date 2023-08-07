<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'dashboard']);

        Permission::create(['name' => 'roles.index']);
        Permission::create(['name' => 'roles.store']);
        Permission::create(['name' => 'roles.create']);
        Permission::create(['name' => 'roles.show']);
        Permission::create(['name' => 'roles.update']);
        Permission::create(['name' => 'roles.destroy']);
    }
}
