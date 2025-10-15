<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@manager.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Actor1',
                'email' => 'actor1@actor.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Actor2',
                'email' => 'actor2@actor.com',
                'password' => bcrypt('password'),
            ],
        ];

        User::insert($users);

        $managers = User::whereIn('name', ['Admin', 'Manager'])->get();
        $actors = User::whereIn('name', ['Actor1', 'Actor2'])->get();

        $managerRoles = Role::create(['name' => 'Manager']);
        $actorRoles = Role::create(['name' => 'Actor']);

        $permissions = Permission::pluck('id')->all();

        $managerRoles->syncPermissions($permissions);
        $managers->each(fn($manager) => $manager->assignRole([$managerRoles->id]));

        $actorRoles->syncPermissions(['tasks-update-status', 'tasks-index', 'tasks-show']);
        $actors->each(fn($actor) => $actor->assignRole([$actorRoles->id]));
    }
}
