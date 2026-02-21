<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken($pembayaran, $user)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $pembayaran->kode_pembayaran,
                'gross_amount' => (int) $pembayaran->nominal_bayar,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->no_hp,
            ],
        ];

        return Snap::getSnapToken($params);
    }
}