<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Cart;

class CartSeeder extends Seeder
{
    public $data = [
        ['user_id' => 15, 'product_id' => 2, 'quantity' => 3],
        ['user_id' => 15, 'product_id' => 5, 'quantity' => 2],
        ['user_id' => 15, 'product_id' => 1, 'quantity' => 1],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cart::insert($this->data);
    }
}
