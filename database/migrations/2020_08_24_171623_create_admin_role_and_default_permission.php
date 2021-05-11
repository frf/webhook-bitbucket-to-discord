<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminRoleAndDefaultPermission extends Migration
{
    public $defaultPermissions = [
        'edit users',
        'delete users',
        'create users',
        'list users',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($this->defaultPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::truncate();
        Permission::truncate();
    }
}
