<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="navbar-btn" id="sidebarCollapse" type="button">
                <span>
                </span>
                <span>
                </span>
                <span>
                </span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right">

                <li class="izuierda">
                    <a href="#">
                        <i class="far fa-building">
                        </i>
                        {{ Auth::user()->Tienda->nombre ?? 'No disponible' }}
                    </a>
                </li>

                <li class="izuierda">
                    <a href="#">
                        <i class="far fa-building">
                        </i>
                        {{ Auth::user()->getNombreRol() }}
                    </a>
                </li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-danger" id="notification-count"></span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu notifications notificacion-drop scrollable-menu" id="notification-list">
                        <!-- Contenido de las notificaciones -->
                    </ul>
                </li>

                <li class="dropdown derecha">
                    <a aria-expanded="false" aria-haspopup="true" class="dropdown-toggle" data-toggle="dropdown"
                        href="#" role="button">
                        <i class="far fa-user-circle">
                        </i>

                        {{ \Illuminate\Support\Str::words(Auth::user()->name, 2) }}

                        <span class="caret">
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('perfil.index', Auth::user()->id) }}">
                                {{ trans('menu.label_perfil') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('resetPassword.index', Auth::user()->id) }}">
                                {{ trans('menu.label_password') }}
                            </a>
                        </li>
                        

                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ trans('menu.label_salir') }}
                            </a>
                            <form action="{{ route('logout') }}" id="logout-form" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
