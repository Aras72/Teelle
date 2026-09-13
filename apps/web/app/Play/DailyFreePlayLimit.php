<?php

declare(strict_types=1);

namespace App\Play;

use App\Models\PlaySession;
use DomainException;
use Illuminate\Support\Facades\DB;

final class DailyFreePlayLimit
{
    public function claim(PlaySession $play, string $ipAddress): void
    {
        $usageDate = now()->toDateString();
        $ipDayHash = $this->ipDayHash($ipAddress, $usageDate);
        $timestamp = now();

        DB::table('daily_free_play_claims')->insertOrIgnore([
            'usage_date' => $usageDate,
            'ip_day_hash' => $ipDayHash,
            'play_session_id' => $play->id,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        $claim = DB::table('daily_free_play_claims')
            ->where('usage_date', $usageDate)
            ->where('ip_day_hash', $ipDayHash)
            ->lockForUpdate()
            ->first();

        if (! $claim || (int) $claim->play_session_id !== $play->id) {
            throw new DomainException('فرصت بازی رایگان امروز این اینترنت استفاده شده است؛ فردا دوباره برگردید یا با عضویت فعال جیگری ادامه دهید');
        }
    }

    private function ipDayHash(string $ipAddress, string $usageDate): string
    {
        $packedIp = @inet_pton(trim($ipAddress));
        if ($packedIp === false) {
            throw new DomainException('نشانی شبکه برای شروع بازی رایگان قابل تشخیص نیست؛ دوباره تلاش کنید');
        }

        $key = (string) config('teelle.free_play_ip_hash_key', config('app.key'));
        if ($key === '') {
            throw new DomainException('شروع بازی رایگان موقتاً در دسترس نیست؛ کمی بعد دوباره تلاش کنید');
        }

        return hash_hmac('sha256', $usageDate.'|'.$packedIp, $key);
    }
}
