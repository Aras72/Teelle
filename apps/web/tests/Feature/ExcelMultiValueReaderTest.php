<?php

namespace Tests\Feature;

use App\Content\TeelleXlsxGameReader;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SystemTaxonomySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use ZipArchive;

class ExcelMultiValueReaderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'mysql') {
            $this->markTestSkipped('Content Admin integration requires a disposable MySQL database.');
        }
        $this->seed([RolePermissionSeeder::class, SystemTaxonomySeeder::class]);
    }

    public function test_reader_maps_comma_separated_multi_values_priority_and_reason(): void
    {
        $headers = ['A' => 'پیش‌نمایش تصویر', 'B' => 'شناسه انگلیسی', 'C' => 'عنوان بازی', 'D' => 'توضیح کوتاه',
            'E' => 'روش بازی؛ مرحله‌ها با | جدا شوند', 'F' => 'نکته ایمنی', 'G' => 'موارد منع؛ با | جدا شوند',
            'H' => 'سطح نظارت', 'I' => 'بازه سنی', 'J' => 'شروع سن', 'K' => 'پایان سن', 'L' => 'حداقل زمان؛ دقیقه',
            'M' => 'حداکثر زمان؛ دقیقه', 'N' => 'زمان آماده‌سازی؛ دقیقه', 'O' => 'فضای لازم', 'P' => 'میزان صدا',
            'Q' => 'میزان کثیفی', 'R' => 'حداقل کودک', 'S' => 'حداکثر کودک', 'T' => 'حداقل بزرگسال',
            'U' => 'حضور بزرگسال', 'V' => 'انرژی کودک', 'W' => 'انرژی همراه', 'X' => 'نوع تعامل',
            'Y' => 'مشارکت همراه', 'Z' => 'سختی آماده‌سازی', 'AA' => 'موقعیت ۱', 'AB' => 'موقعیت ۲',
            'AC' => 'موقعیت ۳', 'AD' => 'مکان ۱', 'AE' => 'مکان ۲', 'AF' => 'حال کودک ۱', 'AG' => 'حال کودک ۲',
            'AH' => 'برچسب ۱', 'AI' => 'برچسب ۲', 'AJ' => 'برچسب ۳', 'AK' => 'ترکیب بازیکنان', 'AL' => 'وسیله ۱',
            'AM' => 'وضعیت وسیله ۱', 'AN' => 'توضیح وسیله ۱', 'AO' => 'وسیله ۲', 'AP' => 'وضعیت وسیله ۲',
            'AQ' => 'توضیح وسیله ۲', 'AR' => 'نکته ایمنی ساختاری ۱', 'AS' => 'نکته ایمنی ساختاری ۲',
            'AT' => 'نام منبع', 'AU' => 'نشانی منبع', 'AV' => 'ریشه فرهنگی', 'AW' => 'اولویت محتوا',
            'AX' => 'دلیل اولویت'];

        $row = ['A' => '', 'B' => 'excel-multi', 'C' => 'بازی اکسل چندگزینه‌ای', 'D' => 'توضیح کوتاه',
            'E' => 'مرحله یک|مرحله دو', 'F' => 'نکته ایمنی', 'G' => '', 'H' => 'در همان اتاق',
            'I' => '۴ تا ۶ سال', 'J' => '۴ سال', 'K' => '۶ سال', 'L' => '5', 'M' => '15', 'N' => '2',
            'O' => 'اتاق', 'P' => 'کم‌صدا', 'Q' => 'بدون کثیفی', 'R' => '1', 'S' => '2', 'T' => '1',
            'U' => 'بله', 'V' => 'متوسط', 'W' => 'کم', 'X' => 'همکاری', 'Y' => 'مشترک',
            'Z' => 'بدون آماده‌سازی', 'AA' => 'بین وعده‌های غذایی,قبل از خواب', 'AB' => '', 'AC' => '',
            'AD' => 'داخل خانه', 'AE' => '', 'AF' => 'آرام,بی‌قرار', 'AG' => '', 'AH' => 'همکاری,حرکت',
            'AI' => '', 'AJ' => '', 'AK' => 'یک کودک و یک بزرگسال', 'AL' => '', 'AM' => '', 'AN' => '',
            'AO' => '', 'AP' => '', 'AQ' => '', 'AR' => 'شدت حسی,خطر افتادن', 'AS' => '',
            'AT' => 'منبع آزمایشی', 'AU' => 'https://example.com/excel', 'AV' => 'ایران',
            'AW' => 'بالا', 'AX' => 'برای روزهای پرانرژی'];

        $path = $this->buildWorkbook($headers, $row);
        $file = new UploadedFile($path, 'games.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $games = app(TeelleXlsxGameReader::class)->read($file);

        $this->assertCount(1, $games);
        $metadata = $games[0]['metadata'];
        $this->assertSame(['between-meals', 'before-bed'], $metadata['situations']);
        $this->assertSame(['calm', 'restless'], $metadata['moods']);
        $this->assertSame(['cooperative', 'movement'], $metadata['tags']);
        $this->assertSame(['sensory_intensity', 'fall_height'], $metadata['safety_flags']);
        $this->assertSame('high', $metadata['content_priority']);
        $this->assertSame('برای روزهای پرانرژی', $metadata['priority_reason']);
        // مقدار نخست ستون نخست انتخاب اصلی است؛ بقیه در alternatives می‌روند.
        $this->assertSame(['before-bed'], $metadata['alternatives']['situations']);
        $this->assertSame(['restless'], $metadata['alternatives']['moods']);
        $this->assertSame(['movement'], $metadata['alternatives']['tags']);
        $this->assertSame(['fall_height'], $metadata['alternatives']['safety_flags']);
        @unlink($path);
    }

    public function test_reader_still_accepts_v2_template_without_priority_columns(): void
    {
        $headers = ['A' => 'پیش‌نمایش تصویر', 'B' => 'شناسه انگلیسی', 'C' => 'عنوان بازی', 'D' => 'توضیح کوتاه',
            'E' => 'روش بازی؛ مرحله‌ها با | جدا شوند', 'F' => 'نکته ایمنی', 'G' => 'موارد منع؛ با | جدا شوند',
            'H' => 'سطح نظارت', 'I' => 'بازه سنی', 'J' => 'شروع سن', 'K' => 'پایان سن', 'L' => 'حداقل زمان؛ دقیقه',
            'M' => 'حداکثر زمان؛ دقیقه', 'N' => 'زمان آماده‌سازی؛ دقیقه', 'O' => 'فضای لازم', 'P' => 'میزان صدا',
            'Q' => 'میزان کثیفی', 'R' => 'حداقل کودک', 'S' => 'حداکثر کودک', 'T' => 'حداقل بزرگسال',
            'U' => 'حضور بزرگسال', 'V' => 'انرژی کودک', 'W' => 'انرژی همراه', 'X' => 'نوع تعامل',
            'Y' => 'مشارکت همراه', 'Z' => 'سختی آماده‌سازی', 'AA' => 'موقعیت ۱', 'AB' => 'موقعیت ۲',
            'AC' => 'موقعیت ۳', 'AD' => 'مکان ۱', 'AE' => 'مکان ۲', 'AF' => 'حال کودک ۱', 'AG' => 'حال کودک ۲',
            'AH' => 'برچسب ۱', 'AI' => 'برچسب ۲', 'AJ' => 'برچسب ۳', 'AK' => 'ترکیب بازیکنان', 'AL' => 'وسیله ۱',
            'AM' => 'وضعیت وسیله ۱', 'AN' => 'توضیح وسیله ۱', 'AO' => 'وسیله ۲', 'AP' => 'وضعیت وسیله ۲',
            'AQ' => 'توضیح وسیله ۲', 'AR' => 'نکته ایمنی ساختاری ۱', 'AS' => 'نکته ایمنی ساختاری ۲',
            'AT' => 'نام منبع', 'AU' => 'نشانی منبع', 'AV' => 'ریشه فرهنگی'];

        $row = ['B' => 'excel-v2', 'C' => 'بازی تمپلیت قدیمی', 'D' => 'توضیح', 'E' => 'مرحله', 'F' => 'ایمنی',
            'H' => 'در همان اتاق', 'I' => '۴ تا ۶ سال', 'J' => '۴ سال', 'K' => '۶ سال', 'L' => '5', 'M' => '15',
            'N' => '2', 'O' => 'اتاق', 'P' => 'کم‌صدا', 'Q' => 'بدون کثیفی', 'R' => '1', 'S' => '2', 'T' => '1',
            'U' => 'بله', 'V' => 'متوسط', 'W' => 'کم', 'X' => 'همکاری', 'Y' => 'مشترک', 'Z' => 'ساده',
            'AA' => 'بین وعده‌های غذایی', 'AD' => 'داخل خانه', 'AF' => 'آرام', 'AH' => 'همکاری',
            'AK' => 'یک کودک و یک بزرگسال', 'AR' => 'شدت حسی', 'AT' => 'منبع', 'AU' => 'https://example.com/v2',
            'AV' => 'ایران'];

        $path = $this->buildWorkbook($headers, $row);
        $file = new UploadedFile($path, 'games.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $games = app(TeelleXlsxGameReader::class)->read($file);
        $this->assertCount(1, $games);
        $metadata = $games[0]['metadata'];
        $this->assertSame(['between-meals'], $metadata['situations']);
        $this->assertSame('normal', $metadata['content_priority']);
        $this->assertSame([], $metadata['alternatives']);
        @unlink($path);
    }

    /** ساخت یک xlsx واقعی در حافظه با شیت تک‌صفحه‌ای و سرستون در ردیف ۴ */
    private function buildWorkbook(array $headers, array $row): string
    {
        $path = tempnam(sys_get_temp_dir(), 'teelle').'.xlsx';
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $cells = function (array $values, int $rowNumber): string {
            $xml = '<x:row xmlns:x="http://schemas.openxmlformats.org/spreadsheetml/2006/main" r="'.$rowNumber.'">';
            $columnIndex = 0;
            foreach ($values as $letter => $value) {
                while ($this->columnNumber($letter) > $columnIndex + 1) {
                    $xml .= '<x:c r="'.$this->columnName($columnIndex + 1).$rowNumber.'" s="38" t="str" />';
                    $columnIndex++;
                }
                $columnIndex = $this->columnNumber($letter);
                $xml .= '<x:c r="'.$letter.$rowNumber.'" s="38" t="str"><x:v>'.htmlspecialchars($value, ENT_XML1).'</x:v></x:c>';
            }

            return $xml.'</x:row>';
        };

        $rowsXml = $cells($headers, 4).$cells($row, 5);

        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8"?><x:workbook xmlns:x="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><x:sheets><x:sheet name="بازی‌ها" sheetId="1" r:id="rId1"/></x:sheets></x:workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>');
        $zip->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8"?><x:worksheet xmlns:x="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><x:sheetData>'.$rowsXml.'</x:sheetData></x:worksheet>');
        $zip->close();

        return $path;
    }

    private function columnNumber(string $letters): int
    {
        $number = 0;
        foreach (str_split($letters) as $letter) {
            $number = $number * 26 + ord($letter) - 64;
        }

        return $number;
    }

    private function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $mod = ($number - 1) % 26;
            $name = chr(65 + $mod).$name;
            $number = intdiv($number - $mod, 26);
        }

        return $name;
    }
}
