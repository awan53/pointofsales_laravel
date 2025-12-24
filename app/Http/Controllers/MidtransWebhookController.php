<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handler(Request $request)
    {
        try {
            Log::info('Midtrans Webhook Data:', $request->all());
            
            $serverKey = env('MIDTRANS_SERVER_KEY');
            // Pastikan format gross_amount sama persis saat hashing
            $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashed !== $request->signature_key) {
                Log::warning('Invalid Signature for Order: ' . $request->order_id);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $sale = Sale::where('invoice', $request->order_id)->first();
            
            if (!$sale) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            $status = $request->transaction_status;
            // Gunakan nilai dari Midtrans dan bulatkan ke integer agar aman untuk database
            $amountFromMidtrans = (int)$request->gross_amount;

            if ($status == 'settlement' || $status == 'capture') {
                $sale->update([
                    'status' => 'success', 
                    'paid'   => $amountFromMidtrans 
                ]);
            } elseif ($status == 'pending') {
                $sale->update(['status' => 'pending']);
            } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                $sale->update(['status' => 'failed']);
            }

            return response()->json(['message' => 'Webhook received']);

        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }
}