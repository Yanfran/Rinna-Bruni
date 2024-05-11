<nav id="sidebar">
    <div class="sidebar-header">

        @if (!empty(Auth::user()))
            @if (!empty(($tienda = Auth::user()->EmpresaData(session()->get('tienda_id')))))
                <img alt="" src="{!! url('uploads/logos/') . '/' !!}{{ $tienda->getLogo() }}">
                </img>
            @endif
        @endif
    </div>

    {{---  MENU PEDIDOS ----}}
    @if (
            Gate::check('pedidos-list') ||
            Gate::check('cupons-list')

        )
        <ul class="list-unstyled components" id="menu-2">
            <li class="li-menu-icon collapsed" data-toggle="collapse" data-target="#pedidos_menu">
                <a href="#">
                    <i class="fas fa-angle-right"></i>
                    Pedidos
                    <span class="arrow"></span>
                </a>
            </li>
            <ul class="collapse list-unstyled components" id="pedidos_menu">
                @can('cupons-list')
                    @if ( Auth::user()->isAdmin() )
                        <li class="li-menu-icon sub">
                            <a href="{{ route('cupons.index') }}">
                                Cupones
                            </a>
                        </li>
                    @endif
                @endcan
                @can('pedidos-list')
                    <li class="li-menu-icon sub">
                        <a href="{{ route('pedidos.index') }}">
                            Pedidos
                        </a>
                    </li>
                @endcan
            </ul>
        </ul>
    @endif

    {{---  MENU ARTICULOS ----}}
    @if (
            Gate::check('existencias-list') ||
            Gate::check('product-list')

        )
        {{-- @if ( Auth::user()->isAdmin() ) --}}
            <ul class="list-unstyled components" id="menu-2">
                <li class="li-menu-icon collapsed" data-toggle="collapse" data-target="#articulos_menu">
                    <a href="#">
                        <i class="fas fa-angle-right"></i>
                        Artículos
                        <span class="arrow"></span>
                    </a>
                </li>
                <ul class="collapse list-unstyled components" id="articulos_menu">
                    @can('existencias-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('existencias.index') }}">
                                Existencias
                            </a>
                        </li>
                    @endcan
                    @can('product-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('products.index') }}">
                                Productos
                            </a>
                        </li>
                    @endcan
                </ul>
            </ul>
        {{-- @endif --}}
    @endif

    {{---  MENU CATALOGO VIRTUAL ----}}
    @if ( Gate::check('catalogos-list') )
         @if ( Auth::user()->isAdmin() )
            <ul class="list-unstyled components" id="menu-2">
                <li class="li-menu-icon collapsed" data-toggle="collapse" data-target="#catalogo_virtual_menu">
                    <a href="#">
                        <i class="fas fa-angle-right"></i>
                        Catálogo virtual
                        <span class="arrow"></span>
                    </a>
                </li>
                <ul class="collapse list-unstyled components" id="catalogo_virtual_menu">
                    @can('catalogos-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('catalogos.index') }}">
                                Catálogos
                            </a>
                        </li>
                    @endcan
                </ul>
            </ul>
        @endif
    @endif

    {{---  MENU USUARIOS ----}}
    @if (
            Gate::check('user-list') ||
            Gate::check('distribuidores-list') ||
            Gate::check('vendedores-list') ||
            Gate::check('role-list')

        )
        <ul class="list-unstyled components" id="menu-2">
            <li class="li-menu-icon collapsed" data-toggle="collapse" data-target="#usuarios_menu">
                <a href="#">
                    <i class="fas fa-angle-right"></i>
                    Usuarios
                    <span class="arrow"></span>
                </a>
            </li>
            <ul class="collapse list-unstyled components" id="usuarios_menu">
                @can('user-list')
                  @if ( Auth::user()->isAdmin() )
                    <li class="li-menu-icon sub">
                        <a href="{{ route('users.index') }}">
                            Administradores
                        </a>
                    </li>
                  @endif
                @endcan
                @can('distribuidores-list')
                    <li class="li-menu-icon sub">
                        <a href="{{ route('distribuidores.index') }}">
                            Distribuidores
                        </a>
                    </li>
                @endcan
                @can('vendedores-list')
                    @if ( Auth::user()->isAdmin() )
                        <li class="li-menu-icon sub">
                            <a href="{{ route('vendedores.index') }}">
                                Vendedores Independientes
                            </a>
                        </li>
                    @endif
                @endcan
                @can('role-list')
                    @if ( Auth::user()->isAdmin() )
                        <li class="li-menu-icon sub">
                            <a href="{{ route('roles.index') }}">
                                Roles
                            </a>
                        </li>
                    @endif
                @endcan
            </ul>
        </ul>
    @endif

    {{-- MENU CATALOGOS DE SISTEMA --}}
    @if (
           Gate::check('lineas-list') ||
           Gate::check('temporadas-list') ||
           Gate::check('clasificacion-list') ||
           Gate::check('catalogos-list') ||
           Gate::check('localidad-list') ||
           Gate::check('estados-list') ||
           Gate::check('municipios-list') ||
           Gate::check('pais-list') ||
           Gate::check('tiendas-list')
         )
         @if ( Auth::user()->isAdmin() )
            <ul class="list-unstyled components" id="menuCatalogos">
                <li class="li-menu-icon collapsed" data-toggle="collapse" data-target="#catalogos_menu">
                    <a href="#">
                        <i class="fas fa-angle-right"></i>
                        Catálogos de sistema
                        <span class="arrow"></span>
                    </a>
                </li>
                <ul class="collapse list-unstyled components" id="catalogos_menu">
                    @can('localidad-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('localidad.index') }}">
                                Colonias
                            </a>
                        </li>
                    @endcan
                    @can('estados-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('estados.index') }}">
                                Estados
                            </a>
                        </li>
                    @endcan
                    @can('descripciones-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('descripciones.index') }}">
                                Descripciones
                            </a>
                        </li>
                    @endcan
                    @can('lineas-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('lineas.index') }}">
                                Líneas
                            </a>
                        </li>
                    @endcan
                    @can('marcas-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('marcas.index') }}">
                                Marcas
                            </a>
                        </li>
                    @endcan
                    @can('municipios-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('municipios.index') }}">
                                Municipios
                            </a>
                        </li>
                    @endcan
                    @can('pais-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('pais.index') }}">
                                Pais
                            </a>
                        </li>
                    @endcan
                    @can('temporadas-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('temporadas.index') }}">
                                Temporadas
                            </a>
                        </li>
                    @endcan
                    @can('tiendas-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('tiendas.index') }}">
                                Tiendas
                            </a>
                        </li>
                    @endcan
                </ul>
            </ul>
        @endif
    @endif


    {{---  MENU AJUSTES ----}}

    @if (Gate::check('ajustes-list') || Gate::check('slider-list'))
        @if ( Auth::user()->isAdmin() )
            <ul class="list-unstyled components" id="menu-2">
                <li class="li-menu-icon collapsed" data-toggle="collapse" data-target="#empresa_menu">
                    <a href="#">
                        <i class="fas fa-angle-right"></i> Ajustes
                        <span class="arrow"></span>
                    </a>
                </li>
                <ul class="collapse list-unstyled components" id="empresa_menu">
                    @can('slider-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('slider') }}">
                                {{ trans('menu.catalogo_slider') }}
                            </a>
                        </li>
                    @endcan
                    @can('ajustes-list')
                        <li class="li-menu-icon sub">
                            <a href="{{ route('cofiguraciones', ['id' => 1]) }}">
                                Configuraciones
                            </a>
                        </li>
                    @endcan
                </ul>
            </ul>
        @endif
    @endif

    <br><br><br>

    {{---- MENU ANTERIOR  ---}}

    {{-----
    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('user-list', '/users', '<i class="fas fa-angle-right"></i> Catálogo usuarios') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('distribuidores-list', '/distribuidores', '<i class="fas fa-angle-right"></i> Catálogo distribuidores') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('vendedores-list', '/vendedores', '<i class="fas fa-angle-right"></i> Catálogo vendedores') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('product-list', '/products', '<i class="fas fa-angle-right"></i> Catálogo productos') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('existencias-list', '/existencias', '<i class="fas fa-angle-right"></i> Catálogo existencias') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('pais-list', '/pais', '<i class="fas fa-angle-right"></i> Catálogo país') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('estados-list', '/estados', '<i class="fas fa-angle-right"></i> Catálogo estados') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('municipios-list', '/municipios', '<i class="fas fa-angle-right"></i> Catálogo municipios') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('localidad-list', '/localidad', '<i class="fas fa-angle-right"></i> Catálogo colonias') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('tiendas-list', '/tiendas', '<i class="fas fa-angle-right"></i> Catálogo tiendas') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('cupons-list', '/cupons', '<i class="fas fa-angle-right"></i> Catálogo cupones') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('role-list', '/roles', '<i class="fas fa-angle-right"></i> Catálogo roles') }}

    {{ $menu = Menu::new()->addClass('list-unstyled components')->addItemClass('li-menu-icon')->linkIfCan('pedidos-list', '/pedidos', '<i class="fas fa-angle-right"></i> Catálogo pedidos') }}
    -----}}

    {{-- <ul class="list-unstyled components">
        <li class="li-menu-icon">
            <a href="/productosnegados">
                <i class="fas fa-angle-right"></i> Productos Negados
            </a>
        </li>
    </ul>
    <ul class="list-unstyled components">
        <li class="li-menu-icon">
            <a href="/productosgestion">
                <i class="fas fa-angle-right"></i> Productos Gestionables
            </a>
        </li>
    </ul> --}}

</nav>
