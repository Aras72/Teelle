<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SupportTicket;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SupportTicketTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_verified_user_can_submit_a_free_subject_ticket_and_only_see_own_tickets(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        SupportTicket::query()->create([
            'user_id' => $other->id,
            'subject' => 'پیام خصوصی کاربر دیگر',
            'body' => 'این متن نباید دیده شود',
        ]);

        $this->post(route('account.tickets.store'), ['subject' => 'بدون ورود', 'body' => 'پیام'])
            ->assertRedirect(route('login'));
        $this->actingAs($user)->post(route('account.tickets.store'), [
            'subject' => 'پیشنهاد برای یک موضوع تازه',
            'body' => 'این تیکت محدود به حذف حساب نیست و موضوع آزاد دارد.',
        ])->assertRedirect()->assertSessionHas('status');

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $user->id,
            'subject' => 'پیشنهاد برای یک موضوع تازه',
            'status' => 'open',
        ]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'support.ticket_created', 'actor_user_id' => $user->id]);
        $this->actingAs($user)->get(route('account.show'))->assertOk()
            ->assertSee('پشتیبانی و تیکت‌ها')->assertSee('پیشنهاد برای یک موضوع تازه')
            ->assertDontSee('پیام خصوصی کاربر دیگر');
    }

    public function test_staff_with_user_management_can_reply_and_user_sees_the_answer(): void
    {
        $user = User::factory()->create();
        $ticket = SupportTicket::query()->create([
            'user_id' => $user->id,
            'subject' => 'سؤال درباره عضویت',
            'body' => 'پاسخ این سؤال را از کجا ببینم؟',
        ]);
        $editor = $this->staff('content_editor');
        $admin = $this->staff('admin');

        $this->actingAs($editor)->get(route('admin.content.tickets.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.content.tickets.index'))
            ->assertOk()->assertSee('تیکت‌ها')->assertSee('سؤال درباره عضویت');
        $this->actingAs($admin)->put(route('admin.content.tickets.update', $ticket), [
            'status' => 'resolved',
            'admin_reply' => 'پاسخ شما آماده است و از همین بخش دیده می‌شود.',
        ])->assertRedirect()->assertSessionHas('status', 'تیکت به‌روزرسانی شد');

        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'status' => 'resolved',
            'replied_by' => $admin->id,
        ]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'support.ticket_updated', 'actor_user_id' => $admin->id]);
        $this->actingAs($user)->get(route('account.show'))->assertOk()
            ->assertSee('پاسخ تیله')->assertSee('پاسخ شما آماده است و از همین بخش دیده می‌شود.');
    }

    private function staff(string $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('code', $role)->value('id'));

        return $user;
    }
}
