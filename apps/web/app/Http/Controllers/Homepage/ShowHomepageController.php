<?php

namespace App\Http\Controllers\Homepage;

use App\Homepage\ReadPublicHeartbeat;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class ShowHomepageController extends Controller
{
    public function __invoke(ReadPublicHeartbeat $readHeartbeat): View
    {
        $heartbeatCount = $readHeartbeat();

        return view('welcome', [
            'heartbeatCount' => $heartbeatCount,
            'heartbeatDisplay' => $heartbeatCount === null ? null : $this->toPersianNumber($heartbeatCount),
        ]);
    }

    private function toPersianNumber(int $number): string
    {
        return strtr(number_format($number), [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', ',' => '٬',
        ]);
    }
}
