<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Identity\Enums\RoleCode;
use App\Domains\Identity\Models\Permission;
use App\Domains\Identity\Models\Role;
use Illuminate\Database\Seeder;

final class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roleDefinitions = [
            [
                'code' => RoleCode::SuperAdmin->value,
                'name' => RoleCode::SuperAdmin->label(),
                'description' => 'Full system access. Cannot be removed.',
                'level' => RoleCode::SuperAdmin->level(),
                'is_default' => false,
                'is_protected' => true,
                'permissions' => ['*'],
            ],
            [
                'code' => RoleCode::Admin->value,
                'name' => RoleCode::Admin->label(),
                'description' => 'Administrative access to most resources.',
                'level' => RoleCode::Admin->level(),
                'is_default' => false,
                'is_protected' => true,
                'permissions' => [
                    'catalog.read', 'catalog.write',
                    'cms.read', 'cms.write',
                    'blog.read', 'blog.write',
                    'orders.read',
                    'customers.read',
                    'reviews.moderate',
                    'settings.read',
                ],
            ],
            [
                'code' => RoleCode::Manager->value,
                'name' => RoleCode::Manager->label(),
                'description' => 'Manages day-to-day operations.',
                'level' => RoleCode::Manager->level(),
                'is_default' => false,
                'is_protected' => false,
                'permissions' => [
                    'catalog.read', 'catalog.write',
                    'orders.read',
                    'customers.read',
                ],
            ],
            [
                'code' => RoleCode::Editor->value,
                'name' => RoleCode::Editor->label(),
                'description' => 'Manages CMS pages and blog content.',
                'level' => RoleCode::Editor->level(),
                'is_default' => false,
                'is_protected' => false,
                'permissions' => [
                    'cms.read', 'cms.write',
                    'blog.read', 'blog.write',
                ],
            ],
            [
                'code' => RoleCode::Reviewer->value,
                'name' => RoleCode::Reviewer->label(),
                'description' => 'Moderates customer reviews.',
                'level' => RoleCode::Reviewer->level(),
                'is_default' => false,
                'is_protected' => false,
                'permissions' => [
                    'reviews.read', 'reviews.moderate',
                ],
            ],
            [
                'code' => RoleCode::Customer->value,
                'name' => RoleCode::Customer->label(),
                'description' => 'Default role for registered storefront users.',
                'level' => RoleCode::Customer->level(),
                'is_default' => true,
                'is_protected' => true,
                'permissions' => [
                    'profile.read', 'profile.write',
                    'wishlist.read', 'wishlist.write',
                    'reviews.read', 'reviews.write',
                ],
            ],
        ];

        /** @var array<string, int> $permissionIdsByCode */
        $permissionIdsByCode = [];

        foreach ($roleDefinitions as $definition) {
            /** @var Role $role */
            $role = Role::updateOrCreate(
                ['code' => $definition['code']],
                [
                    'name' => $definition['name'],
                    'description' => $definition['description'],
                    'level' => $definition['level'],
                    'is_default' => $definition['is_default'],
                    'is_protected' => $definition['is_protected'],
                ],
            );

            $resolvedPermissionIds = [];

            foreach ($definition['permissions'] as $permissionCode) {
                $parts = explode('.', $permissionCode, 2);
                $group = $parts[0] ?? 'general';

                if (! isset($permissionIdsByCode[$permissionCode])) {
                    /** @var Permission $permission */
                    $permission = Permission::updateOrCreate(
                        ['code' => $permissionCode],
                        [
                            'name' => $permissionCode,
                            'group' => $group,
                            'description' => null,
                            'is_protected' => $permissionCode === '*',
                        ],
                    );
                    $permissionIdsByCode[$permissionCode] = $permission->id;
                }

                $resolvedPermissionIds[] = $permissionIdsByCode[$permissionCode];
            }

            $role->permissions()->sync($resolvedPermissionIds);
        }
    }
}
