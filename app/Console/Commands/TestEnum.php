<?php

namespace App\Console\Commands;

use App\Models\Ticket\Ticket;
use Illuminate\Console\Command;

class TestEnum extends Command
{

    protected $signature = 'enum';
    protected $description = 'Command description';
    public function handle()
    {
        $tickets = Ticket::all();

        foreach ($tickets as $ticket){
            var_dump($ticket->status->color());


            break;
        }
    }
}
