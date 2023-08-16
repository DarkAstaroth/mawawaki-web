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

                    <span class="menu-title">Dashboards</span><span class="menu-arrow"></span>
                </span>

                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link active" href="/dashboard">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Default</span>
                        </a>
                    </div>
                </div>
            </div>

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
                        <a class="menu-link active" href="{{ route('usuarios.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Todos los usuarios</span>
                        </a>
                    </div>
                </div>
            </div>

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
        </div>
    </div>
</div>
