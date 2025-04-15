<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Kimia'],
            ['name' => 'ABC'],
            ['name' => 'Matematika'], 
            ['name' => 'Fisika'],
            ['name' => 'Novel'] 
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}