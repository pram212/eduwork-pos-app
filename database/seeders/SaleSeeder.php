<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Product;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sale::factory(5) // buat 10 data Sale!
            ->hasAttached( // buat juga data di tabel pivot!
                Product::factory()->count(2), // setiap Sale memiliki 2 Product.
                ['quantity'=> 1] // setiap Product quantity-nya 1 pcs!
            )
            ->create(); // Mainkan!
    }
}
