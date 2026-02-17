<?php

namespace App\Http\Controllers\Tickets\Admin;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Ticket\Department;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class AdminTicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Ticket::with(['user', 'category', 'department', 'assignee'])
            ->where('user_id', '!=', $user->id); // Excluir tickets propios

        // Si NO es admin ni manager, filtrar por departamento o asignación
        if (!$user->hasRole(['admin', 'manager'])) {
            $query->where(function ($q) use ($user) {
                // Ver tickets de su departamento
                if ($user->department_id) {
                    $q->where('department_id', $user->department_id);
                }
                // O tickets asignados directamente a él (aunque sean de otro depto, por si acaso)
                $q->orWhere('assignee_id', $user->id);
            });
        }

        $tickets = $query->orderBy('is_escalated', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tickets.admin.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $user = Auth::user();

        // Evitar que soporte vea sus propios tickets en el panel de admin
        if ($ticket->user_id === $user->id) {
            abort(403, 'No puedes gestionar tus propios tickets desde el panel de administración.');
        }

        // Verificar acceso si no es admin/manager
        if (!$user->hasRole(['admin', 'manager'])) {
            $hasAccess = false;
            // Acceso por departamento
            if ($user->department_id && $ticket->department_id === $user->department_id) {
                $hasAccess = true;
            }
            // Acceso por asignación directa
            if ($ticket->assignee_id === $user->id) {
                $hasAccess = true;
            }

            if (!$hasAccess) {
                abort(403, 'No tienes permiso para ver este ticket.');
            }
        }

        $departments = Department::all();
        $supportUsers = User::role(['support', 'admin', 'manager'])->get();

        return view('tickets.admin.show', compact('ticket', 'departments', 'supportUsers'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|min:5',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:' . implode(',', array_column(TicketStatus::cases(), 'value')),
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $ticket->status = TicketStatus::tryFrom($request->status);
        if (!$ticket->assignee_id) {
            $ticket->assignee_id = Auth::id();
        }
        $ticket->save();

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Respuesta enviada correctamente.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_column(TicketStatus::cases(), 'value')),
        ]);

        $ticket->status = TicketStatus::tryFrom($request->status);
        $ticket->save();

        return back()->with('success', 'Estado actualizado.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assignee_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'priority' => ['required', new Enum(TicketPriority::class)],
            'is_escalated' => 'nullable|boolean',
        ]);

        $ticket->update([
            'assignee_id' => $request->assignee_id,
            'department_id' => $request->department_id,
            'priority' => $request->priority,
            'is_escalated' => $request->boolean('is_escalated'),
        ]);

        return back()->with('success', 'Ticket actualizado/escalado correctamente.');
    }
}
