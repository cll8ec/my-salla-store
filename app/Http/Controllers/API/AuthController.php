<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController
{
    public function requestOTP(Request $request): JsonResponse
    {
        return response()->json(['success' => true, 'message' => 'تم إرسال الكود بنجاح']);
    }
}
