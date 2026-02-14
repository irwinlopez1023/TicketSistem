<x-bootstrap>
    <h1>Mis tickets</h1>
    @forelse ($tickets as $ticket)
        @if($loop->first)
       <table class="table table-striped table-responsive">
           <thead>
               <tr>
                   <td>Titulo</td>
                   <td>Descripción</td>
                   <td>Estado</td>
                   <td>Prioridad</td>
               </tr>
           </thead>
           <tbody>
           @endif
            <tr>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->description_short }}</td>
                <td><x-badge :text="$ticket->status_label" :color="$ticket->status_color"></x-badge></td>
                <td><x-badge :text="$ticket->priority_label" :color="$ticket->priority_color" />
                </td>
            </tr>
           @if($loop->last)
           </tbody>
            </table>
           @endif
    @empty
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Ningún ticket</h5>
                <p class="card-text">No has creado ningún ticket..</p>
                <a href="{{ route('user.tickets.create') }}" class="btn btn-primary">Crear ticket</a>
            </div>
        </div>
    @endforelse

</x-bootstrap>
