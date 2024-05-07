<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
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
        $path = request()->path();
        if (str_contains($path, 'api/')) {
            VerifyEmail::toMailUsing(function (object $notifiable) {
                $url = URL::temporarySignedRoute(
                    'api.verification.verify',
                    Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                    [
                        'id' => $notifiable->getKey(),
                        'hash' => sha1($notifiable->getEmailForVerification()),
                    ]
                );
                ['path' => $path, 'query' => $query] = parse_url($url);
                $url = Config::get('app.fe_url') . $path . '?' . $query;
                return (new MailMessage)
                    ->subject(Lang::get('Verify Email Address'))
                    ->line(Lang::get('Please click the button below to verify your email address.'))
                    ->action(Lang::get('Verify Email Address'), $url)
                    ->line(Lang::get('If you did not create an account, no further action is required.'));
            });
        }
    }
}
