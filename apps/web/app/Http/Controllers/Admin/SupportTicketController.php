<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Content\AuditWriter;
use App\Http\Controllers\Controller;
use App\Models\PrivacyRequest;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->hasPermission('users.manage'), 403);

        $tickets = SupportTicket::query()
            ->with(['user', 'responder'])
            ->orderByRaw("CASE status WHEN 'open' THEN 0 WHEN 'in_progress' THEN 1 WHEN 'resolved' THEN 2 ELSE 3 END")
            ->latest()
            ->paginate(20, ['*'], 'tickets_page');
        $deletions = PrivacyRequest::query()
            ->with('user')
            ->where('request_type', 'deletion')
            ->latest('requested_at')
            ->paginate(20, ['*'], 'deletions_page');

        return view('admin.tickets.index', compact('tickets', 'deletions'));
    }

    public function update(Request $request, SupportTicket $supportTicket, AuditWriter $audit): RedirectResponse
    {
        abort_unless($request->user()->hasPermission('users.manage'), 403);
        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'admin_reply' => ['nullable', 'string', 'max:5000'],
        ]);
        $reply = filled($data['admin_reply'] ?? null) ? trim((string) $data['admin_reply']) : null;
        $before = ['status' => $supportTicket->status, 'has_reply' => filled($supportTicket->admin_reply)];

        $supportTicket->update([
            'status' => $data['status'],
            'admin_reply' => $reply,
            'replied_by' => $reply ? $request->user()->id : null,
            'replied_at' => $reply ? now() : null,
        ]);
        $audit->write($request->user(), 'support.ticket_updated', $supportTicket, $before, [
            'status' => $supportTicket->status,
            'has_reply' => filled($supportTicket->admin_reply),
        ]);

        return back()->with('status', 'تیکت به‌روزرسانی شد');
    }
}
