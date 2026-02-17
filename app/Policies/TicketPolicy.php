<?php

namespace App\Policies;

use App\Enums\TicketStatus;
use App\Models\User;
use App\Models\Ticket\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Ticket $ticket)
    {
        if ($user->can('tickets.view.all')) {
            return true;
        }

        return $ticket->user_id === $user->id;
    }

    public function create(User $user)
    {
        return $user->can('tickets.create');
    }

    public function store(User $user)
    {
        return $this->create($user);
    }

    public function reply(User $user, Ticket $ticket)
    {
        if (! $user->can('tickets.reply') || $ticket->isClosed()) {
            return false;
        }

        if ($user->can('tickets.view.all')) {
            return true;
        }

        if ($ticket->user_id !== $user->id) {
            return false;
        }

        // Permitir responder si el estado NO es OPEN
        if ($ticket->status !== TicketStatus::OPEN) {
            return true;
        }

        // Si el estado es OPEN, permitir hasta 3 respuestas consecutivas del usuario
        // Contamos las respuestas más recientes del usuario hasta que encontremos una que no sea suya
        $consecutiveReplies = 0;
        foreach ($ticket->replies()->latest()->get() as $reply) {
            if ($reply->user_id === $user->id) {
                $consecutiveReplies++;
            } else {
                break; // Encontramos una respuesta de soporte (u otro usuario), paramos de contar
            }
        }

        return $consecutiveReplies < 3;
    }

    public function close(User $user, Ticket $ticket)
    {
        if (! $user->can('tickets.reply') || $ticket->isClosed()) {
            return false;
        }

        if ($user->can('tickets.view.all')) {
            return true;
        }

        return $ticket->user_id === $user->id;
    }
}
