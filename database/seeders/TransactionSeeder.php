<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Product;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = Transaction::factory(5) // buat 10 data Transaction!
                            ->hasAttached( // buat juga data di tabel pivot!
                                Product::factory()->count(2), // setiap Transaction memiliki 3 Product.
                                ['quantity'=> 1] // setiap Product quantity-nya 1 pcs!
                            )
                            ->create();
        // Mainkan!
        return $transactions;
    }
}
