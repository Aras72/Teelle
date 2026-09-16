<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class SupportTicketController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $ticket = SupportTicket::query()->create([
            'user_id' => $request->user()->id,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'status' => 'open',
        ]);

        AuditLog::query()->create([
            'actor_user_id' => $request->user()->id,
            'actor_type' => 'user',
            'action' => 'support.ticket_created',
            'target_type' => SupportTicket::class,
            'target_id' => (string) $ticket->id,
            'after_json' => ['status' => 'open'],
            'request_id' => (string) Str::ulid(),
            'occurred_at' => now(),
        ]);

        return back()->with('status', 'تیکت شما ثبت شد؛ پاسخ را از همین صفحه می‌بینید');
    }
}
