<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
    data-kt-menu="true">

    <div class="menu-item px-3">
        <div class="menu-content d-flex align-items-center px-3">

            <div class="symbol symbol-50px me-5">
                <img alt="Logo" src="{{ asset('assets/media/avatars/300-1.jpg') }}" />
            </div>


            <div class="d-flex flex-column">
                <div class="fw-bold d-flex align-items-center fs-5">
                    @if (auth()->check())
                        {{ auth()->user()->nombres }} {{ auth()->user()->paterno }} {{ auth()->user()->materno }}
                    @endif
                    <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span>
                </div>
                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                    @if (auth()->check())
                        {{ auth()->user()->email }}
                    @endif
                </a>
            </div>

        </div>
    </div>


    <div class="separator my-2"></div>


    <div class="menu-item px-5">
        <a href="{{ route('usuario.perfil', auth()->user()->id) }}" class="menu-link px-5">
            Mi Perfíl
        </a>
    </div>











    <div class="separator my-2"></div>






    <div class="menu-item px-5">
        <a href="/logout" class="menu-link px-5">
            Salir
        </a>
    </div>

</div>
