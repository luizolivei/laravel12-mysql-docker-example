<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Inertia\Inertia;
use Inertia\Response;

class OfferController extends Controller
{
    /**
     * Display the offers list page.
     */
    public function __invoke(): Response
    {
        $offers = Offer::query()
            ->orderByDesc('start_date')
            ->get()
            ->map(fn (Offer $offer) => [
                'id' => $offer->id,
                'title' => $offer->title,
                'description' => $offer->description,
                'price' => $offer->price,
                'currency' => $offer->currency,
                'status' => $offer->status,
                'start_date' => $offer->start_date?->toISOString(),
                'end_date' => $offer->end_date?->toISOString(),
                'created_at' => $offer->created_at?->toISOString(),
                'updated_at' => $offer->updated_at?->toISOString(),
            ])
            ->values();

        return Inertia::render('TestPage', [
            'offers' => $offers,
        ]);
    }
}
