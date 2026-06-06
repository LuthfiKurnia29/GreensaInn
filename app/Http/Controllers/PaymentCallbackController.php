<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // order_id is in format BOOK-{id}-{timestamp}
        $orderIdParts = explode('-', $request->order_id);
        $peminjamanId = $orderIdParts[1] ?? null;

        if (!$peminjamanId) {
            return response()->json(['message' => 'Invalid order id format'], 400);
        }

        $peminjaman = Peminjaman::find($peminjamanId);

        if (!$peminjaman) {
            return response()->json(['message' => 'Peminjaman not found'], 404);
        }

        if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
            $peminjaman->update([
                'status_pembayaran' => 'verified'
                // Optional: You could update 'status' => 'approved' here, 
                // but we keep it 'pending' so Admin still confirms the booking time and details
            ]);
            
            // Add notification for successful payment
            \App\Models\Notification::create([
                'user_id'       => $peminjaman->user_id,
                'peminjaman_id' => $peminjaman->id,
                'type'          => 'payment_success',
                'message'       => 'Pembayaran untuk pesanan Anda berhasil diverifikasi. Silakan tunggu konfirmasi selanjutnya.',
                'is_read'       => false,
            ]);
            
        } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
            $peminjaman->update([
                'status_pembayaran' => 'unpaid'
            ]);
        } elseif ($request->transaction_status == 'pending') {
            $peminjaman->update([
                'status_pembayaran' => 'pending_verification' // Or just keep unpaid
            ]);
        }

        return response()->json(['message' => 'Success']);
    }
}
