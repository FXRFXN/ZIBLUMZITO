<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::Create([
        
            'name' => 'TORTAS',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'

        ]);
        Category::Create([
        
            'name' => 'REFRESCOS',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'

        ]);
        Category::Create([
        
            'name' => 'PIZZAS',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'

        ]);
        Category::Create([
        
            'name' => 'TACOS',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'

        ]);
    }
}
