<x-bootstrap>
    <div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-3">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            {{ $ticket->title }}
                        </h5>
                        <x-badge :priority="$ticket->priority" :color="$ticket->priorityColor" :text="$ticket->priorityLabel"></x-badge>
                    </div>

                    <div class="mt-2 small text-muted d-flex flex-wrap gap-3">
                        <span>
                            <i class="bi bi-folder2-open"></i>
                            {{ $ticket->category->name }}
                        </span>
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
                <div class="card-header bg-light">
                    <strong>Ticket # {{ $ticket->id }}</strong>
                </div>

                <div class="card-body">

                    <div class="d-flex mb-3 ">
                        <div class="p-3 rounded bg-light border" style="max-width: 75%;">
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
                            <div class="p-3 rounded {{ $reply->isFromTicketOwner() ? 'bg-light border' : 'bg-primary text-white' }}" style="max-width: 75%;">
                                <div class="small mb-1 {{ $reply->isFromTicketOwner() ? 'text-muted' : 'text-white-50' }}">
                                    <i class="bi bi-person"></i>
                                    {{ $reply->user->name }}
                                    <span class="ms-2"><i class="bi bi-clock"></i> {{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <div>
                                    {{ $reply->message }}
                                </div>
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

                @cannot('reply', $ticket)
                    <div class="alert alert-warning" role="alert">
                        Debes esperar a que el ticket sea contestado para poder responder.
                    </div>
                @endcannot


                    @can('reply', $ticket)
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <strong>Responder</strong>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('user.tickets.reply', $ticket) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea class="form-control" name="message" rows="4" required minlength="10"  placeholder="Escribe tu respuesta..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Enviar respuesta
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endcan

                @can('close',$ticket)
                    <form action="{{ route('user.tickets.close', $ticket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger mt-3">Cerrar ticket</button>
                    </form>
                @endcan
            @endif






        </div>
    </div>
</div>

</x-bootstrap>
