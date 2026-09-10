<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChildProfileRequest;
use App\Models\ChildProfile;
use App\Models\Household;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class ChildProfileController extends Controller
{
    public function index(Request $request): View
    {
        $household = $this->householdFor($request->user());
        $children = $household->childProfiles()->orderByRaw("status = 'active' DESC")->latest()->get();

        return view('account.children.index', ['children' => $children]);
    }

    public function create(): View
    {
        return view('account.children.form', ['child' => null, 'relationship' => 'parent']);
    }

    public function store(ChildProfileRequest $request): RedirectResponse
    {
        $household = $this->householdFor($request->user());
        $data = $request->validated();

        DB::transaction(function () use ($household, $request, $data): void {
            $child = $household->childProfiles()->create([
                'nickname' => filled($data['nickname'] ?? null) ? trim($data['nickname']) : null,
                'birth_month' => $data['birth_month'],
                'status' => 'active',
            ]);
            DB::table('child_relationships')->insert([
                'child_profile_id' => $child->id,
                'user_id' => $request->user()->id,
                'relationship_code' => $data['relationship_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('account.children.index')->with('status', 'پروفایل کودک ساخته شد');
    }

    public function edit(Request $request, string $child): View
    {
        $profile = $this->ownedProfile($request->user(), $child);
        $relationship = DB::table('child_relationships')
            ->where('child_profile_id', $profile->id)->where('user_id', $request->user()->id)
            ->value('relationship_code') ?? 'caregiver';

        return view('account.children.form', ['child' => $profile, 'relationship' => $relationship]);
    }

    public function update(ChildProfileRequest $request, string $child): RedirectResponse
    {
        $profile = $this->ownedProfile($request->user(), $child);
        abort_if($profile->status !== 'active', 409);
        $data = $request->validated();

        DB::transaction(function () use ($profile, $request, $data): void {
            $profile->update([
                'nickname' => filled($data['nickname'] ?? null) ? trim($data['nickname']) : null,
                'birth_month' => $data['birth_month'],
            ]);
            $relationship = DB::table('child_relationships')
                ->where('child_profile_id', $profile->id)
                ->where('user_id', $request->user()->id);

            if ($relationship->exists()) {
                $relationship->update([
                    'relationship_code' => $data['relationship_code'],
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('child_relationships')->insert([
                    'child_profile_id' => $profile->id,
                    'user_id' => $request->user()->id,
                    'relationship_code' => $data['relationship_code'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()->route('account.children.index')->with('status', 'پروفایل کودک به‌روز شد');
    }

    public function archive(Request $request, string $child): RedirectResponse
    {
        $profile = $this->ownedProfile($request->user(), $child);

        if ($profile->status === 'active') {
            $profile->update(['status' => 'archived']);
        }

        return redirect()->route('account.children.index')->with('status', 'پروفایل بایگانی شد و اطلاعات آن حذف نشد');
    }

    private function householdFor(User $user): Household
    {
        return Household::query()->where('owner_user_id', $user->id)->firstOrFail();
    }

    private function ownedProfile(User $user, string $publicId): ChildProfile
    {
        return ChildProfile::query()
            ->where('public_id', $publicId)
            ->whereHas('household', fn ($query) => $query->where('owner_user_id', $user->id))
            ->firstOrFail();
    }
}
