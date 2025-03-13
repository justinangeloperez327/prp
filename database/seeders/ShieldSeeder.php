<?php

namespace Database\Seeders;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_role","view_any_role","create_role","update_role","delete_role","delete_any_role", "view_order","view_any_order","create_order","update_order","delete_order","delete_any_order",
        "page_ProcessedOrders", "page_OverdueOrders", "page_OnHoldOrders", "page_NewOrders", "page_CancelledOrders", "page_CurrentOrders",
        "view_user","view_any_user","create_user","update_user","delete_user","delete_any_user",
        "view_product","view_any_product","create_product","update_product","delete_product","delete_any_product",
        "view_product::category","view_any_product::category","create_product::category","update_product::category","delete_product::category","delete_any_product::category",
        "view_product::item","view_any_product::item","create_product::item","update_product::item","delete_product::item","delete_any_product::item",
        "view_contact","view_any_contact","create_contact", "update_contact","delete_contact","delete_any_contact",
        "view_customer","view_any_customer","create_customer","update_customer","delete_customer","delete_any_customer"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Super Admin Role Created');

        // Create Admin Role
        $adminRolesWithPermissions = '[{"name":"admin","guard_name":"web","permissions":["view_order","view_any_order","create_order","update_order","delete_order","delete_any_order",
        "page_ProcessedOrders", "page_OverdueOrders", "page_OnHoldOrders", "page_NewOrders", "page_CancelledOrders", "page_CurrentOrders",
        "view_product","view_any_product","create_product","update_product","delete_product","delete_any_product",
        "view_product::category","view_any_product::category","create_product::category","update_product::category","delete_product::category","delete_any_product::category",
        "view_product::item","view_any_product::item","create_product::item","update_product::item","delete_product::item","delete_any_product::item",
        "view_contact","view_any_contact","create_contact", "update_contact","delete_contact","delete_any_contact",
        "view_customer","view_any_customer","create_customer","update_customer","delete_customer","delete_any_customer"]}]';

        static::makeRolesWithPermissions($adminRolesWithPermissions);
        $this->command->info('Admin Role Created');
        //
        $customerRolesWithPermissions = '[{"name":"customer","guard_name":"web","permissions":["view_order","view_any_order","create_order","update_order","delete_order","delete_any_order", "page_ProcessedOrders"]}]';
        static::makeRolesWithPermissions($customerRolesWithPermissions);
        $this->command->info('Customer Role Created');

        //Super
        $super = User::where('email', 'super@gmail.com')->first();

        $super = User::create([
            'name' => 'super admin',
            'email' => 'super@gmail.com',
            'password' => Hash::make('super'),
            'remember_token' => Str::random(10),
        ]);

        $super->assignRole('super_admin');
        $this->command->info('Super Admin User Created');

        //Admin
        $admin = User::where('email', 'admin@gmail.com')->first();

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
        ]);

        $admin->assignRole('admin');
        $this->command->info('Admin User Created');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
