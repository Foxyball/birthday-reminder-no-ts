<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'status',
        'name',
        'email',
        'phone',
        'birthday',
        'image',
        'notes',
        'gift_ideas',
        'is_locked',
        'locked_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'is_locked' => 'boolean',
            'locked_at' => 'datetime',
            'birthday' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the full URL to the contact's image.
     */
    public function getImageUrlAttribute(): ?string
    {
        return ImageHelper::url($this->image);
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Contact $contact) {
            if ($contact->image) {
                ImageHelper::delete($contact->image);
            }
        });
    }
}
