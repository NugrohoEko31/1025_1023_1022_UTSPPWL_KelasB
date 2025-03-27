<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Role jika belum ada
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $operator = Role::firstOrCreate(['name' => 'Operator']);

        // Ambil semua permissions dari database
        $allPermissions = Permission::pluck('name')->toArray();

        // Berikan semua permission ke Super Admin
        $superAdmin->syncPermissions($allPermissions);

        // Berikan izin tertentu ke Admin
        $admin->syncPermissions([
            'create-user',
            'edit-user',
            'delete-user',
        ]);

        // Berikan izin CRUD Product ke Operator
        $operator->syncPermissions([
            'create-product',
            'edit-product',
            'delete-product',
        ]);
    }
}
