<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Torta de carnita',
            'cost' => 30,
            'price' => 35,
            'barcode' => '234546',
            'stock' => 1000,
            'alerts' => 10,
            'category_id' => 1,
            'image' => 'torta.png',
        ]);
        Product::create([
            'name' => 'Torta de Asada',
            'cost' => 30,
            'price' => 40,
            'barcode' => '234546',
            'stock' => 1000,
            'alerts' => 10,
            'category_id' => 2,
            'image' => 'Asada.png',
        ]);
        Product::create([
            'name' => 'Torta de queso',
            'cost' => 30,
            'price' => 55,
            'barcode' => '234546',
            'stock' => 1000,
            'alerts' => 10,
            'category_id' => 3,
            'image' => 'torta.png',
        ]);
        Product::create([
            'name' => 'Torta de pastor',
            'cost' => 30,
            'price' => 25,
            'barcode' => '234546',
            'stock' => 1000,
            'alerts' => 10,
            'category_id' => 4,
            'image' => 'torta.png',
        ]);

    }
}
