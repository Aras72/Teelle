<?php

namespace App\Providers;

use App\Auth\DisabledOtpSender;
use App\Auth\OtpSender;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OtpSender::class, DisabledOtpSender::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('auth', function (Request $request): Limit {
            $email = mb_strtolower(trim((string) $request->input('email')));

            return Limit::perMinute(5)->by(hash('sha256', $email.'|'.$request->ip()));
        });
        RateLimiter::for('recovery', function (Request $request): Limit {
            $email = mb_strtolower(trim((string) $request->input('email')));

            return Limit::perHour(3)->by(hash('sha256', $email.'|'.$request->ip()));
        });
        RateLimiter::for('match', function (Request $request): Limit {
            $state = $request->session()->get('teelle.quick_match');
            $submissionKey = is_array($state) ? ($state['submission_key'] ?? null) : null;
            $identity = $request->user()?->getAuthIdentifier() ?? (is_string($submissionKey) ? $submissionKey : $request->ip());
            $actorKey = hash('sha256', (string) $identity);

            return Limit::perMinute(30)->by($actorKey);
        });
        RateLimiter::for('play', function (Request $request): Limit {
            $identity = $request->user()?->getAuthIdentifier()
                ?? $request->session()->get('teelle.guest_token')
                ?? $request->ip();

            return Limit::perMinute(60)->by(hash('sha256', (string) $identity));
        });
        RateLimiter::for('search', function (Request $request): Limit {
            $identity = $request->user()?->getAuthIdentifier() ?? $request->ip();

            return Limit::perMinute(30)->by(hash('sha256', (string) $identity));
        });
        RateLimiter::for('privacy', function (Request $request): Limit {
            $identity = $request->user()?->getAuthIdentifier() ?? $request->ip();

            return Limit::perHour(5)->by(hash('sha256', (string) $identity));
        });

        foreach (['content.edit', 'content.review', 'content.publish', 'coverage.view', 'subscription.manage', 'roles.manage', 'users.manage', 'users.view', 'users.edit'] as $ability) {
            Gate::define($ability, fn (User $user): bool => $user->hasPermission($ability));
        }

        VerifyEmail::toMailUsing(fn (object $notifiable, string $url): MailMessage => (new MailMessage)
            ->subject('تأیید ایمیل حساب تیله')
            ->greeting('سلام '.$notifiable->name)
            ->line('برای کامل‌شدن حساب بزرگسال تیله، ایمیل خود را تأیید کنید')
            ->action('تأیید ایمیل', $url)
            ->line('اگر شما این حساب را نساخته‌اید، این پیام را نادیده بگیرید'));
        ResetPassword::toMailUsing(function (object $notifiable, string $token): MailMessage {
            $url = route('password.reset', ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()]);

            return (new MailMessage)
                ->subject('بازیابی رمز عبور تیله')
                ->greeting('سلام '.$notifiable->name)
                ->line('برای انتخاب رمز عبور تازه از پیوند زیر استفاده کنید')
                ->action('انتخاب رمز تازه', $url)
                ->line('این پیوند تا ۶۰ دقیقه معتبر است و اگر شما درخواست نداده‌اید، نیازی به کاری نیست');
        });
    }
}
