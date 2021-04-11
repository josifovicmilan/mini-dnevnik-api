<?php

namespace App\Providers;

use App\Models\Student;
use App\Models\Position;
use App\Observers\StudentObserver;
use App\Observers\PositionObserver;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Student::observe(StudentObserver::class);
        Position::observe(PositionObserver::class);
    }
}
