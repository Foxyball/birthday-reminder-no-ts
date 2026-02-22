<?php

namespace App\Models;

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
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the full URL to the contact's image.
     */
//    public function getImageUrlAttribute(): ?string
//    {
//        return ImageHelper::url($this->image);
//    }

    /**
     * Boot method to handle model events.
     */
//    protected static function boot()
//    {
//        parent::boot();
//
//        // Clean up image file when contact is deleted
//        static::deleting(function (Contact $contact) {
//            if ($contact->image) {
//                ImageHelper::delete($contact->image);
//            }
//        });
//    }
}
