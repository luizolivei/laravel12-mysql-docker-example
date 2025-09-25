<?php

namespace Database\Seeders;

use App\Domain\Categories\Entities\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tecnologia',
                'description' => 'Produtos e serviços relacionados à tecnologia.',
            ],
            [
                'name' => 'Viagens',
                'description' => 'Pacotes e ofertas para viagens nacionais e internacionais.',
            ],
            [
                'name' => 'Moda',
                'description' => 'Roupas, calçados e acessórios.',
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['name' => $category['name']],
                $category,
            );
        }
    }
}
