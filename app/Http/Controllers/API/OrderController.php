<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class OrderController extends Controller
{
    /**
     * Get orders list via API.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'orders' => [],
        ]);
    }
}
