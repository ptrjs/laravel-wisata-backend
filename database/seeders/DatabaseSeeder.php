<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'peter',
            'email' => 'peter@wisata.com',
            'password' => Hash::make('12345678'),
        ]);

        Category::factory()->create([
            'name'=>'DOMESTIK',
            'description'=>'Tiket Domestik'
        ]);

        Category::factory()->create([
            'name'=>'PARKIR',
            'description'=>'Tiket Parkir'
        ]);

        Category::factory()->create([
            'name'=>'CEMILAN',
            'description'=>'Cemilan'
        ]);

        Product::factory(10)->create();
    }
}
