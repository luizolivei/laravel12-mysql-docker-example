<?php

namespace App\Services;

use App\Models\ScheduledOffer;
use App\Repositories\ScheduledOfferRepositoryInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class ScheduledOfferService
{
    public function __construct(
        private readonly ScheduledOfferRepositoryInterface $scheduledOffers,
        private readonly OfferService $offers,
        private readonly DatabaseManager $database,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function schedule(array $data): ScheduledOffer
    {
        if (! array_key_exists('active', $data)) {
            $data['active'] = true;
        }

        return $this->scheduledOffers->create($data);
    }

    /**
     * @return Collection<int, ScheduledOffer>
     */
    public function listUpcoming(int $limit = 20): Collection
    {
        return $this->scheduledOffers->upcoming($limit);
    }

    public function processDue(int $limit = 50): int
    {
        $scheduled = $this->scheduledOffers->getDue($limit);
        $processedCount = 0;

        foreach ($scheduled as $item) {
            $this->database->transaction(function () use ($item, &$processedCount) {
                if ($item->processed_at !== null) {
                    return;
                }

                $payload = Arr::only(
                    $item->toArray(),
                    [
                        'category_id',
                        'title',
                        'description',
                        'price',
                        'currency',
                        'status',
                        'active',
                        'start_date',
                        'end_date',
                    ],
                );

                $offer = $this->offers->create($payload);

                $this->scheduledOffers->markAsProcessed($item, $offer);
                $processedCount++;
            });
        }

        return $processedCount;
    }
}
