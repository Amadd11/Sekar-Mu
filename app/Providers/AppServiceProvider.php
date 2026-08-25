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
                if ($user->isAdmin()) {
                    $latestApp = SuratPengajuan::latest()->first();
                } elseif ($user->isKetuaKepk() || $user->isAnggotaKepk()) {
                    $latestApp = SuratPengajuan::where('user_id', $user->id)->latest()->first()
                        ?? SuratPengajuan::latest()->first();
                } elseif ($user->isReviewer()) {
                    $latestApp = SuratPengajuan::whereHas('penilai', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->latest()->first();
                } elseif ($user->isApplicant()) {
                    $latestApp = SuratPengajuan::where('user_id', $user->id)->latest()->first();
                }
            }

            $view->with([
                'user' => $user,
                'latestApp' => $latestApp,
            ]);
        });
    }
}
