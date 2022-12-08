<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;

class ProductGroupItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_group_items')->insert([
            ['group_id' => 1, 'product_id' => 2],
            ['group_id' => 1, 'product_id' => 5],
        ]);
    }
}
