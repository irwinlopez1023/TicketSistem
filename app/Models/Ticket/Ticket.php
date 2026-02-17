<?php

namespace App\Models\Ticket;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'status', 'priority', 'category_id', 'user_id', 'assignee_id', 'department_id', 'is_escalated'];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'is_escalated' => 'boolean',
    ];

    public function getDescriptionShortAttribute(): string
    {
        return substr($this->description, 0, 50).(strlen($this->description) > 50 ? '...' : null);
    }

    public function getPriorityLabelAttribute(): string
    {
        return $this->priority->label();
    }

    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    public function getPriorityColorAttribute(): string
    {
        return $this->priority->color();
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class, 'ticket_id');
    }

    public function isClosed(): bool
    {
        return $this->status === TicketStatus::CLOSED;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id', 'id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
