<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function acceptString(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'string' => 'required|string|size:40',
        ]);

        // Retrieve the validated input
        $inputString = $request->input('string');

        // Process the input string as needed
        // For example, you could just return it in a response
        return response()->json([
            'success' => true,
            'message' => 'String accepted',
            'string' => $inputString,
        ]);
    }
}
