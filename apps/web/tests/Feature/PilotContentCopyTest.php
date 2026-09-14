<?php

namespace Tests\Feature;

use Tests\TestCase;

class PilotContentCopyTest extends TestCase
{
    public function test_owner_reviewed_copy_is_the_canonical_pilot_content(): void
    {
        $games = collect(require resource_path('content/pilot-games-v1.php'))->keyBy('slug');

        $this->assertCount(25, $games);
        $this->assertSame([
            'روبه‌روی کودک بنشینید',
            'یک حالت ساده مثل لبخند نشان دهید',
            'مکث کنید و منتظر پاسخ کودک باشید',
            'این روال را نوبتی تکرار کنید',
        ], $games['face-mirror']['instructions']);
        $this->assertSame('همراه یک قصه کوتاه می‌گوید و کودک با کلام یا حرکت ادامه‌اش می‌دهد', $games['half-story-toddler']['summary']);
        $this->assertSame('برای دو اتفاق یا دو بخش یک صدای کوتاه انتخاب کنید', $games['story-sound-effects']['instructions'][1]);
        $this->assertSame('پس از چند دور داستان را تمام کنید', $games['chain-story']['instructions'][2]);
        $this->assertSame('از فاصله کوتاه توپ را قل دهید طوری که از محدوده خارج نشود', $games['soft-ball-target']['instructions'][1]);
        $this->assertSame('هر نفر یک دقیقه دلیل بگوید و از یک موضوع ترجیحا خنده‌دار دفاع کند', $games['funny-debate']['instructions'][1]);
    }
}
