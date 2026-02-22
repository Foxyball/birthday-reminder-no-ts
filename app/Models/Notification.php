<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'notifiable_id',
        'notifiable_type',
        'title',
        'message',
        'type',
        'link',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    public function isRead(): bool
    {
        return ! is_null($this->read_at);
    }

    public static function createInfoNotification(int $userId, string $title, Model $notifiable, ?string $message = null, ?string $link = null): self
    {
        return self::create([
            'id' => Str::uuid(),
            'user_id' => $userId,
            'notifiable_id' => $notifiable->getKey(),
            'notifiable_type' => $notifiable->getMorphClass(),
            'title' => $title,
            'message' => $message,
            'type' => 'info',
            'link' => $link,
        ]);
    }

    public static function createSuccessNotification(int $userId, string $title, Model $notifiable, ?string $message = null, ?string $link = null): self
    {
        return self::create([
            'id' => Str::uuid(),
            'user_id' => $userId,
            'notifiable_id' => $notifiable->getKey(),
            'notifiable_type' => $notifiable->getMorphClass(),
            'title' => $title,
            'message' => $message,
            'type' => 'success',
            'link' => $link,
        ]);
    }
}
