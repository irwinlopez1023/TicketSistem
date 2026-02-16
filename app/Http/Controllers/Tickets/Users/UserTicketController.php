<?php

namespace App\Http\Controllers\Tickets\Users;

use App\Http\Controllers\Controller;
use App\Models\Ticket\Category;
use App\Models\Ticket\TicketReply;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
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
        $categories = Category::all();
        return view('tickets.users.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $this->authorize('create', Ticket::class);
        $request->validate([
            'title' => 'required|min:10|max:255',
            'description' => 'required|min:10',
            'priority' => 'required|in:low,medium,high,urgent',
            'category_id' => 'required|exists:categories,id'
        ]);

        Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'category_id' => $request->category_id,
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
            $ticket->status = 'answered';
        }else{
            $ticket->status = 'open';
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

        $ticket->status = "closed";
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
