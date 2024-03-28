<div class="overflow-hidden app-sidebar-menu flex-column-fluid">

    <div id="kt_app_sidebar_menu_wrapper" class="my-5 app-sidebar-wrapper hover-scroll-overlay-y" data-kt-scroll="true"
        data-kt-scroll-activate="true" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
        data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">

        <div class="px-3 menu menu-column menu-rounded menu-sub-indention" id="#kt_app_sidebar_menu" data-kt-menu="true"
            data-kt-menu-expand="false">

            <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-element-11 fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>

                    <span class="menu-title">Inicio</span><span class="menu-arrow"></span>
                </span>

                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link active" href="/dashboard">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Panel inicial</span>
                        </a>
                    </div>
                </div>
            </div>


            @role('admin')
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">terapias</span></div>
            @endrole

            {{-- <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-element-11 fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>

                    <span class="menu-title">Pacientes</span><span class="menu-arrow"></span>
                </span>

                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link " href="{{ route('pacientes.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Todas los pacientes</span>
                        </a>
                        <a class="menu-link " href="/dashboard">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Nueva registro</span>
                        </a>

                    </div>
                </div>
            </div> --}}


            @role('cliente')
                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="fa-solid fa-horse fa-2xl"></i>
                        </span>
                        <span class="menu-title">Pacientes</span><span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link active"
                                href="{{ route('cliente.pacientes', ['id' => auth()->user()->id]) }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Administrar</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endrole

            @role('admin')
                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="fa-solid fa-horse fa-2xl"></i>
                        </span>
                        <span class="menu-title">Pacientes</span><span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link active" href="{{ route('pacientes.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Todos los pacientes</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endrole

            @role('admin')
                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-lovely fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>

                        <span class="menu-title">Terapias</span><span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link " href="{{ route('terapias.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Todas las terapias</span>
                            </a>
                            <a class="menu-link " href="/dashboard">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Nueva Terapia</span>
                            </a>

                        </div>
                    </div>
                </div>
            @endrole


            @role('admin')
                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="fa-solid fa-horse fa-2xl"></i>
                        </span>
                        <span class="menu-title">Caballos</span><span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link active" href="{{ route('caballos.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Todos los caballos</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endrole



            @role('admin')
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Personal</span></div>

                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-calendar fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Calendario</span>
                    </span>
                </div>


                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-questionnaire-tablet fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>

                        <span class="menu-title">Actividades</span><span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link " href="{{ route('usuario.actividades', ['id' => auth()->user()->id]) }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Todas las actividades</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endrole

            @role('admin')
                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-user-square fs-1">
                                <i class="path1"></i>
                                <i class="path2"></i>
                                <i class="path3"></i>
                            </i>
                        </span>

                        <span class="menu-title">Usuarios</span><span class="menu-arrow"></span>

                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('usuarios.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Todos los usuarios</span>
                            </a>

                        </div>
                    </div>
                </div>

                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-bookmark fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>

                        <span class="menu-title">Eventos</span><span class="menu-arrow"></span>

                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('eventos.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Todos los eventos</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endrole

            @role('admin')
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Sistema</span></div>
            @endrole


            @role('admin')
                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-message-programming fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>


                        <span class="menu-title">Avisos</span><span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link " href="{{ route('avisos.gestion') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Todos los avisos</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endrole


            @role('admin')
                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-element-11 fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>


                        <span class="menu-title">Reportes</span><span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link " href="{{ route('reportes.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Todos los reportes</span>
                            </a>
                            <a class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Pacientes</span>
                            </a>

                        </div>
                    </div>
                </div>
            @endrole



            @role('admin')
                <div data-kt-menu-trigger="click" class="menu-item here menu-accordion show">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-setting-4 fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Configuraciones</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->segment(2) == 'roles' ? 'active' : '' }}"
                                href="{{ route('config.general') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">General</span>
                            </a>
                        </div>
                    </div>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->segment(2) == 'roles' ? 'active' : '' }}"
                                href="{{ route('roles.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Roles</span>
                            </a>
                        </div>
                    </div>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->segment(2) == 'permisos' ? 'active' : '' }}"
                                href="{{ route('permisos.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Permisos</span>
                            </a>
                        </div>
                    </div>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->segment(2) == 'modulos' ? 'active' : '' }}"
                                href="{{ route('modulos.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Módulos</span>
                            </a>
                        </div>
                    </div>

                </div>
            @endrole
        </div>
    </div>
</div>
