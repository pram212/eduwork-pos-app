<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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

        $dahsboardPermission = ['name' => 'lihat dahsboard'];
        $productPermissions = [ 'lihat produk', 'tambah produk', 'edit produk', 'hapus produk'];
        $categoryPermissions = [ 'lihat kategori', 'tambah kategori', 'edit kategori', 'hapus kategori'];
        $warehousePermissions = [ 'lihat gudang', 'tambah gudang', 'edit gudang', 'hapus gudang'];
        $supplierPermissions = [ 'lihat supplier', 'tambah supplier', 'edit supplier', 'hapus supplier'];
        $userPermissions = [ 'lihat user', 'tambah user', 'edit user', 'hapus user'];
        $activityPermissions = ['lihat aktivity'];
        $salePermissons = ['lihat penjualan', 'tambah penjualan', 'edit penjualan', 'hapus penjualan'];
        $purchasePermissons = ['lihat pembelian', 'tambah pembelian', 'edit pembelian', 'hapus pembelian'];
        $reportPermissions = ['lihat laporan penjualan', 'lihat laporan pembelian'];

        // create permissions
        foreach ($dahsboardPermission as $key => $value) {
            Permission::create(['name' => $value]);
        }
        foreach ($productPermissions as $key => $value) {
            Permission::create(['name' => $value]);
        }
        foreach ($categoryPermissions as $key => $value) {
            Permission::create(['name' => $value]);
        }
        foreach ($warehousePermissions as $key => $value) {
            Permission::create(['name' => $value]);
        }
        foreach ($supplierPermissions as $key => $value) {
            Permission::create(['name' => $value]);
        }
        foreach ($userPermissions as $key => $value) {
            Permission::create(['name' => $value]);
        }
        foreach ($activityPermissions as $key => $value) {
            Permission::create(['name' => $value]);
        }
        foreach ($salePermissons as $key => $value) {
            Permission::create(['name' => $value]);
        }
        foreach ($purchasePermissons as $key => $value) {
            Permission::create(['name' => $value]);
        }
        foreach ($reportPermissions as $key => $value) {
            Permission::create(['name' => $value]);
        }

        $users = [
            [
                'name' => 'pramono',
                'email' => 'pramono@mail.com',
                'password' => Hash::make('pramono'),
                'phone' => '0857677677',
                'address' => 'Bandung',
            ],
            [
                'name' => 'dimas',
                'email' => 'dimas@mail.com',
                'password' => Hash::make('dimas'),
                'phone' => '087734638',
                'address' => 'Yogyakarta',
            ],
            [
                'name' => 'iwan',
                'email' => 'iwan@mail.com',
                'password' => Hash::make('iwan'),
                'phone' => '0883478770',
                'address' => 'Bandung',
            ],

        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'phone' => $user['phone'],
                'address' => $user['address'],
            ]);
        }

        $superRole = Role::create(['name' => 'super admin']);
        $superRole->givePermissionTo(Permission::all());

        $cashier = Role::create(['name' => 'kasir']);
        $cashier->givePermissionTo($salePermissons);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo($productPermissions);
        $admin->givePermissionTo($categoryPermissions);
        $admin->givePermissionTo($warehousePermissions);
        $admin->givePermissionTo($supplierPermissions);
        $admin->givePermissionTo($userPermissions);
        $admin->givePermissionTo($purchasePermissons);
        $admin->givePermissionTo($reportPermissions);

        $superUser = User::where('name', 'pramono')->first();
        $superUser->assignRole('super admin');

        $cashierUser = User::where('name', 'iwan')->first();
        $cashierUser->assignRole('kasir');

        $adminUser = User::where('name', 'dimas')->first();
        $adminUser->assignRole('admin');

    }
}
