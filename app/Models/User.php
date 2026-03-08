<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Billable, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_locked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_locked' => 'boolean',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (User $user) {
            if (! $user->isForceDeleting()) {
                return;
            }

            $user->contacts()->each(function (Contact $contact) {
                $contact->delete();
            });
        });
    }

    // check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === '1';
    }

    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
