<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * DEC-060: مالک خواستار ویرایش کامل متن و جزئیات پلن‌های تیله جیگری شد.
 * ستون توضیحات فقط-افزایشی است و به هیچ جدول دیگری دست نمی‌زند (قرارداد آپدیت امن).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table): void {
            $table->string('description', 500)->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table): void {
            $table->dropColumn('description');
        });
    }
};
