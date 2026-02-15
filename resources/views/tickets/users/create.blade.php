<x-bootstrap>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1>Crear ticket</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-control" method="POST" action="{{ route('user.tickets.store') }}">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Titulo</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Titulo" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea class="form-control" name="description" id="description" rows="4" required minlength="10"></textarea>
        </div>

        <div class="mb-3">
            <label for="priority" class="form-label">Prioridad</label>
            <select class="form-select" name="priority" id="priority" required>
                <option value="low">Baja</option>
                <option value="medium">Media</option>
                <option value="high">Alta</option>
                <option value="urgent">Urgente</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Categoría</label>
            <select class="form-select" name="category" id="category" required>

                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>



        <button type="submit" class="btn btn-primary">Crear ticket</button>
    </form>
            </div>
    </div>
    </div>
</x-bootstrap>
