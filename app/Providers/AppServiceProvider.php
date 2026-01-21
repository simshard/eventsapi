<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\BookingRepositoryInterface;
use App\Repositories\AttendeeRepositoryInterface;
use App\Repositories\EventRepository;
use App\Repositories\BookingRepository;
use App\Repositories\AttendeeRepository;
use App\Services\BookingServiceInterface;
use App\Services\EventServiceInterface;
use App\Services\BookingService;
use App\Services\EventService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
               // Repository bindings
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(AttendeeRepositoryInterface::class, AttendeeRepository::class);

        // Service bindings
        $this->app->bind(BookingServiceInterface::class, BookingService::class);
        $this->app->bind(EventServiceInterface::class, EventService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
