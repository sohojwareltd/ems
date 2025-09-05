<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define static permissions (add more as needed)
        $permissions = [
            [
                'name' => 'admin.panel.access',
                'display_name' => 'Access Admin Panel',
                'description' => 'Can access the admin panel',
                'group' => 'admin',
            ],
            [
                'name' => 'dashboard.view',
                'display_name' => 'View Dashboard',
                'description' => 'Can view dashboard widgets and statistics',
                'group' => 'dashboard',
            ],
            [
                'name' => 'dashboard.sales',
                'display_name' => 'View Sales Data',
                'description' => 'Can view sales charts and statistics',
                'group' => 'dashboard',
            ],
            [
                'name' => 'dashboard.orders',
                'display_name' => 'View Order Data',
                'description' => 'Can view order tables and statistics',
                'group' => 'dashboard',
            ],
            [
                'name' => 'dashboard.products',
                'display_name' => 'View Product Data',
                'description' => 'Can view product charts and stock alerts',
                'group' => 'dashboard',
            ],
            // ... you can keep or remove the static permissions below if you want only dynamic ones ...
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm['name'], $perm['display_name'], $perm['description'], $perm['group']);
        }

        // Dynamically generate CRUD permissions for all FilamentResource classes
        $filesystem = new Filesystem();
        $resourcePath = app_path('Filament/Resources');
        $resourceFiles = $filesystem->allFiles($resourcePath);
        $crudActions = ['view', 'create', 'edit', 'delete'];

        foreach ($resourceFiles as $file) {
            if (!Str::endsWith($file->getFilename(), 'Resource.php')) {
                continue;
            }
            $className = $file->getBasename('.php');
            $resource = Str::replaceLast('Resource', '', $className);
            $resourceSlug = Str::kebab($resource);
            $group = $resourceSlug;

            foreach ($crudActions as $action) {
                $permName = $resourceSlug . '.' . $action;
                $displayName = ucfirst($action) . ' ' . Str::headline($resource);
                $description = 'Can ' . $action . ' ' . Str::headline($resource);

                Permission::firstOrCreate(
                    ['name' => $permName],
                    [
                        'display_name' => $displayName,
                        'description' => $description,
                        'group' => $group,
                    ]
                );
            }
        }

        // Assign all permissions to admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->assignPermissions(Permission::all());
        }
    }
}
