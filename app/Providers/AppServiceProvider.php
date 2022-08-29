<?php

namespace App\Providers;

use App\Http\Services\AuthService\LoginInterface;
use App\Http\Services\AuthService\LoginService;
use App\Http\Services\Dashboard\Admin\RecordParking\ARecordParkingInterface;
use App\Http\Services\Dashboard\Admin\RecordParking\ARecordParkingService;
use App\Http\Services\Dashboard\Staff\RecordParking\RecordParkingInterface;
use App\Http\Services\Dashboard\Staff\RecordParking\RecordParkingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LoginInterface::class, LoginService::class);
        $this->app->bind(RecordParkingInterface::class, RecordParkingService::class);
        $this->app->bind(ARecordParkingInterface::class, ARecordParkingService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
