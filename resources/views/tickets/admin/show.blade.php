<x-bootstrap>
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    @endpush

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <x-alert-session-success />
                <x-alert-errors />
                <div class="card shadow-sm mb-3">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">
                                {{ $ticket->title }}
                                @if($ticket->is_escalated)
                                    <span class="badge bg-danger ms-2">ESCALADO</span>
                                @endif
                            </h5>
                            <x-badge :priority="$ticket->priority" :color="$ticket->priorityColor" :text="$ticket->priorityLabel"></x-badge>
                        </div>
                        <div class="mt-2 small text-muted d-flex flex-wrap gap-3">
                            <span>
                                <i class="bi bi-folder2-open"></i>
                                {{ $ticket->category->name }}
                            </span>
                            @if($ticket->department)
                                <span>
                                    <i class="bi bi-building"></i>
                                    {{ $ticket->department->name }}
                                </span>
                            @endif
                            <span>
                                <i class="bi bi-person"></i>
                                {{ $ticket->user->name }}
                            </span>
                            <span>
                                <i class="bi bi-clock"></i>
                                {{ $ticket->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <strong>Ticket # {{ $ticket->id }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="d-flex mb-3 ">
                            <div class="p-3 rounded border" style="max-width: 75%;">
                                <div class="small mb-1 text-muted">
                                    <i class="bi bi-person"></i> {{ $ticket->user->name }}
                                    <span class="ms-2"> <i class="bi bi-clock"></i> {{ $ticket->created_at->diffForHumans() }} </span>
                                </div>
                                <div>
                                    {{ $ticket->description }}
                                </div>
                            </div>
                        </div>
                        @forelse ($ticket->replies as $reply)
                            <div class="d-flex mb-3 {{ $reply->isFromTicketOwner() ? '' : 'justify-content-end' }}">
                                <div class="p-3 rounded {{ $reply->isFromTicketOwner() ? 'border' : 'bg-primary text-white' }}" style="max-width: 75%;">
                                    <div class="small mb-1 {{ $reply->isFromTicketOwner() ? 'text-muted' : 'text-white-50' }}">
                                        <i class="bi bi-person"></i>
                                        {{ $reply->user->name }}
                                        <span class="ms-2"><i class="bi bi-clock"></i> {{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div>
                                        {{ $reply->message }}
                                    </div>
                                    @if($reply->attachment_url)
                                        <div class="mt-2">
                                            <a href="{{ $reply->attachment_url }}" target="_blank">
                                                <img src="{{ $reply->attachment_url }}" alt="Adjunto" class="img-fluid rounded" style="max-height: 200px;">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No hay respuestas aún.</p>
                        @endforelse
                    </div>
                </div>
                @if($ticket->isClosed())
                    <div class="alert alert-danger" role="alert">
                        El ticket ha sido cerrado, ya no puedes responder.
                    </div>
                @else
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <strong>Responder</strong>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control" name="message" rows="4" required minlength="5"  placeholder="Escribe tu respuesta..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Adjuntar imagen (Opcional)</label>
                                    <input class="form-control" type="file" id="attachment" name="attachment" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select class="form-select" name="status" id="status" required>
                                        @foreach(\App\Enums\TicketStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                                                {{ $status->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Enviar respuesta
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <strong>Gestión del Ticket</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_escalated" name="is_escalated" value="1" {{ $ticket->is_escalated ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-danger" for="is_escalated">Marcar como ESCALADO</label>
                            </div>
                            <div class="mb-3">
                                <label for="department_id" class="form-label">Departamento</label>
                                <select class="form-select" name="department_id" id="department_id">
                                    <option value="">Sin asignar</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ $ticket->department_id == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="form-label">Prioridad</label>
                                <select class="form-select" name="priority" id="priority">
                                    @foreach(\App\Enums\TicketPriority::cases() as $priority)
                                        <option value="{{ $priority->value }}" {{ $ticket->priority === $priority ? 'selected' : '' }}>
                                            {{ $priority->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="assignee_id" class="form-label">Asignar a Agente</label>
                                <select class="form-select" name="assignee_id" id="assignee_id">
                                    <option value="">Sin asignar</option>
                                    @foreach($supportUsers as $user)
                                        <option value="{{ $user->id }}" {{ $ticket->assignee_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-secondary w-100">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new TomSelect("#assignee_id", {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: "Buscar agente...",
                    plugins: ['dropdown_input'],
                });
            });
        </script>
    @endpush
</x-bootstrap>
