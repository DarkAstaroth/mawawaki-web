<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\User;
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
        // Crear roles
        Role::findOrCreate('invitado');
        Role::findOrCreate('cliente');
        Role::findOrCreate('personal');

        // Crear el rol de administrador y asignarle un permiso
        $adminRole = Role::findOrCreate('admin');
        $crearRolesPermission = Permission::findOrCreate('crear roles');
        $adminRole->givePermissionTo('crear roles');
        $crearRolesPermission->assignRole($adminRole);

        // Crear una persona y un usuario con el rol de administrador
        $adminPersona = Persona::factory()->create([
            'nombre' => 'Victor Manuel',
            'paterno' => 'Ninahuanca',
            'materno' => 'Ayala',
            'fecha_nacimiento' => strtotime('1980-01-01'),
            'genero' => 'masculino',
            'telefono' => '123456789',
            'direccion' => 'Calle Principal, Ciudad',
            'email' => 'mninahuanca3@gmail.com',
            'ci' => '1234567',
        ]);

        $adminUser = User::factory()->create([
            'name' => 'AdminUser',
            'email' => 'mninahuanca3@gmail.com',
            'solicitud' => 1,
            'verificada' => 1,
            'estado' => 1,
            'profile_photo_path' => 'assets/imagenes/user-default.jpg',
            'password' => bcrypt('fhccfnxdy2'),
            'persona_id' => $adminPersona->id,
        ]);
        $adminUser->assignRole($adminRole);

        // Crear una persona y un usuario con el rol de cliente y la misma contraseña
        $clientePersona = Persona::factory()->create([
            'nombre' => 'Juan',
            'paterno' => 'Perez',
            'materno' => 'Gonzales',
            'fecha_nacimiento' => strtotime('1995-05-10'),
            'genero' => 'femenino',
            'telefono' => '987654321',
            'direccion' => 'Calle Secundaria, Ciudad',
            'email' => 'cliente@example.com',
            'ci' => '7654321',
        ]);

        $clienteUser = User::factory()->create([
            'name' => 'JuanUser',
            'email' => 'cliente@example.com',
            'solicitud' => 1,
            'verificada' => 1,
            'estado' => 1,
            'profile_photo_path' => 'assets/imagenes/user-default.jpg',
            'password' => bcrypt('fhccfnxdy2'),
            'persona_id' => $clientePersona->id,
        ]);
        $clienteUser->assignRole('cliente');

        // Seeder para la tabla 'clientes'
        DB::table('clientes')->insert([
            'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'UsuarioID' => $clienteUser->id,
            'ocupacion' => 'profesor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Otros seeders o factory calls
        // $this->call(RoleSeeder::class);
        // $this->call(PermisosSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
