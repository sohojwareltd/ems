<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use App\Models\Permission;

class SyncFilamentPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync-filament';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync or create permissions for all FilamentResource classes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filesystem = new Filesystem();
        $resourcePath = app_path('Filament/Resources');
        $resourceFiles = $filesystem->allFiles($resourcePath);

        $crudActions = ['view', 'create', 'edit', 'delete'];
        $created = 0;
        $synced = 0;

        foreach ($resourceFiles as $file) {
            if (!Str::endsWith($file->getFilename(), 'Resource.php')) {
                continue;
            }
            $className = $file->getBasename('.php');
            $resource = Str::replaceLast('Resource', '', $className);
            $resourceSlug = Str::kebab($resource); // e.g., 'User' => 'user'
            $group = $resourceSlug;

            foreach ($crudActions as $action) {
                $permName = $resourceSlug . '.' . $action;
                $displayName = ucfirst($action) . ' ' . Str::headline($resource);
                $description = 'Can ' . $action . ' ' . Str::headline($resource);

                $permission = Permission::firstOrCreate(
                    ['name' => $permName],
                    [
                        'display_name' => $displayName,
                        'description' => $description,
                        'group' => $group,
                    ]
                );
                if ($permission->wasRecentlyCreated) {
                    $created++;
                } else {
                    $synced++;
                }
            }
        }

        $this->info("Permissions synced. Created: $created, Updated: $synced");
    }
}
