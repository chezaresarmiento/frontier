<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\UserToken
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property \Illuminate\Support\Carbon $expires_at
 *
 * @property-read User $user
 */
class UserToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token', 'expires_at'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
