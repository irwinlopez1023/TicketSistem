<?php

namespace App\Http\Controllers\Tickets\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tickets\Tickets;

class UserTicketController extends Controller
{
    public function index()
    {
        $tickets = Tickets::where('user_id', auth()->id())->get();

        return view('tickets.users.index',compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tickets.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:10|max:255',
            'description' => 'required|min:10',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        Tickets::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'user_id' => auth()->id()
        ]);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
