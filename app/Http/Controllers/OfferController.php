<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Post;

class OfferController extends Controller
{
    public function index()
    {
        return response()->json(Offer::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $offer = Offer::create($validated);

        return response()->json($offer, 201);
    }
}
