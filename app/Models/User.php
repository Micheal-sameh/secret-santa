<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function hostedGames(): HasMany
    {
        return $this->hasMany(Game::class, 'host_id');
    }

    public function participatedGames(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_participants');
    }

    public function givenAssignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'giver_id');
    }

    public function receivedAssignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'receiver_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('is_active', true)
            ->where('ends_at', '>', now())
            ->latest();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }
}
