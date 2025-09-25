<?php

namespace App\Services;

use App\Models\Offer;
use App\Repositories\OfferRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class OfferService
{
    public function __construct(
        private readonly OfferRepositoryInterface $offers,
    ) {
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): Collection
    {
        $search = Arr::get($filters, 'search');
        $categoryId = Arr::get($filters, 'category_id');

        return $this->offers->list(
            is_string($search) ? $search : null,
            is_numeric($categoryId) ? (int) $categoryId : null,
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Offer
    {
        return $this->offers->create($data);
    }

    public function delete(Offer $offer): void
    {
        $this->offers->delete($offer);
    }

    public function cloneLatestWithDiscount(float $discountPercentage): ?Offer
    {
        $latestOffer = $this->offers->findLatest();

        if ($latestOffer === null) {
            return null;
        }

        $discountRate = max(0.0, $discountPercentage) / 100;
        $discountedPrice = max(0.0, round($latestOffer->price * (1 - $discountRate), 2));

        $payload = collect($latestOffer->getFillable())
            ->mapWithKeys(fn (string $attribute) => [
                $attribute => $latestOffer->getAttribute($attribute),
            ])
            ->toArray();

        $payload['price'] = $discountedPrice;

        return $this->offers->create($payload);
    }
}
