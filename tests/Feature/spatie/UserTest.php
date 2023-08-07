<?php

namespace Tests\Feature\spatie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $role =   Role::findOrCreate('admin');
        $permission = Permission::findOrCreate('crear roles');
        $role->givePermissionTo('crear roles');
        $permission->assignRole($role);
        $this->assertTrue(true);
    }
}
