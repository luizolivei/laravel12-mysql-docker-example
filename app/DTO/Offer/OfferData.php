<?php

namespace App\DTO\Offer;

use Illuminate\Support\Carbon;

class OfferData
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly float $price,
        public readonly string $currency,
        public readonly string $status,
        public readonly Carbon $startDate,
        public readonly ?Carbon $endDate,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $endDate = $data['end_date'] ?? null;

        if (is_string($endDate)) {
            $endDate = trim($endDate);
        }

        return new self(
            title: $data['title'],
            description: $data['description'],
            price: (float) $data['price'],
            currency: strtoupper($data['currency']),
            status: $data['status'],
            startDate: Carbon::parse($data['start_date']),
            endDate: $endDate !== null && $endDate !== ''
                ? Carbon::parse($endDate)
                : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => $this->currency,
            'status' => $this->status,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ];
    }
}
