<?php

namespace App\Repositories;

use App\Models\Offer;
use App\Models\ScheduledOffer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class EloquentScheduledOfferRepository implements ScheduledOfferRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): ScheduledOffer
    {
        return ScheduledOffer::query()->create($data);
    }

    public function markAsProcessed(ScheduledOffer $scheduledOffer, Offer $offer): void
    {
        $scheduledOffer->forceFill([
            'processed_at' => Carbon::now(),
            'offer_id' => $offer->getKey(),
        ])->save();
    }

    /**
     * @return Collection<int, ScheduledOffer>
     */
    public function getDue(int $limit = 50): Collection
    {
        return ScheduledOffer::query()
            ->with('category')
            ->whereNull('processed_at')
            ->where('scheduled_for', '<=', Carbon::now())
            ->orderBy('scheduled_for')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, ScheduledOffer>
     */
    public function upcoming(int $limit = 20): Collection
    {
        return ScheduledOffer::query()
            ->with('category')
            ->whereNull('processed_at')
            ->orderBy('scheduled_for')
            ->limit($limit)
            ->get();
    }
}
