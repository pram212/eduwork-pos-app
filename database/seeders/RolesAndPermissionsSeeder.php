<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'ubah produk']);
        Permission::create(['name' => 'hapus produk']);
        Permission::create(['name' => 'tambah produk']);
        Permission::create(['name' => 'lihat produk']);

        // or may be done by chaining
        $role = Role::create(['name' => 'admin'])
            ->givePermissionTo(['lihat produk', 'tambah produk', 'ubah produk', 'hapus produk']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

    }
}
