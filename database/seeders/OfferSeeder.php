<?php

namespace Database\Seeders;

use App\Domain\Categories\Entities\Category;
use App\Domain\Offers\Entities\Offer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OfferSeeder extends Seeder
{
    /**
     * Seed the offers table with sample data.
     */
    public function run(): void
    {
        $categories = Category::query()->pluck('id', 'name');

        $offers = [
            [
                'category' => 'Moda',
                'title' => 'Desconto de Inverno',
                'description' => 'Oferta especial de inverno com 30% OFF em todos os casacos.',
                'price' => 299.90,
                'currency' => 'BRL',
                'status' => 'active',
                'start_date' => Carbon::create(2024, 6, 1, 0, 0, 0),
                'end_date' => Carbon::create(2024, 6, 30, 23, 59, 59),
            ],
            [
                'category' => 'Viagens',
                'title' => 'Combo Férias',
                'description' => 'Pacote de viagem para o Nordeste com voo + hospedagem por 5 noites.',
                'price' => 2599.99,
                'currency' => 'BRL',
                'status' => 'active',
                'start_date' => Carbon::create(2024, 7, 1, 0, 0, 0),
                'end_date' => Carbon::create(2024, 7, 31, 23, 59, 59),
            ],
            [
                'category' => 'Tecnologia',
                'title' => 'Promoção Relâmpago',
                'description' => 'Somente hoje! Notebook gamer com 20% de desconto.',
                'price' => 4999.00,
                'currency' => 'BRL',
                'status' => 'expired',
                'start_date' => Carbon::create(2024, 5, 20, 12, 0, 0),
                'end_date' => Carbon::create(2024, 5, 21, 23, 59, 59),
            ],
            [
                'category' => 'Tecnologia',
                'title' => 'Oferta Black Friday',
                'description' => 'Smart TV 4K com desconto especial e frete grátis.',
                'price' => 3599.50,
                'currency' => 'BRL',
                'status' => 'draft',
                'start_date' => Carbon::create(2024, 11, 25, 0, 0, 0),
                'end_date' => Carbon::create(2024, 11, 30, 23, 59, 59),
            ],
            [
                'category' => 'Tecnologia',
                'title' => 'Plano Premium',
                'description' => 'Assinatura anual premium com 2 meses grátis.',
                'price' => 799.90,
                'currency' => 'BRL',
                'status' => 'active',
                'start_date' => Carbon::create(2024, 1, 1, 0, 0, 0),
                'end_date' => Carbon::create(2024, 12, 31, 23, 59, 59),
            ],
        ];

        foreach ($offers as $offer) {
            $categoryId = $categories[$offer['category']] ?? Category::query()
                ->firstOrCreate(['name' => $offer['category']])
                ->id;

            Offer::query()->updateOrCreate(
                ['title' => $offer['title']],
                [
                    'category_id' => $categoryId,
                    'title' => $offer['title'],
                    'description' => $offer['description'],
                    'price' => $offer['price'],
                    'currency' => $offer['currency'],
                    'status' => $offer['status'],
                    'start_date' => $offer['start_date'],
                    'end_date' => $offer['end_date'],
                ]
            );
        }
    }
}
