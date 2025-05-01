<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Define an array of 5 category names
        $categories = [
            'AI Engineer',
            'Software Developer',
            'Data Scientist',
            'DevOps Engineer',
            'Product Manager',
        ];

        // Insert each category into the database
        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
