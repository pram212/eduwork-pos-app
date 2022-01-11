<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['penjualan', 'pembelian'];

        foreach ($types as $key => $value) {
            DB::table('types')->insert([
                'name' => $value
            ]);
        }
    }
}
