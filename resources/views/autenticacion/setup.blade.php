@extends('plantilla.masterLogin')
@section('contenido')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="w-lg-500px p-10">

                        <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form"
                            data-kt-redirect-url="../../demo1/dist/authentication/layouts/corporate/sign-in.html"
                            action="#">

                            <div class="text-center mb-5">
                                <h1 class="text-dark fw-bolder mb-3">¡Bienvenido!</h1>
                            </div>

                            <div class="d-flex justify-content-center border mb-5">
                                <span class="border border-secondary rounded px-5">
                                    <div class="p-2">
                                        <div class="d-flex">
                                            <img src="{{ asset(auth()->user()->profile_photo_path) }}" alt="photo_user"
                                                width="40px" class="rounded-pill" />
                                            <div class="d-flex flex-column justify-content-center mx-4">
                                                <div class="text-black-500 fw-bold fs-6">{{ auth()->user()->name }}
                                                </div>
                                                <div class="text-gray-500 fw-semibold fs-base ">{{ auth()->user()->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>

                            </div>

                            <div class="text-center mb-5">
                                <div class="text-gray-500 fw-bold fs-6">Completa tu registro</div>
                            </div>

                            <div class="fv-row mb-8">
                                <input type="text" placeholder="Nombres" name="text" autocomplete="off"
                                    class="form-control bg-transparent" />
                            </div>

                            <div class="fv-row mb-8">
                                <input type="text" placeholder="Apellido paterno" name="text" autocomplete="off"
                                    class="form-control bg-transparent" />
                            </div>

                            <div class="fv-row mb-8">
                                <input type="text" placeholder="Apellido materno" name="text" autocomplete="off"
                                    class="form-control bg-transparent" />
                            </div>


                            <div class="fv-row mb-8" data-kt-password-meter="true">

                                <div class="mb-1">

                                    <div class="position-relative mb-3">
                                        <input class="form-control bg-transparent" type="password" placeholder="Contraseña"
                                            name="password" autocomplete="off" />
                                        <span
                                            class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                            data-kt-password-meter-control="visibility">
                                            <i class="ki-duotone ki-eye-slash fs-2"></i>
                                            <i class="ki-duotone ki-eye fs-2 d-none"></i>
                                        </span>
                                    </div>


                                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                    </div>

                                </div>


                                <div class="text-muted">
                                    Utilice 8 o más caracteres con una combinación de letras, números y símbolos.
                                </div>

                            </div>


                            <div class="fv-row mb-8">

                                <input placeholder="Repite la contraseña" name="confirm-password" type="password"
                                    autocomplete="off" class="form-control bg-transparent" />

                            </div>


                            <div class="fv-row mb-8">
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="toc" value="1" />
                                    <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">I Accept the
                                        <a href="#" class="ms-1 link-primary">Terms</a></span>
                                </label>
                            </div>


                            <div class="d-grid mb-10">
                                <button type="submit" id="kt_sign_up_submit" class="btn btn-primary">

                                    <span class="indicator-label">Enviar solicitud</span>


                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>

                                </button>
                            </div>


                            <div class="text-gray-500 text-center fw-semibold fs-6">Already have an Account?
                                <a href="../../demo1/dist/authentication/layouts/corporate/sign-in.html"
                                    class="link-primary fw-semibold">Sign in</a>
                            </div>

                        </form>

                    </div>

                </div>
            </div>


            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
                style="background-image: url({{ asset('assets/media/misc/auth-bg.png)') }}">

                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">

                    <a href="../../demo1/dist/index.html" class="mb-0 mb-lg-12">
                        <img alt="Logo" src="{{ asset('assets/media/logos/logo-scallia.png') }}"
                            class="h-60px h-lg-75px" />
                    </a>

                    <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20"
                        src="{{ asset('assets/media/misc/auth-screens.png') }}" alt="" />


                    <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Fast, Efficient and
                        Productive</h1>


                    <div class="d-none d-lg-block text-white fs-base text-center">In this kind of post,
                        <a href="#" class="opacity-75-hover text-warning fw-bold me-1">the blogger</a>introduces a
                        person they’ve interviewed
                        <br />and provides some background information about
                        <a href="#" class="opacity-75-hover text-warning fw-bold me-1">the interviewee</a>and their
                        <br />work following this is a transcript of the interview.
                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
