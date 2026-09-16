<?php

declare(strict_types=1);

namespace App\Content;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use ZipArchive;

final class TeelleXlsxGameReader
{
    private const MAX_ENTRIES = 400;

    private const MAX_UNCOMPRESSED_BYTES = 30_000_000;

    private const IMPORT_HEADERS = [
        'شناسه انگلیسی', 'عنوان بازی', 'توضیح کوتاه', 'روش بازی؛ مرحله‌ها با | جدا شوند', 'نکته ایمنی', 'موارد منع؛ با | جدا شوند',
        'سطح نظارت', 'بازه سنی', 'شروع سن', 'پایان سن', 'حداقل زمان؛ دقیقه', 'حداکثر زمان؛ دقیقه', 'زمان آماده‌سازی؛ دقیقه',
        'فضای لازم', 'میزان صدا', 'میزان کثیفی', 'حداقل کودک', 'حداکثر کودک', 'حداقل بزرگسال', 'حضور بزرگسال', 'انرژی کودک',
        'انرژی همراه', 'نوع تعامل', 'مشارکت همراه', 'سختی آماده‌سازی', 'موقعیت ۱', 'موقعیت ۲', 'موقعیت ۳', 'مکان ۱',
        'مکان ۲', 'حال کودک ۱', 'حال کودک ۲', 'برچسب ۱', 'برچسب ۲', 'برچسب ۳', 'ترکیب بازیکنان', 'وسیله ۱',
        'وضعیت وسیله ۱', 'توضیح وسیله ۱', 'وسیله ۲', 'وضعیت وسیله ۲', 'توضیح وسیله ۲', 'نکته ایمنی ساختاری ۱',
        'نکته ایمنی ساختاری ۲', 'نام منبع', 'نشانی منبع', 'ریشه فرهنگی',
    ];

    /** @return array<int, array<string, mixed>> */
    public function read(UploadedFile $file): array
    {
        if (strtolower($file->getClientOriginalExtension()) !== 'xlsx') {
            $this->fail('فقط فایل Excel با پسوند xlsx پذیرفته می‌شود');
        }

        $mime = (string) $file->getMimeType();
        if (! in_array($mime, [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip',
            'application/x-zip-compressed',
            'application/octet-stream',
        ], true)) {
            $this->fail('نوع فایل با یک فایل Excel معتبر سازگار نیست');
        }

        $zip = new ZipArchive;
        if ($zip->open($file->getRealPath()) !== true) {
            $this->fail('فایل Excel باز نشد یا ساختار آن معتبر نیست');
        }

        try {
            $this->guardArchive($zip);
            $sharedStrings = $this->sharedStrings($zip);
            $rows = $this->sheetRows($zip, $sharedStrings);
        } finally {
            $zip->close();
        }

        return $this->toGames($rows);
    }

