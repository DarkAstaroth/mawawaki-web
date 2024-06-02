@extends('plantilla.masterLogin')
@section('contenido')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column  flex-lg-row flex-column-fluid">
            <div class="order-2 p-10 d-flex flex-column flex-lg-row-fluid w-lg-50 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="p-10 w-lg-500px">


                        <div class="mb-5 text-center">
                            <h1 class="mb-3 text-dark fw-bolder">Crear Nueva cuenta</h1>
                        </div>

                        <div class="mb-5 text-center">
                            <div class="text-gray-500 fw-bold fs-6">Completa tu registro</div>
                        </div>
                        <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" method="POST"
                            action="{{ route('usuario.crear') }}">
                            @csrf
                            <div class="mb-8 fv-row">
                                <label class="form-label">Nombres:</label>
                                <input type="text" placeholder="Ej: Juan Carlos" name="nombres" autocomplete="off"
                                    class="bg-transparent form-control" value="{{ old('nombres') }}"
                                    style="text-transform: uppercase;" />
                                @error('nombres')
                                    <div class="text-danger"> El campo nombres es obligatorio. </div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 mb-8 fv-row">
                                <div>

                                    <label class="form-label">Apellido Paterno:</label>
                                    <input type="text" placeholder="Ej: Perez" name="paterno" autocomplete="off"
                                        class="bg-transparent form-control" value="{{ old('paterno') }}"
                                        style="text-transform: uppercase;" />
                                    @error('paterno')
                                        <div class="text-danger"> El campo apellido paterno es obligatorio. </div>
                                    @enderror
                                </div>
                                <div>

                                    <label class="form-label">Apellido Materno:</label>
                                    <input type="text" placeholder="Ej: Torrez" name="materno" autocomplete="off"
                                        class="bg-transparent form-control" value="{{ old('materno') }}"
                                        style="text-transform: uppercase;" />
                                    @error('materno')
                                        <div class="text-danger"> El campo apellido materno es obligatorio. </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-8 fv-row">
                                <label class="form-label">Correo electrónico:</label>
                                <input type="email" placeholder="Correo electrónico" name="email" autocomplete="off"
                                    class="bg-transparent form-control" value="{{ old('email') }}" />
                                @error('email')
                                    <div class="text-danger"> El correo electrónico es obligatorio y debe ser válido. </div>
                                @enderror
                            </div>


                            <div class="mb-8 fv-row" data-kt-password-meter="true">

                                <div class="mb-1">

                                    <div class="mb-3 position-relative">
                                        <label class="form-label">Contraseña:</label>
                                        <input class="bg-transparent form-control" type="password"
                                            placeholder="Establece una contraseña segura" name="password"
                                            autocomplete="off" />
                                        @error('password')
                                            <div class="text-danger"> La contraseña es obligatoria y debe tener al menos 8
                                                caracteres. </div>
                                        @enderror
                                        <span
                                            class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                            data-kt-password-meter-control="visibility">
                                            <i class="ki-duotone ki-eye-slash fs-2"></i>
                                            <i class="ki-duotone ki-eye fs-2 d-none"></i>
                                        </span>
                                    </div>


                                    <div class="mb-3 d-flex align-items-center" data-kt-password-meter-control="highlight">
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px me-2"></div>
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px me-2"></div>
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px me-2"></div>
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px"></div>
                                    </div>

                                </div>


                                <div class="text-muted">
                                    Utilice 8 o más caracteres con una combinación de letras, números y símbolos.
                                </div>

                            </div>


                            <div class="mb-8 fv-row">
                                <label class="form-label">Confirmar contraseña:</label>
                                <input placeholder="Repite la contraseña" name="password_confirmation" type="password"
                                    autocomplete="off" class="bg-transparent form-control" />
                                @error('password_confirmation')
                                    <div class="text-danger"> La confirmación de contraseña no coincide con la contraseña.
                                    </div>
                                @enderror
                            </div>


                            <div class="mb-8 fv-row">
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="toc" value="1" />
                                    <span class="text-gray-700 form-check-label fw-semibold fs-base ms-1">Acepto los
                                        <a href="#" class="ms-1 link-primary">Terminos y Condiciones</a></span>
                                    @error('toc')
                                        <div class="text-danger"> Debes Aceptar los terminos y condiciones
                                        </div>
                                    @enderror
                                </label>
                            </div>


                            <div class="mb-10 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="indicator-label">Enviar solicitud</span>
                                        <span id="loading" style="display: none;"
                                            class="spinner-border spinner-border-sm align-middle ms-2">
                                        </span>

                                    </div>

                                </button>
                            </div>


                            <div class="text-center text-gray-500 fw-semibold fs-6">¿Ya tienes una cuenta?
                                <a href="{{ route('login') }}" class="link-primary fw-semibold">Ingresar</a>
                            </div>

                        </form>

                    </div>

                </div>
            </div>

            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2 bg-dark">

                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">

                    <a href="../../demo1/dist/index.html" class="mb-0 mb-lg-12">
                        <img alt="Logo" src="{{ asset('assets/media/logos/logo-unido.png') }}"
                            class="h-60px h-lg-75px" />
                    </a>


                    <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20"
                        src="{{ asset('assets/media/misc/Login-image.png') }}" alt="" />


                    <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Una Mirada al Éxito de la
                        Terapia con Caballos
                    </h1>


                    <div class="d-none d-lg-block text-white fs-base text-center">IConecta con la naturaleza, siente la<br>
                        poderosa presencia equina y desata tu bienestar emocional. En nuestro sitio, explorarás cómo
                        este<br>
                        enfoque innovador acelera tu camino hacia el éxito personal. ¡Bienvenido a una terapia que te<br>
                        impulsa hacia la mejor versión de ti mismo!
                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection
