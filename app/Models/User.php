<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * فیلدهایی که قابل پر شدن هستند.
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'mobile',
        'password',
        'bill_limit',
        'email_verified_at',
        'mobile_verified_at',
    ];

    /**
     * فیلدهایی که باید مخفی شوند.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تبدیل مقادیر فیلدها هنگام دریافت از دیتابیس.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mobile_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * نقش‌های کاربر
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * بررسی اینکه آیا کاربر نقش خاصی دارد یا خیر
     */
    public function hasRole(string|array $roles): bool
    {
        $userRoles = $this->roles->pluck('name')->toArray();

        if (is_array($roles)) {
            return (bool) array_intersect($roles, $userRoles);
        }

        return in_array($roles, $userRoles);
    }

    /**
     * انتساب نقش جدید به کاربر
     */
    public function assignRole(string $roleName): void
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * دسترسی به قبض‌ها (در صورت نیاز)
     */
    public function electricityBills(): HasMany
    {
        return $this->hasMany(ElectricityBill::class);
    }
    public function gasBills(): HasMany
    {
        return $this->hasMany(GasBill::class);
    }
    public function waterBills(): HasMany
    {
        return $this->hasMany(WaterBill::class);
    }
}
