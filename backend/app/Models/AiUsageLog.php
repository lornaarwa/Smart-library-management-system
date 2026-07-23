<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'chat_session_id',
        'tokens_consumed',
        'cost_estimate',
        'request_type',
        'ip_address',
    ];

    protected $casts = [
        'tokens_consumed' => 'integer',
        'cost_estimate' => 'decimal:4',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }
}
