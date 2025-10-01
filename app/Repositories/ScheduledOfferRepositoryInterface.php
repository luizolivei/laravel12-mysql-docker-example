<?php

namespace App\Repositories;

use App\Models\Offer;
use App\Models\ScheduledOffer;
use Illuminate\Database\Eloquent\Collection;

interface ScheduledOfferRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): ScheduledOffer;

    /**
     * @return Collection<int, ScheduledOffer>
     */
    public function getDue(int $limit = 50): Collection;

    public function markAsProcessed(ScheduledOffer $scheduledOffer, Offer $offer): void;

    /**
     * @return Collection<int, ScheduledOffer>
     */
    public function upcoming(int $limit = 20): Collection;
}
