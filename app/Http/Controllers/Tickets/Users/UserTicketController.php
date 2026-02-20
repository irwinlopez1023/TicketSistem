<?php

namespace App\Http\Controllers\Tickets\Users;

use App\Enums\Tickets\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Ticket\Department;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketReply;
use Illuminate\Http\Request;

class UserTicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();

        return view('tickets.users.index',compact('tickets'));
    }

    public function create()
    {
        $this->authorize('create', Ticket::class);
        $departments = Department::all();
        return view('tickets.users.create', compact('departments'));
    }
    public function store(Request $request)
    {

        $this->authorize('create', Ticket::class);
        $request->validate([
            'title' => 'required|min:10|max:255',
            'description' => 'required|min:10',
            'priority' => 'required|in:low,medium,high,urgent',
            'department_id' => 'required|exists:departments,id'
        ]);

        Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'department_id' => $request->department_id,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('user.tickets.index')->with('success', 'Ticket creado con éxito');

    }

    public function reply(Request $request, string $id)
    {

        $ticket = Ticket::findOrFail($id);

        $this->authorize('reply', $ticket);
        $request->validate([
            'message' => 'required|min:10',
        ]);

        if (Auth()->user()->id != $ticket->user_id){
            if (empty($ticket->assignee_id)){ $ticket->assignee_id = Auth()->user()->id; }
            $ticket->status = TicketStatus::IN_PROGRESS;
        }else{
            $ticket->status = TicketStatus::OPEN;
        }
        $ticket->save();

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        return redirect()->route('user.tickets.show', $ticket->id)->with('success', 'Respuesta enviada con éxito');
    }

    public function close(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->authorize('close', $ticket);

        $ticket->status = TicketStatus::CLOSED;
        $ticket->save();
        return redirect()->route('user.tickets.show', $ticket->id)->with('success', 'Ticket cerrado.');

    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->authorize('view', $ticket);

        return view('tickets.users.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
