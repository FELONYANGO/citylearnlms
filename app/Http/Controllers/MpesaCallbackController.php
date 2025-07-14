<?php

namespace App\Http\Controllers;

use App\Services\MpesaService;
use Illuminate\Http\Request;
use Techlup\PaymentGateway\Mpesa\StkPush;

class MpesaCallbackController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    public function handleCallback(Request $request)
    {
        try {
            $callbackData = StkPush::getCallbackData();

            $result = $this->mpesaService->handleCallback($callbackData);

            return response()->json([
                'status' => 'success',
                'message' => $result ? 'Payment processed successfully' : 'Payment processing failed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process callback'
            ], 500);
        }
    }
}
