<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = ['makanan', 'minuman', 'alat mandi', 'rokok', 'obat-obatan'];

        foreach ($datas as $key => $value) {

            DB::table('categories')->insert([
                'name' => $value
            ]);

        }
    }
}
