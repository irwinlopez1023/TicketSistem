<x-bootstrap>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestión de Tickets</h1>
            <a href="{{ route('user.tickets.create') }}" class="btn btn-primary">Crear Ticket</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Asunto</th>
                                <th>Usuario</th>
                                <th>Departamento</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Asignado a</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="{{ $ticket->is_escalated ? 'table-danger' : '' }}">
                                    <td>
                                        #{{ $ticket->id }}
                                        @if($ticket->is_escalated)
                                            <i class="bi bi-exclamation-triangle-fill text-danger" title="Escalado"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-decoration-none fw-bold text-dark">
                                            {{ Str::limit($ticket->title, 40) }}
                                        </a>
                                    </td>
                                    <td>{{ $ticket->user->name }}</td>
                                    <td>{{ $ticket->department->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $ticket->status->color() }}">
                                            {{ $ticket->status->label() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $ticket->priority->color() }}">
                                            {{ $ticket->priority->label() }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->assignee->name ?? 'Sin asignar' }}</td>
                                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        No hay tickets registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-bootstrap>
