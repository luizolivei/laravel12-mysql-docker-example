<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreEnterpriseRequest;
use App\Http\Requests\UpdateEnterpriseRequest;
use App\Models\Enterprise;
use App\Http\Controllers\Controller;
use App\Services\EnterpriseService;

class EnterpriseController extends Controller
{
    public function __construct(
        private readonly EnterpriseService $enterprise
    )
     {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enterprises = $this->enterprise->list();

        return response()->json($enterprises);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnterpriseRequest $request)
    {
        $enterprise = $this->enterprise->create($request->validated(), $request->user());

        return response()->json($enterprise);
    }

    public function destroy(Enterprise $enterprise)
    {
        $this->enterprise->delete($enterprise);
    }
}
