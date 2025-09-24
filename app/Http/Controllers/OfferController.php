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
    ->where('title', 'like', '%a%') // não esquece do wildcard
    ->get();


        return Inertia::render('TestPage', [
            'offers' => $offers,
        ]);
    }
}
