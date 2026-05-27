<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        $this->registerActivityLogListeners();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
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
            : null,
        );
    }

    /**
     * Register login/logout event listeners for activity logging.
     */
    protected function registerActivityLogListeners(): void
    {
        Event::listen(Login::class, function (Login $event) {
            try {
                ActivityLog::log(
                    'logged_in', 'auth',
                    "User {$event->user->name} logged in",
                    [
                        'subject_type' => User::class,
                        'subject_id'   => $event->user->id,
                    ]
                );
            } catch (\Throwable) {
                // Silently fail if activity_logs table doesn't exist yet (e.g. before migration)
            }
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                try {
                    ActivityLog::log(
                        'logged_out', 'auth',
                        "User {$event->user->name} logged out",
                        [
                            'subject_type' => User::class,
                            'subject_id'   => $event->user->id,
                        ]
                    );
                } catch (\Throwable) {
                    // Silently fail if activity_logs table doesn't exist yet
                }
            }
        });
    }
}
