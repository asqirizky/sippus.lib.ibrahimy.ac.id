<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat Permissions
        Permission::create(['name' => 'atur pengguna']);
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'delete posts']);
        Permission::create(['name' => 'view reports']);
        // Berikan izin langsung ke user tertentu
        $admin = User::where('username', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo(['atur pengguna', 'edit posts', 'delete posts', 'view reports']);
        }
    }
}
