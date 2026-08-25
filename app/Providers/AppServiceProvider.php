<?php

namespace App\Providers;

use App\Models\SuratPengajuan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer(['layouts.partials.sidebar', 'layouts.partials.topbar'], function ($view) {
            $user = auth()->user();
            $latestApp = null;

            if ($user) {
                $latestApp = SuratPengajuan::where('user_id', $user->id)->latest()->first();
                if (! $latestApp && ($user->isAdmin() || $user->isReviewer() || $user->isKetuaKepk() || $user->isAnggotaKepk())) {
                    $latestApp = SuratPengajuan::latest()->first();
                }
            }

            $view->with([
                'user' => $user,
                'latestApp' => $latestApp,
            ]);
        });
    }
}
