@php
    $headerClass = 'bg-dark'; // Por defecto

    if (Auth::check()) {
        if (session()->has('impersonated_by')) {
             $headerClass = 'bg-danger'; // Si está suplantando, rojo para alertar
        } elseif (Auth::user()->hasRole('admin')) {
            $headerClass = 'bg-danger'; // Admin siempre rojo
        } elseif (Auth::user()->department) {
            // Si tiene departamento, usa el color del departamento (primary, success, etc.)
            // Asumimos que el color guardado es el nombre de la clase de bootstrap (ej: primary)
            $headerClass = 'bg-' . Auth::user()->department->color;
        }
    }
@endphp

@if(session()->has('impersonated_by'))
    <div class="bg-warning text-dark text-center py-1 fw-bold">
        Estás suplantando a {{ Auth::user()->name }}.
        <form action="{{ route('impersonate.stop') }}" method="POST" class="d-inline ms-2">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-dark">Volver a mi cuenta</button>
        </form>
    </div>
@endif

<header
    class="navbar sticky-top {{ $headerClass }} flex-md-nowrap p-0 shadow"
    data-bs-theme="dark"
>
    <a
        class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white"
        href="#"
    >
        {{ config('app.name','Laravel') }}
        @if(Auth::check() && Auth::user()->department)
            <small class="d-block fw-light" style="font-size: 0.7rem;">{{ Auth::user()->department->name }}</small>
        @endif
    </a
    >
    <ul class="navbar-nav flex-row d-md-none">
        <li class="nav-item text-nowrap">
            <button
                class="nav-link px-3 text-white"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSearch"
                aria-controls="navbarSearch"
                aria-expanded="false"
                aria-label="Toggle search"
            >
                <svg class="bi" aria-hidden="true">
                    <use xlink:href="#search"></use>
                </svg>
            </button>
        </li>
        <li class="nav-item text-nowrap">
            <button
                class="nav-link px-3 text-white"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu"
                aria-controls="sidebarMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <svg class="bi" aria-hidden="true">
                    <use xlink:href="#list"></use>
                </svg>
            </button>
        </li>
    </ul>
    <div id="navbarSearch" class="navbar-search w-100 collapse">
        <input
            class="form-control w-100 rounded-0 border-0"
            type="text"
            placeholder="Search"
            aria-label="Search"
        />
    </div>
    <div class="navbar-nav ms-auto me-3 d-none d-md-block">
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->name ?? 'Usuario' }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end position-absolute">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Cerrar sesión</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
