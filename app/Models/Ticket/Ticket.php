<?php

namespace App\Models\Ticket;

use App\Enums\Tickets\TicketPriority;
use App\Enums\Tickets\TicketStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property TicketStatus $status
 * @property int $user_id
 * @property int|null $assignee_id
 * @property int $department_id
 * @property TicketPriority $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read User|null $assignee
 * @property-read \App\Models\Ticket\Department $department
 * @property-read string $description_short
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket\TicketReply> $replies
 * @property-read int|null $replies_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereAssigneeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereDepartamentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereDepartmentId($value)
 * @mixin \Eloquent
 */
class Ticket extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'description', 'status', 'priority','department_id','user_id','assignee_id'];
    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
    ];
    public function getDescriptionShortAttribute(): string
    {
        return Str::limit($this->description, 50);
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
    public function department(): BelongsTo {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id', 'id');
    }
}
