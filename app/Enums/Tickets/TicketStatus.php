<?php

namespace App\Enums\Tickets;

enum TicketStatus: string
{
    case OPEN  = 'open';
    case IN_PROGRESS = 'in progress';
    case ANSWERED = 'answered';
    case CLOSED = 'closed';

    public function label() : String
    {
        return match($this) {
            self::OPEN => 'Abierto',
            self::IN_PROGRESS => 'En Proceso',
            self::ANSWERED => 'Respondido',
            self::CLOSED => 'Cerrado',
        };
    }
    public function color() : String
    {

        return match($this) {
            self::OPEN => 'info',
            self::IN_PROGRESS, self::ANSWERED => 'warning',
            self::CLOSED => 'danger',
        };
    }
}


