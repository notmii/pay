<?php

namespace App\Providers;

use App\Providers\ExchangeRate\ExchangeRateApi;
use App\Providers\ExchangeRate\ExchangeRateProviderInterface;
use App\Repositories\OperationRepository;
use App\Repositories\OperationRepositoryInterface;
use App\Services\CommissionCalculator\BusinessWithdrawal;
use App\Services\CommissionCalculator\Deposit;
use App\Services\CommissionCalculator\PrivateWithdrawal;
use App\Services\CommissionCalculator\CommissionCalculatorFactory;
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
        $this->app->bind(ExchangeRateProviderInterface::class, function($app) {
            return new ExchangeRateApi();
        });

        $this->app->singleton(OperationRepositoryInterface::class, function($app) {
            return new OperationRepository();
        });

        $this->app->bind(Deposit::class, function($app) {
            return new Deposit();
        });

        $this->app->bind(BusinessWithdrawal::class, function($app) {
            return new BusinessWithdrawal();
        });

        $this->app->bind(PrivateWithdrawal::class, function($app) {
            return new PrivateWithdrawal(
                $app->make(ExchangeRateProviderInterface::class),
                $app->make(OperationRepositoryInterface::class)
            );
        });

        $this->app->singleton(CommissionCalculatorFactory::class, function($app) {
            return new CommissionCalculatorFactory($app);
        });
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
