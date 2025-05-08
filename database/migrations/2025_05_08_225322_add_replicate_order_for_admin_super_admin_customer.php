<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $superAdmin = Role::findByName('super_admin');
        $admin = Role::findByName('admin');
        $customer = Role::findByName('customer');

        $permission = Permission::where('name', 'replicate_order')->first();

        $admin->permissions()->attach($permission->id);
        $superAdmin->permissions()->attach($permission->id);
        $customer->permissions()->attach($permission->id);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $superAdmin = Role::findByName('super_admin');
        $admin = Role::findByName('admin');
        $customer = Role::findByName('customer');

        $permission = Permission::where('name', 'replicate_order')->first();

        $admin->permissions()->detach($permission->id);
        $superAdmin->permissions()->detach($permission->id);
        $customer->permissions()->detach($permission->id);
    }
};
