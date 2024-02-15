<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Quote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quote_text',
        'author_name',
    ];

    /**
     * The users that are associated with the quote.
     */
    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_quotes');
    }

    public function getIsCachedAttribute()
    {
        return $this->attributes['isCached'] ?? false;
    }

    /**
     * Determine if the quote is a favorite for the current user.
     *
     * @return bool
     */
    public function getIsFavoriteAttribute(): bool
    {
        if (!Auth::guard('web')->check()) {
            return false;
        }

        if ($this->relationLoaded('favoritedByUsers')) {
            return $this->favoritedByUsers->contains(Auth::id());
        }

        return $this->favoritedByUsers()->where('user_id', Auth::id())->exists();
    }
}
