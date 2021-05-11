<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SyncRolesPermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (config('acl.roles') as $role => $permissions) {
            $role = \Spatie\Permission\Models\Role::findOrCreate($role);

            foreach ($permissions as $permission) {
                $permission = \Spatie\Permission\Models\Permission::findOrCreate($permission);
                $role->givePermissionTo($permission);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        echo "do it manually";
    }
}