    private function guardArchive(ZipArchive $zip): void
    {
        if ($zip->numFiles < 1 || $zip->numFiles > self::MAX_ENTRIES || $zip->locateName('[Content_Types].xml') === false) {
            $this->fail('ساختار فایل Excel قابل قبول نیست');
        }

        $total = 0;
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $stat = $zip->statIndex($index);
            $name = strtolower((string) ($stat['name'] ?? ''));
            $total += (int) ($stat['size'] ?? 0);
            if ($total > self::MAX_UNCOMPRESSED_BYTES || str_contains($name, 'vbaproject') || str_ends_with($name, '.bin')) {
                $this->fail('فایل Excel بیش از حد بزرگ است یا محتوای اجرایی دارد');
            }
        }
    }

    /** @return array<int, string> */
    private function sharedStrings(ZipArchive $zip): array
    {
        $content = $zip->getFromName('xl/sharedStrings.xml');
        if ($content === false) {
            return [];
        }

        $xml = $this->xml($content);
        $xpath = new \DOMXPath($xml);
        $xpath->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $values = [];
        foreach ($xpath->query('//x:si') ?: [] as $item) {
            $text = '';
            foreach ($xpath->query('.//x:t', $item) ?: [] as $node) {
                $text .= $node->textContent;
            }
            $values[] = $text;
        }

        return $values;
    }

    /** @param array<int, string> $sharedStrings
     * @return array<int, array<int, string>>
     */
    private function sheetRows(ZipArchive $zip, array $sharedStrings): array
    {
        $content = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($content === false) {
            $this->fail('شیت بازی‌ها در فایل پیدا نشد');
        }
        $xml = $this->xml($content);
        $xpath = new \DOMXPath($xml);
        $xpath->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $rows = [];
        foreach ($xpath->query('//x:sheetData/x:row') ?: [] as $rowNode) {
            $rowNumber = (int) $rowNode->attributes?->getNamedItem('r')?->nodeValue;
            $row = [];
            foreach ($xpath->query('./x:c', $rowNode) ?: [] as $cell) {
                $reference = (string) $cell->attributes?->getNamedItem('r')?->nodeValue;
                preg_match('/^[A-Z]+/', $reference, $match);
                $column = $this->columnIndex($match[0] ?? '');
                $type = (string) $cell->attributes?->getNamedItem('t')?->nodeValue;
                if ($type === 'inlineStr') {
                    $value = $xpath->query('.//x:is/x:t', $cell)?->item(0)?->textContent ?? '';
                } else {
                    $value = $xpath->query('./x:v', $cell)?->item(0)?->textContent ?? '';
                    if ($type === 's') {
                        $value = $sharedStrings[(int) $value] ?? '';
                    }
                }
                $row[$column] = trim((string) $value);
            }
            $rows[$rowNumber] = $row;
        }

        return $rows;
    }

    /** @param array<int, array<int, string>> $rows
     * @return array<int, array<string, mixed>>
     */
    private function toGames(array $rows): array
    {
        $headers = $rows[4] ?? [];
        foreach (self::IMPORT_HEADERS as $offset => $title) {
            if (($headers[$offset + 2] ?? '') !== $title) {
                $this->fail('ستون‌های فایل با تمپلیت رسمی تیله هماهنگ نیستند');
            }
        }

        $games = [];
        foreach ($rows as $number => $row) {
            if ($number < 5 || ($this->value($row, 2) === '' && $this->value($row, 3) === '')) {
                continue;
            }
            $games[] = $this->game($row);
        }
        if ($games === []) {
            $this->fail('هیچ ردیف پرشده‌ای در فایل پیدا نشد');
        }

        return $games;
    }

    /** @param array<int, string> $row
     * @return array<string, mixed>
     */
    private function game(array $row): array
    {
        $materials = [];
        foreach ([[38, 39, 40], [41, 42, 43]] as [$slugColumn, $requirementColumn, $noteColumn]) {
            if (($slug = $this->decode($this->value($row, $slugColumn), 'material')) !== '') {
                $materials[] = ['slug' => $slug, 'requirement' => $this->decode($this->value($row, $requirementColumn), 'requirement'), 'quantity_note' => $this->value($row, $noteColumn) ?: null];
            }
        }

        return [
            'slug' => $this->value($row, 2), 'title' => $this->value($row, 3), 'summary' => $this->value($row, 4),
            'instructions' => $this->parts($this->value($row, 5)), 'safety_copy' => $this->value($row, 6),
            'contraindications' => $this->parts($this->value($row, 7)), 'supervision_level' => $this->decode($this->value($row, 8), 'supervision'),
            'metadata' => [
                'age_band' => $this->decode($this->value($row, 9), 'age'),
                'minimum_age_months' => $this->age($this->value($row, 10), false),
                'maximum_age_months_exclusive' => $this->age($this->value($row, 11), true),
                'duration_min_minutes' => $this->number($this->value($row, 12)), 'duration_max_minutes' => $this->number($this->value($row, 13)),
                'prep_time_minutes' => $this->number($this->value($row, 14)), 'space_required' => $this->decode($this->value($row, 15), 'space'),
                'noise_level' => $this->decode($this->value($row, 16), 'noise'), 'mess_level' => $this->decode($this->value($row, 17), 'mess'),
                'minimum_children' => $this->number($this->value($row, 18)), 'maximum_children' => $this->number($this->value($row, 19)),
                'minimum_adults' => $this->number($this->value($row, 20)), 'required_adult' => $this->value($row, 21) === 'بله',
                'child_energy' => $this->decode($this->value($row, 22), 'energy'), 'caregiver_energy' => $this->decode($this->value($row, 23), 'energy'),
                'interaction_type' => $this->decode($this->value($row, 24), 'interaction'), 'caregiver_involvement' => $this->decode($this->value($row, 25), 'involvement'),
                'setup_complexity' => $this->decode($this->value($row, 26), 'setup'),
                'situations' => $this->decodedColumns($row, [27, 28, 29], 'situation'), 'locations' => $this->decodedColumns($row, [30, 31], 'location'),
                'moods' => $this->decodedColumns($row, [32, 33], 'mood'), 'tags' => $this->decodedColumns($row, [34, 35, 36], 'tag'),
                'player_requirement' => $this->decode($this->value($row, 37), 'player'), 'materials' => $materials,
                'safety_flags' => $this->decodedColumns($row, [44, 45], 'safety'), 'source_title' => $this->value($row, 46),
                'source_url' => $this->value($row, 47), 'cultural_origin' => $this->value($row, 48),
            ],
        ];
    }

    /** @param array<int, string> $row
     * @param  array<int, int>  $columns
     * @return array<int, string>
     */
    private function decodedColumns(array $row, array $columns, string $type): array
    {
        return array_values(array_unique(array_filter(array_map(fn (int $column): string => $this->decode($this->value($row, $column), $type), $columns))));
    }

    /** @return array<int, string> */
    private function parts(string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/[|\r\n]+/u', $value) ?: [])));
    }

    private function age(string $value, bool $end): int
    {
        $normalized = $this->latinDigits($value);
        preg_match('/(\d+)/', $normalized, $match);
        $number = (int) ($match[1] ?? 0);

        if (str_contains($normalized, 'ماه')) {
            return $number + ($end ? 1 : 0);
        }

        return $end && $number === 12 ? 156 : $number * 12;
    }

    private function number(string $value): int
    {
        return (int) preg_replace('/\D+/', '', $this->latinDigits($value));
    }

    private function latinDigits(string $value): string
    {
        return strtr($value, ['۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٬' => '']);
    }

    private function decode(string $value, string $type): string
    {
        $maps = [
            'supervision' => ['در دسترس مستقیم' => 'within_reach', 'در همان اتاق' => 'same_room', 'بررسی دوره‌ای' => 'check_in'],
            'age' => ['۶ تا ۲۳ ماه' => '6-23m', '۲ تا ۳ سال' => '2-3y', '۴ تا ۶ سال' => '4-6y', '۷ تا ۹ سال' => '7-9y', '۱۰ تا ۱۲ سال' => '10-12y'],
            'space' => ['روی پا یا بغل' => 'lap', 'فضای کوچک' => 'small', 'اتاق' => 'room', 'فضای بزرگ' => 'large', 'فضای باز' => 'outdoor'],
            'noise' => ['کم‌صدا' => 'quiet', 'صدای معمولی' => 'moderate', 'پرسروصدا' => 'loud'],
            'mess' => ['بدون کثیفی' => 'none', 'کثیفی کم' => 'light', 'کثیف‌کاری' => 'messy'],
            'energy' => ['کم' => 'low', 'متوسط' => 'medium', 'زیاد' => 'high'],
            'interaction' => ['کنار هم' => 'side_by_side', 'همکاری' => 'cooperative', 'رقابتی' => 'competitive', 'بازی خیالی' => 'pretend', 'گفت‌وگو' => 'conversation'],
            'involvement' => ['فعال' => 'active', 'مشترک' => 'shared', 'کم' => 'light'],
            'setup' => ['بدون آماده‌سازی' => 'none', 'ساده' => 'simple', 'متوسط' => 'moderate'],
            'situation' => [
                'بعد از کار' => 'after-work', 'روز بارانی' => 'rainy-day', 'رستوران' => 'restaurant', 'ماشین' => 'car', 'مهمانی' => 'party', 'قبل خواب' => 'before-bed',
                'ارتباط' => 'connection', 'بی‌حوصلگی' => 'bored', 'وقت داخل خانه' => 'indoor-time', 'بی‌قراری' => 'restless', 'آرام‌شدن' => 'calm-down',
            ],
            'location' => [
                'داخل خانه' => 'home-inside', 'بیرون' => 'outdoors', 'رستوران' => 'restaurant', 'ماشین' => 'car', 'مهمانی' => 'party',
                'حیاط خانه' => 'home-outside', 'سفر' => 'travel', 'پارک' => 'park',
            ],
            'mood' => [
                'آرام' => 'calm', 'بی‌حوصله' => 'bored', 'پرانرژی' => 'energetic', 'نیاز به توجه' => 'needs-attention',
                'بی‌قرار' => 'restless', 'هیجان‌زده' => 'excited', 'غمگین' => 'sad',
            ],
            'tag' => ['همکاری' => 'cooperative', 'زبان و گفت‌وگو' => 'language', 'حرکت' => 'movement', 'خلاقیت' => 'creative', 'حسی' => 'sensory'],
            'player' => [
                'یک کودک و یک بزرگسال' => 'child-and-adult', 'چند کودک' => 'multiple-children', 'بزرگسال همراه نیست' => 'no-adult',
                'کودک و بزرگسال' => 'child-and-adult', 'دو بازیکن' => 'two-players', 'گروه کوچک' => 'small-group',
            ],
            'material' => [
                'کاغذ و مداد' => 'paper-pencil', 'توپ' => 'ball', 'وسایل خانه' => 'household-items',
                'لیوان' => 'cups', 'پتو' => 'blanket', 'کاغذ' => 'paper', 'توپ نرم' => 'ball',
            ],
            'requirement' => ['لازم' => 'required', 'اختیاری' => 'optional'],
            'safety' => ['شدت حسی' => 'sensory_intensity', 'خطر افتادن' => 'fall_height', 'قطعات ریز' => 'small_parts', 'بند یا خفگی' => 'strangulation', 'ضربه' => 'high_impact', 'خوردن مواد' => 'ingestion', 'ترافیک و فضای باز' => 'traffic_outdoor', 'جسم تیز' => 'sharp_object'],
        ];

        return $maps[$type][$value] ?? $value;
    }

    /** @param array<int, string> $row */
    private function value(array $row, int $column): string
    {
        return trim((string) ($row[$column] ?? ''));
    }

    private function columnIndex(string $letters): int
    {
        $index = 0;
        foreach (str_split($letters) as $letter) {
            $index = ($index * 26) + ord($letter) - 64;
        }

        return $index;
    }

    private function xml(string $content): \DOMDocument
    {
        $document = new \DOMDocument;
        if (! $document->loadXML($content, LIBXML_NONET | LIBXML_NOBLANKS)) {
            $this->fail('یکی از بخش‌های داخلی فایل Excel معتبر نیست');
        }

        return $document;
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['workbook' => $message]);
    }
}
