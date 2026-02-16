<div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
    <div
        class="offcanvas-md offcanvas-end bg-body-tertiary"
        tabindex="-1"
        id="sidebarMenu"
        aria-labelledby="sidebarMenuLabel"
    >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">
                {{ config('app.name','Laravel') }}
            </h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                data-bs-target="#sidebarMenu"
                aria-label="Close"
            ></button>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" aria-current="page" href="{{ route('user.tickets.index') }}">
                    <svg class="bi" aria-hidden="true"><use xlink:href="#house-fill"></use></svg>
                    Inicio
                </a>
            </li>


            @if(Auth()->user()->can('tickets.create'))
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" aria-current="page" href="{{ route('user.tickets.create') }}">
                    <svg class="bi" aria-hidden="true"><use xlink:href="#house-fill"></use></svg>
                    Crear ticket
                </a>
            </li>
        @endif

    </div>
</div>
