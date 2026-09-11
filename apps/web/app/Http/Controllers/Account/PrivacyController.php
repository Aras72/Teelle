<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Privacy\AccountDataExporter;
use App\Privacy\PrivacyRequestManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PrivacyController extends Controller
{
    public function export(Request $request, AccountDataExporter $exporter, PrivacyRequestManager $requests): StreamedResponse
    {
        $request->validate(['export_password' => ['required', 'current_password:web']]);
        $user = $request->user();
        $payload = $exporter->for($user);
        $requests->recordExport($user);
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $filename = 'teelle-account-'.$user->public_id.'-'.now()->format('Y-m-d').'.json';

        return response()->streamDownload(static function () use ($json): void {
            echo $json;
        }, $filename, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function requestDeletion(Request $request, PrivacyRequestManager $requests): RedirectResponse
    {
        $request->validate(['deletion_password' => ['required', 'current_password:web']]);
        $privacyRequest = $requests->requestDeletion($request->user());

        return back()->with('status', 'درخواست حذف ثبت شد؛ تا '.$privacyRequest->scheduled_for?->format('Y/m/d').' فرصت لغو دارید');
    }

    public function cancelDeletion(Request $request, PrivacyRequestManager $requests): RedirectResponse
    {
        $cancelled = $requests->cancelDeletion($request->user());

        return back()->with('status', $cancelled ? 'درخواست حذف حساب لغو شد' : 'درخواست حذف فعالی وجود ندارد');
    }
}
