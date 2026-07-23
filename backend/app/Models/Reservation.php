<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'member_id',
        'queue_position',
        'status', // pending, ready_for_pickup, fulfilled, expired, cancelled
        'reserved_at',
        'expires_at',
    ];

    protected $casts = [
        'queue_position' => 'integer',
        'reserved_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
