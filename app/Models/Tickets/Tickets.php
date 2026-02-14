<?php

namespace App\Models\Tickets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tickets extends Model
{
    protected $fillable = ['title', 'description', 'status', 'priority','user_id','assignee_id'];

    public function getDescription(): string
    {



        return substr($this->description, 0, 50). ( strlen($this->description) > 50 ? "..." : null );
    }
    public function getPriority(): string{
        $priorities = [
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'urgent' => "Urgente"
        ];
        return $priorities[$this->priority];
    }
    public function getStatus(): string
    {
        $statuses = [
            'open' => 'Abierto',
            'in progress' => 'En proceso',
            'closed' => 'Cerrado',
        ];

        return $statuses[$this->status];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function assignee(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'assignee_id', 'id');
    }
}
