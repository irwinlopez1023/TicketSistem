<?php

namespace App\Policies;

use App\Enums\Tickets\TicketStatus;
use App\Models\Ticket\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }
    public function view(User $user, Ticket $ticket){

        if ($user->can('tickets.view.all')) {
            return true;
        }
        return $ticket->user_id === $user->id;
    }
    public function create(User $user){
        return $user->can('tickets.create');
    }
    public function reply(User $user, Ticket $ticket)
    {
        if (!$user->can('tickets.reply') || $ticket->isClosed()) return false;
        if ($user->can('tickets.view.all')) return true;
        if ($ticket->user_id !== $user->id) return false;
        if ($ticket->status !== TicketStatus::OPEN) return true;
       $replies = $ticket->replies()->latest('created_at')->take(3)->get();
       return ($replies->where('user_id', $user->id)->count() < 3 );

    }

    public function close(User $user, Ticket $ticket)
    {
        if (!$user->can('tickets.reply') || $ticket->isClosed()) return false;
        if ($user->can('tickets.view.all')) return true;
        if ($ticket->user_id !== $user->id) return false;
        return true;
    }

}
