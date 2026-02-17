<div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
    <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">
                {{ config('app.name','Laravel') }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" aria-current="page" href="{{ route('user.tickets.index') }}">
                    <svg class="bi" aria-hidden="true"><use xlink:href="#house-fill"></use></svg>
                    Mis Tickets
                </a>
            </li>
            @if(Auth()->user()->can('tickets.create'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" aria-current="page" href="{{ route('user.tickets.create') }}">
                        <i class="bi bi-plus-circle"></i>
                        Crear ticket
                    </a>
                </li>
            @endif

            @hasanyrole('admin|support')
                <li class="nav-item mt-3">
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                        <span>Administración</span>
                    </h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('admin.tickets.index') }}">
                        <i class="bi bi-speedometer2"></i>
                        Panel de Soporte
                    </a>
                </li>
            @endhasanyrole

            @hasrole('admin')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i>
                        Gestión de Usuarios
                    </a>
                </li>
            @endhasrole
        </ul>
    </div>
</div>
