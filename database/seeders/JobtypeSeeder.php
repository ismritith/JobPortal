<?php

namespace Database\Seeders;

use App\Models\JobType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Define an array of 5 category names
        $jobtype = [
            'AI Engineer',
            'Software Developer',
            'Data Scientist',
            'DevOps Engineer',
            'Product Manager',
        ];

        // Insert each category into the database
        foreach ($jobtype as $name) {
            JobType::create(['name' => $name]);
        }
    }
}
