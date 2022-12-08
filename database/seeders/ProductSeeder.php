<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Product;

class ProductSeeder extends Seeder
{
    public $data = [
        ['user_id' => 10, 'title' => 'Product 1', 'price' => 10],
        ['user_id' => 10, 'title' => 'Product 2', 'price' => 15],
        ['user_id' => 10, 'title' => 'Product 3', 'price' => 8],
        ['user_id' => 10, 'title' => 'Product 4', 'price' => 7],
        ['user_id' => 10, 'title' => 'Product 5', 'price' => 20],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert($this->data);
    }
}
