<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (env('APP_ENV') !== 'local') {
        //     URL::forceScheme('https');
        // }

        \Illuminate\Support\Facades\Event::listen(function (\Illuminate\Auth\Events\Login $event) {
            $pendingCart = session()->pull('pending_cart');
            if ($pendingCart) {
                foreach ($pendingCart as $item) {
                    $keranjang = \App\Models\Keranjang::where('user_id', $event->user->id)
                        ->where('barang_id', $item['id'])
                        ->first();

                    if ($keranjang) {
                        $keranjang->increment('qty', $item['qty']);
                    } else {
                        \App\Models\Keranjang::create([
                            'user_id' => $event->user->id,
                            'barang_id' => $item['id'],
                            'qty' => $item['qty']
                        ]);
                    }
                }
            }
        });
    }
}