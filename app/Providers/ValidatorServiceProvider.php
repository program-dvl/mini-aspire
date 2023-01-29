<?php

namespace App\Providers;

use App\Models\RepaymentSchedule;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['validator']->extend('check_payment_reschedule', function ($attribute, $value, $parameters)
        {
            return RepaymentSchedule::where(
                [
                    'id' => $value,
                    'loan_id' => $parameters[0]
                ]
            )->exist();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
