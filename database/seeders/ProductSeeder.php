<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // garante que existem categorias
        $categories = Category::factory(5)->create();

        // cria produtos e associa categorias aleatórias
        Product::factory(20)
            ->create()
            ->each(function (Product $product) use ($categories) {
                $product->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
    }
}
