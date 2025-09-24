<?php

namespace App\DTO\Offer;

class OfferFilterData
{
    public readonly ?string $search;

    public function __construct(?string $search = null)
    {
        $search = is_string($search) ? trim($search) : null;

        $this->search = $search === '' ? null : $search;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['search'] ?? null,
        );
    }

    public function hasSearch(): bool
    {
        return $this->search !== null;
    }

    public function searchPattern(): ?string
    {
        if (! $this->hasSearch()) {
            return null;
        }

        return '%' . str_replace(['%', '_'], ['\\%', '\\_'], $this->search) . '%';
    }
}
