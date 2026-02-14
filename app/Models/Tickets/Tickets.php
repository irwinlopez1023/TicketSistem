<?php

namespace App\Models\Tickets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tickets extends Model
{
    protected $fillable = ['title', 'description', 'status', 'priority','user_id','assignee_id'];

    public function getDescriptionShortAttribute(): string
    {
        return substr($this->description, 0, 50). ( strlen($this->description) > 50 ? "..." : null );
    }
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'urgent' => 'Urgente',
            default => 'Desconocido'
        };
    }
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'info',
            'in progress' => 'warning',
            'closed' => 'success',
            default => 'secondary'
        };
    }
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'info',
            'medium' => 'primary',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status){
            'open' => 'Abierto',
            'in progress' => 'En proceso',
            'closed' => 'Cerrado',
            default => 'Desconocido'
        };
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
