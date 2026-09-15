<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Content\AuditWriter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class UserController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->hasPermission('users.view'), 403);
        $search = trim((string) $request->query('q'));

        $users = User::query()
            ->with(['roles:id,code,title', 'latestPurchase.plan', 'latestEntitlement'])
            ->when($search !== '', fn ($query) => $query->where(function ($nested) use ($search): void {
                $nested->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_e164', 'like', "%{$search}%");
            }))
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function edit(Request $request, User $user): View
    {
        abort_unless($request->user()->hasPermission('users.edit'), 403);
        abort_if($user->roles()->where('code', 'admin')->exists() && ! $request->user()->hasPermission('roles.manage'), 403);
        $user->load(['roles:id,code,title', 'latestPurchase.plan', 'latestEntitlement']);

        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user, AuditWriter $audit): RedirectResponse
    {
        $validated = $request->validated();
        $before = $user->only(['name', 'email', 'phone_e164']);

        DB::transaction(function () use ($request, $user, $validated, $before, $audit): void {
            $emailChanged = $user->email !== $validated['email'];
            $phoneChanged = $user->phone_e164 !== $validated['phone_e164'];
            $user->forceFill([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_e164' => $validated['phone_e164'],
                'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
                'phone_verified_at' => $phoneChanged ? null : $user->phone_verified_at,
            ])->save();
            $audit->write($request->user(), 'identity.user.updated', $user, $before, $user->only(['name', 'email', 'phone_e164']), $validated['reason']);
        });

        return redirect()->route('admin.content.users.edit', $user)->with('status', 'مشخصات کاربر ذخیره شد');
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user, AuditWriter $audit): RedirectResponse
    {
        $validated = $request->validated();
        $role = Role::query()->where('code', 'support_admin')->firstOrFail();
        $hadRole = $user->roles()->whereKey($role->getKey())->exists();

        DB::transaction(function () use ($request, $user, $role, $validated, $hadRole, $audit): void {
            if ($validated['support_admin']) {
                $user->roles()->syncWithoutDetaching([$role->getKey()]);
            } else {
                $user->roles()->detach($role->getKey());
            }
            $audit->write(
                $request->user(),
                'identity.support_admin.updated',
                $user,
                ['support_admin' => $hadRole],
                ['support_admin' => $validated['support_admin']],
                $validated['reason'],
            );
        });

        return back()->with('status', $validated['support_admin'] ? 'دسترسی ادمین فعال شد' : 'دسترسی ادمین برداشته شد');
    }

    public function destroy(DeleteUserRequest $request, User $user, AuditWriter $audit): RedirectResponse
    {
        abort_if((int) $request->user()->id === (int) $user->id, 403);
        abort_if($user->roles()->where('code', 'admin')->exists(), 403);
        abort_if(! $request->user()->hasPermission('roles.manage') && $user->roles()->where('code', 'support_admin')->exists(), 403);

        DB::transaction(function () use ($request, $user, $audit): void {
            $lockedUser = User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            $before = ['status' => $lockedUser->status];
            $lockedUser->forceFill(['status' => 'disabled', 'remember_token' => null])->save();
            DB::table('sessions')->where('user_id', $lockedUser->id)->delete();
            $audit->write($request->user(), 'identity.user.disabled', $lockedUser, $before, ['status' => 'disabled'], $request->validated('reason'));
        });

        return redirect()->route('admin.content.users.index')->with('status', 'حساب کاربر غیرفعال شد و دسترسی او قطع شد');
    }
}
