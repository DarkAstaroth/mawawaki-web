<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

// Roles
Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->push('Roles', route('roles.index'));
});

Breadcrumbs::for('roles.permisos', function (BreadcrumbTrail $trail, Role $rol) {
    $trail->parent('roles');
    $trail->push('Asignar permisos');
    $trail->push($rol->name, route('roles.index', $rol));
});


// Usuarios
Breadcrumbs::for('usuarios', function (BreadcrumbTrail $trail) {
    $trail->push('Todos los Usuarios', route('usuarios.index'));
});

