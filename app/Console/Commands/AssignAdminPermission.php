<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;

class AssignAdminPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:assign-panel-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign admin.panel.access permission to admin role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $adminPermission = Permission::where('name', 'admin.panel.access')->first();

        if (!$adminRole) {
            $this->error('Admin role not found!');
            return 1;
        }

        if (!$adminPermission) {
            $this->error('admin.panel.access permission not found!');
            return 1;
        }

        $adminRole->permissions()->syncWithoutDetaching([$adminPermission->id]);
        
        $this->info('Admin role now has admin.panel.access permission!');
        return 0;
    }
}
