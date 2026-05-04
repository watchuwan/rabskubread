<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $tenants = '[]';
        $users = '[{"id":1,"name":"Super Admin","email":"superadmin@tokroti.com","email_verified_at":"2026-04-30T15:16:54.000000Z","is_active":true,"last_login_at":null,"created_at":"2026-04-30T15:16:54.000000Z","updated_at":"2026-04-30T15:16:54.000000Z","password":"$2y$12$IUOBLrvHRDKDVvN7AvPt1e7cYQeRrmZNbkNmFfENI2xXaKzC7rYta","roles":["super_admin"]},{"id":2,"name":"Admin Staff","email":"adminstaff@tokroti.com","email_verified_at":"2026-04-30T15:16:54.000000Z","is_active":true,"last_login_at":null,"created_at":"2026-04-30T15:16:54.000000Z","updated_at":"2026-04-30T15:16:54.000000Z","password":"$2y$12$lT65bS3WAS\\/zCf1k2da2ieugxivhZ0yxsAbJIBK1hQ\\/sehWWbt5ry","roles":["admin_staff"]},{"id":3,"name":"Staff","email":"staff@tokroti.com","email_verified_at":"2026-04-30T15:16:54.000000Z","is_active":true,"last_login_at":null,"created_at":"2026-04-30T15:16:54.000000Z","updated_at":"2026-04-30T15:16:54.000000Z","password":"$2y$12$f.P1d\\/B\\/zP4Dq9EbMGX0teBHKJyffIAO7ZM7EcbE8lZx0iJwCM9ZK","roles":["staff"]}]';
        $userTenantPivot = '[]';
        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["ViewAny:Address","View:Address","Create:Address","Update:Address","Delete:Address","Restore:Address","ForceDelete:Address","ForceDeleteAny:Address","RestoreAny:Address","Replicate:Address","Reorder:Address","ViewAny:Cart","View:Cart","Create:Cart","Update:Cart","Delete:Cart","Restore:Cart","ForceDelete:Cart","ForceDeleteAny:Cart","RestoreAny:Cart","Replicate:Cart","Reorder:Cart","ViewAny:Category","View:Category","Create:Category","Update:Category","Delete:Category","Restore:Category","ForceDelete:Category","ForceDeleteAny:Category","RestoreAny:Category","Replicate:Category","Reorder:Category","ViewAny:ContactMessage","View:ContactMessage","Create:ContactMessage","Update:ContactMessage","Delete:ContactMessage","Restore:ContactMessage","ForceDelete:ContactMessage","ForceDeleteAny:ContactMessage","RestoreAny:ContactMessage","Replicate:ContactMessage","Reorder:ContactMessage","ViewAny:Customer","View:Customer","Create:Customer","Update:Customer","Delete:Customer","Restore:Customer","ForceDelete:Customer","ForceDeleteAny:Customer","RestoreAny:Customer","Replicate:Customer","Reorder:Customer","ViewAny:Faq","View:Faq","Create:Faq","Update:Faq","Delete:Faq","Restore:Faq","ForceDelete:Faq","ForceDeleteAny:Faq","RestoreAny:Faq","Replicate:Faq","Reorder:Faq","ViewAny:Order","View:Order","Create:Order","Update:Order","Delete:Order","Restore:Order","ForceDelete:Order","ForceDeleteAny:Order","RestoreAny:Order","Replicate:Order","Reorder:Order","ViewAny:Payment","View:Payment","Create:Payment","Update:Payment","Delete:Payment","Restore:Payment","ForceDelete:Payment","ForceDeleteAny:Payment","RestoreAny:Payment","Replicate:Payment","Reorder:Payment","ViewAny:ProductReview","View:ProductReview","Create:ProductReview","Update:ProductReview","Delete:ProductReview","Restore:ProductReview","ForceDelete:ProductReview","ForceDeleteAny:ProductReview","RestoreAny:ProductReview","Replicate:ProductReview","Reorder:ProductReview","ViewAny:Product","View:Product","Create:Product","Update:Product","Delete:Product","Restore:Product","ForceDelete:Product","ForceDeleteAny:Product","RestoreAny:Product","Replicate:Product","Reorder:Product","ViewAny:Setting","View:Setting","Create:Setting","Update:Setting","Delete:Setting","Restore:Setting","ForceDelete:Setting","ForceDeleteAny:Setting","RestoreAny:Setting","Replicate:Setting","Reorder:Setting","ViewAny:ShippingMethod","View:ShippingMethod","Create:ShippingMethod","Update:ShippingMethod","Delete:ShippingMethod","Restore:ShippingMethod","ForceDelete:ShippingMethod","ForceDeleteAny:ShippingMethod","RestoreAny:ShippingMethod","Replicate:ShippingMethod","Reorder:ShippingMethod","ViewAny:User","View:User","Create:User","Update:User","Delete:User","Restore:User","ForceDelete:User","ForceDeleteAny:User","RestoreAny:User","Replicate:User","Reorder:User","ViewAny:Voucher","View:Voucher","Create:Voucher","Update:Voucher","Delete:Voucher","Restore:Voucher","ForceDelete:Voucher","ForceDeleteAny:Voucher","RestoreAny:Voucher","Replicate:Voucher","Reorder:Voucher","ViewAny:Wishlist","View:Wishlist","Create:Wishlist","Update:Wishlist","Delete:Wishlist","Restore:Wishlist","ForceDelete:Wishlist","ForceDeleteAny:Wishlist","RestoreAny:Wishlist","Replicate:Wishlist","Reorder:Wishlist","ViewAny:Role","View:Role","Create:Role","Update:Role","Delete:Role","Restore:Role","ForceDelete:Role","ForceDeleteAny:Role","RestoreAny:Role","Replicate:Role","Reorder:Role","View:CustomerGrowthChart","View:RevenueStats","View:StatsOverview","View:TopCustomersWidget","View:OrderStatusChart","View:RevenueByCategoryChart","View:TopProducts","View:OrdersChart"]},{"name":"admin_staff","guard_name":"web","permissions":["ViewAny:Order","View:Order","Create:Order","Update:Order","Delete:Order","Restore:Order","RestoreAny:Order","ViewAny:Payment","View:Payment","Update:Payment","ViewAny:Product","View:Product","Create:Product","Update:Product","Delete:Product","Restore:Product","RestoreAny:Product","Replicate:Product","ViewAny:Category","View:Category","Create:Category","Update:Category","Delete:Category","Restore:Category","RestoreAny:Category","ViewAny:Customer","View:Customer","Create:Customer","Update:Customer","Delete:Customer","Restore:Customer","RestoreAny:Customer","ViewAny:Address","View:Address","Create:Address","Update:Address","Delete:Address","ViewAny:Cart","View:Cart","ViewAny:Wishlist","View:Wishlist","ViewAny:Voucher","View:Voucher","Create:Voucher","Update:Voucher","Delete:Voucher","ViewAny:ProductReview","View:ProductReview","Update:ProductReview","Delete:ProductReview","ViewAny:ContactMessage","View:ContactMessage","Update:ContactMessage","Delete:ContactMessage","ViewAny:Faq","View:Faq","Create:Faq","Update:Faq","Delete:Faq","ViewAny:ShippingMethod","View:ShippingMethod","View:StatsOverview","View:RevenueStats","View:OrderStatusChart","View:OrdersChart","View:TopProducts","View:RevenueByCategoryChart","View:CustomerGrowthChart","View:TopCustomersWidget"]},{"name":"staff","guard_name":"web","permissions":["ViewAny:Order","View:Order","Update:Order","ViewAny:Payment","View:Payment","Update:Payment","ViewAny:Product","View:Product","ViewAny:Category","View:Category","ViewAny:Customer","View:Customer","ViewAny:Address","View:Address","ViewAny:ContactMessage","View:ContactMessage","Update:ContactMessage","View:StatsOverview","View:OrderStatusChart","View:OrdersChart","View:TopProducts"]}]';
        $directPermissions = '[]';

        // 1. Seed tenants first (if present)
        if (! blank($tenants) && $tenants !== '[]') {
            static::seedTenants($tenants);
        }

        // 2. Seed roles with permissions
        static::makeRolesWithPermissions($rolesWithPermissions);

        // 3. Seed direct permissions
        static::makeDirectPermissions($directPermissions);

        // 4. Seed users with their roles/permissions (if present)
        if (! blank($users) && $users !== '[]') {
            static::seedUsers($users);
        }

        // 5. Seed user-tenant pivot (if present)
        if (! blank($userTenantPivot) && $userTenantPivot !== '[]') {
            static::seedUserTenantPivot($userTenantPivot);
        }

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function seedTenants(string $tenants): void
    {
        if (blank($tenantData = json_decode($tenants, true))) {
            return;
        }

        $tenantModel = '';
        if (blank($tenantModel)) {
            return;
        }

        foreach ($tenantData as $tenant) {
            $tenantModel::firstOrCreate(
                ['id' => $tenant['id']],
                $tenant
            );
        }
    }

    protected static function seedUsers(string $users): void
    {
        if (blank($userData = json_decode($users, true))) {
            return;
        }

        $userModel = 'App\Models\User';
        $tenancyEnabled = false;

        foreach ($userData as $data) {
            // Extract role/permission data before creating user
            $roles = $data['roles'] ?? [];
            $permissions = $data['permissions'] ?? [];
            $tenantRoles = $data['tenant_roles'] ?? [];
            $tenantPermissions = $data['tenant_permissions'] ?? [];
            unset($data['roles'], $data['permissions'], $data['tenant_roles'], $data['tenant_permissions']);

            $user = $userModel::firstOrCreate(
                ['email' => $data['email']],
                $data
            );

            // Handle tenancy mode - sync roles/permissions per tenant
            if ($tenancyEnabled && (! empty($tenantRoles) || ! empty($tenantPermissions))) {
                foreach ($tenantRoles as $tenantId => $roleNames) {
                    $contextId = $tenantId === '_global' ? null : $tenantId;
                    setPermissionsTeamId($contextId);
                    $user->syncRoles($roleNames);
                }

                foreach ($tenantPermissions as $tenantId => $permissionNames) {
                    $contextId = $tenantId === '_global' ? null : $tenantId;
                    setPermissionsTeamId($contextId);
                    $user->syncPermissions($permissionNames);
                }
            } else {
                // Non-tenancy mode
                if (! empty($roles)) {
                    $user->syncRoles($roles);
                }

                if (! empty($permissions)) {
                    $user->syncPermissions($permissions);
                }
            }
        }
    }

    protected static function seedUserTenantPivot(string $pivot): void
    {
        if (blank($pivotData = json_decode($pivot, true))) {
            return;
        }

        $pivotTable = '';
        if (blank($pivotTable)) {
            return;
        }

        foreach ($pivotData as $row) {
            $uniqueKeys = [];

            if (isset($row['user_id'])) {
                $uniqueKeys['user_id'] = $row['user_id'];
            }

            $tenantForeignKey = 'team_id';
            if (! blank($tenantForeignKey) && isset($row[$tenantForeignKey])) {
                $uniqueKeys[$tenantForeignKey] = $row[$tenantForeignKey];
            }

            if (! empty($uniqueKeys)) {
                DB::table($pivotTable)->updateOrInsert($uniqueKeys, $row);
            }
        }
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            return;
        }

        /** @var \Illuminate\Database\Eloquent\Model $roleModel */
        $roleModel = Utils::getRoleModel();
        /** @var \Illuminate\Database\Eloquent\Model $permissionModel */
        $permissionModel = Utils::getPermissionModel();

        $tenancyEnabled = false;
        $teamForeignKey = 'team_id';

        foreach ($rolePlusPermissions as $rolePlusPermission) {
            $tenantId = $rolePlusPermission[$teamForeignKey] ?? null;

            // Set tenant context for role creation and permission sync
            if ($tenancyEnabled) {
                setPermissionsTeamId($tenantId);
            }

            $roleData = [
                'name' => $rolePlusPermission['name'],
                'guard_name' => $rolePlusPermission['guard_name'],
            ];

            // Include tenant ID in role data (can be null for global roles)
            if ($tenancyEnabled && ! blank($teamForeignKey)) {
                $roleData[$teamForeignKey] = $tenantId;
            }

            $role = $roleModel::firstOrCreate($roleData);

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

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (blank($permissions = json_decode($directPermissions, true))) {
            return;
        }

        /** @var \Illuminate\Database\Eloquent\Model $permissionModel */
        $permissionModel = Utils::getPermissionModel();

        foreach ($permissions as $permission) {
            if ($permissionModel::whereName($permission['name'])->doesntExist()) {
                $permissionModel::create([
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name'],
                ]);
            }
        }
    }
}
