<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\User;
use App\Observers\CategoryObserver;
use App\Observers\ContactObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

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
        Cashier::calculateTaxes();

        Category::observe(CategoryObserver::class);
        User::observe(UserObserver::class);
        Contact::observe(ContactObserver::class);
    }
}
