<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // ─── Role Helpers ────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOfficer(): bool
    {
        return $this->role === 'officer';
    }

    public function isFarmer(): bool
    {
        return $this->role === 'farmer';
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    // A user (farmer role) has one farmer profile
    public function farmer()
    {
        return $this->hasOne(Farmer::class);
    }

    // Notifications sent to this user
    public function appNotifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    // Schemes created by this user (admin)
    public function createdSchemes()
    {
        return $this->hasMany(Scheme::class, 'created_by');
    }

    // Get unread notification count
    public function unreadNotificationsCount()
    {
        return $this->appNotifications()->where('is_read', false)->count();
    }

    // ─── Casts ────────────────────────────────────────────────────────────────

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }
}
