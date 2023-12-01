<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">

    <!--begin::Heading-->
    <div class="d-flex flex-column bgi-no-repeat rounded-top"
        style="background-image: url('/assets/media/misc/menu-header-bg.jpg')">
        <!--begin::Title-->
        <h3 class="text-white fw-semibold px-9 mt-10 mb-6">
            Notificaciones <span class="fs-8 opacity-75 ps-3"></span>
        </h3>
        <!--end::Title-->
        <!--begin::Tabs-->
        <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
            <li class="nav-item">
                <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab"
                    href="#kt_topbar_notifications_1">Notificaciones</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab"
                    href="#kt_topbar_notifications_3">Actividades</a>
            </li>
        </ul>
        <!--end::Tabs-->
    </div>
    <!--end::Heading-->
    <!--begin::Tab content-->
    <div class="tab-content">
        <!--begin::Tab panel-->
        <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">
            <!--begin::Items-->
            <div class="scroll-y mh-325px my-5 px-8">
                @forelse($notificaciones as $notificacion)
                    <!--begin::Item-->
                    <div class="d-flex flex-stack py-4">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px me-4">
                                <span class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-abstract-28 fs-2 text-primary"><span
                                            class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Title-->
                            <div class="mb-0 me-2">
                                <a href="#"
                                    class="fs-6 text-gray-800 text-hover-primary fw-bold">{{ $notificacion->titulo }}</a>
                                <div class="text-gray-500 fs-7">{{ Str::words($notificacion->mensaje, 10) }} ...</div>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Label-->
                        <span class="badge badge-light fs-8">1 hr</span>
                        <!--end::Label-->
                    </div>



                @empty
                    <div class="text-center text-muted py-4">No hay notificaciones nuevas.</div>
                @endforelse
            </div>
            <!--end::Items-->
            <!--begin::View more-->
            <div class="py-3 text-center border-top">
                <a href="{{ route('usuario.perfil', Auth::id()) }}"
                    class="btn btn-color-gray-600 btn-active-color-primary">
                    Ver todas
                    <i class="ki-duotone ki-arrow-right fs-5"><span class="path1"></span><span
                            class="path2"></span></i>
                </a>
            </div>
            <!--end::View more-->
        </div>
        <!--end::Tab panel-->
        <!--begin::Tab panel-->

        <!--end::Tab panel-->
        <!--begin::Tab panel-->
        <div class="tab-pane fade" id="kt_topbar_notifications_3" role="tabpanel">
            <!--begin::Items-->
            <div class="scroll-y mh-325px my-5 px-8">
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-success me-4">200 OK</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">New order</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Just now</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-danger me-4">500 ERR</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">New customer</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">2 hrs</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-success me-4">200 OK</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Payment process</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">5 hrs</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-warning me-4">300 WRN</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Search query</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">2 days</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-success me-4">200 OK</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">API connection</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">1 week</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-success me-4">200 OK</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Database restore</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Mar 5</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-warning me-4">300 WRN</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">System update</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">May 15</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-warning me-4">300 WRN</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Server OS update</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Apr 3</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-warning me-4">300 WRN</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">API rollback</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Jun 30</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-danger me-4">500 ERR</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Refund process</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Jul 10</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-danger me-4">500 ERR</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Withdrawal process</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Sep 10</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-danger me-4">500 ERR</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Mail tasks</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Dec 10</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
            </div>
            <!--end::Items-->
            <!--begin::View more-->
            <div class="py-3 text-center border-top">
                <a href="?page=pages/user-profile/activity" class="btn btn-color-gray-600 btn-active-color-primary">
                    Ver Todas
                    <i class="ki-duotone ki-arrow-right fs-5"><span class="path1"></span><span
                            class="path2"></span></i>
                </a>
            </div>
            <!--end::View more-->
        </div>
        <!--end::Tab panel-->
    </div>
    <!--end::Tab content-->
</div>
