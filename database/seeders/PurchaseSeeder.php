<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Purchase::factory(5) // buat 10 data Sale!
            ->hasAttached( // buat juga data di tabel pivot!
                Product::factory()->count(2), // setiap Sale memiliki 2 Product.
                ['quantity'=> 1] // setiap Product quantity-nya 1 pcs!
            )
            ->create(); // Mainkan!
    }
}
