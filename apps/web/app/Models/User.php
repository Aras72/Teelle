<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

#[Fillable(['name', 'email', 'phone_e164', 'locale', 'timezone', 'password', 'onboarding_completed_at', 'privacy_accepted_at', 'privacy_policy_version'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmailContract
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasPublicUlid, Notifiable;

    /** @var array<int, string>|null */
    private ?array $permissionCodeCache = null;

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'onboarding_completed_at' => 'datetime',
            'privacy_accepted_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function directPermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withPivot('granted_by')->withTimestamps();
    }

    public function playSessions(): HasMany
    {
        return $this->hasMany(PlaySession::class);
    }

    public function savedGames(): HasMany
    {
        return $this->hasMany(SavedGame::class);
    }

    public function privacyRequests(): HasMany
    {
        return $this->hasMany(PrivacyRequest::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function latestPurchase(): HasOne
    {
        return $this->hasOne(Purchase::class)->latestOfMany();
    }

    public function entitlements(): HasMany
    {
        return $this->hasMany(Entitlement::class);
    }

    public function latestEntitlement(): HasOne
    {
        return $this->hasOne(Entitlement::class)->latestOfMany();
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissionCodes(), true);
    }

    /** @param array<int, string> $permissions */
    public function hasAnyPermission(array $permissions): bool
    {
        return array_intersect($permissions, $this->permissionCodes()) !== [];
    }

    /** @return array<int, string> */
    private function permissionCodes(): array
    {
        return $this->permissionCodeCache ??= DB::table('permissions')
            ->where(function ($query): void {
                $query->whereExists(function ($direct): void {
                    $direct->selectRaw('1')
                        ->from('permission_user')
                        ->whereColumn('permission_user.permission_id', 'permissions.id')
                        ->where('permission_user.user_id', $this->getKey());
                })->orWhereExists(function ($throughRole): void {
                    $throughRole->selectRaw('1')
                        ->from('permission_role')
                        ->join('role_user', 'role_user.role_id', '=', 'permission_role.role_id')
                        ->whereColumn('permission_role.permission_id', 'permissions.id')
                        ->where('role_user.user_id', $this->getKey());
                });
            })
            ->pluck('code')
            ->all();
    }
}
