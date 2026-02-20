<?php

namespace App\Enums\Tickets;

enum TicketPriority : String
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';


    public function label() : String
    {
        return match($this){
            self::LOW => 'Baja',
            self::MEDIUM => 'Media',
            self::HIGH => 'Alta',
            self::URGENT => 'Urgente'
        };
    }
    public function color() : String
    {
        return match($this){
            self::LOW => 'secondary',
            self::MEDIUM => 'warning',
            self::HIGH, self::URGENT => 'danger'
        };
    }

}
