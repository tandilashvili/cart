<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UserProductGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_product_groups')->insert([
            ['group_id' => 1, 'user_id' => 10, 'discount' => 15],
        ]);
    }
}
