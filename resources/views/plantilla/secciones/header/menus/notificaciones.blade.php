{{-- <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">

    <div class="tab-content">

        <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">

            <div class="scroll-y mh-325px my-5 px-8">
                @forelse($notificaciones as $notificacion)
                    <div class="d-flex flex-stack py-4">

                        <div class="d-flex align-items-center">

                            <div class="symbol symbol-35px me-4">
                                <span class="symbol-label bg-light-{{ $notificacion->tipo }}">
                                    <i class="ki-duotone ki-abstract-28 fs-2 text-{{ $notificacion->tipo }}"></i>
                                </span>
                            </div>


                            <div class="mb-0 me-2">
                                <a href="{{ $notificacion->url }}"
                                    class="fs-6 text-gray-800 text-hover-primary fw-bold">
                                    {{ $notificacion->mensaje }}
                                </a>
                                <div class="text-gray-500 fs-7">{{ $notificacion->tipo }}</div>
                            </div>

                        </div>


                        <span class="badge badge-light fs-8">{{ $notificacion->created_at->diffForHumans() }}</span>

                    </div>

                @empty
                    <div class="text-center text-muted py-4">No hay notificaciones.</div>
                @endforelse
            </div>


            <div class="py-3 text-center border-top">
                <a href="?page=pages/user-profile/activity" class="btn btn-color-gray-600 btn-active-color-primary">
                    Ver Todas
                    <i class="ki-duotone ki-arrow-right fs-5"></i>
                </a>
            </div>
        </div>
    </div>
</div> --}}


<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">

    <!--begin::Heading-->
    <div class="d-flex flex-column bgi-no-repeat rounded-top"
        style="background-image: url('/assets/media/misc/menu-header-bg.jpg')">
        <!--begin::Title-->
        <h3 class="text-white fw-semibold px-9 mt-10 mb-6">
            Notificaciones <span class="fs-8 opacity-75 ps-3">3 Sin leer</span>
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
                                <div class="text-gray-500 fs-7">Phase 1 development</div>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Label-->
                        <span class="badge badge-light fs-8">1 hr</span>
                        <!--end::Label-->
                    </div>

                    {{-- <div class="d-flex flex-stack py-4">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px me-4">
                                <span class="symbol-label bg-light-danger">
                                    <i class="ki-duotone ki-information fs-2 text-danger"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span></i>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Title-->
                            <div class="mb-0 me-2">
                                <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">HR
                                    Confidential</a>
                                <div class="text-gray-500 fs-7">
                                    Confidential staff documents
                                </div>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Label-->
                        <span class="badge badge-light fs-8">2 hrs</span>
                        <!--end::Label-->
                    </div>
                   
                    <div class="d-flex flex-stack py-4">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px me-4">
                                <span class="symbol-label bg-light-warning">
                                    <i class="ki-duotone ki-briefcase fs-2 text-warning"><span
                                            class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Title-->
                            <div class="mb-0 me-2">
                                <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Company HR</a>
                                <div class="text-gray-500 fs-7">Corporeate staff profiles</div>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Label-->
                        <span class="badge badge-light fs-8">5 hrs</span>
                        <!--end::Label-->
                    </div>
                 
                    <div class="d-flex flex-stack py-4">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px me-4">
                                <span class="symbol-label bg-light-success">
                                    <i class="ki-duotone ki-abstract-12 fs-2 text-success"><span
                                            class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Title-->
                            <div class="mb-0 me-2">
                                <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Project
                                    Redux</a>
                                <div class="text-gray-500 fs-7">New frontend admin theme</div>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Label-->
                        <span class="badge badge-light fs-8">2 days</span>
                        <!--end::Label-->
                    </div>
                  
                    <div class="d-flex flex-stack py-4">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px me-4">
                                <span class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-colors-square fs-2 text-primary"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span></i>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Title-->
                            <div class="mb-0 me-2">
                                <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Project
                                    Breafing</a>
                                <div class="text-gray-500 fs-7">
                                    Product launch status update
                                </div>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Label-->
                        <span class="badge badge-light fs-8">21 Jan</span>
                        <!--end::Label-->
                    </div>
                 
                    <div class="d-flex flex-stack py-4">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px me-4">
                                <span class="symbol-label bg-light-info">
                                    <i class="ki-duotone ki-picture fs-2 text-info"></i>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Title-->
                            <div class="mb-0 me-2">
                                <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Banner
                                    Assets</a>
                                <div class="text-gray-500 fs-7">
                                    Collection of banner images
                                </div>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Label-->
                        <span class="badge badge-light fs-8">21 Jan</span>
                        <!--end::Label-->
                    </div>
                  
                    <div class="d-flex flex-stack py-4">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px me-4">
                                <span class="symbol-label bg-light-warning">
                                    <i class="ki-duotone ki-color-swatch fs-2 text-warning"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span><span
                                            class="path5"></span><span class="path6"></span><span
                                            class="path7"></span><span class="path8"></span><span
                                            class="path9"></span><span class="path10"></span><span
                                            class="path11"></span><span class="path12"></span><span
                                            class="path13"></span><span class="path14"></span><span
                                            class="path15"></span><span class="path16"></span><span
                                            class="path17"></span><span class="path18"></span><span
                                            class="path19"></span><span class="path20"></span><span
                                            class="path21"></span></i>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Title-->
                            <div class="mb-0 me-2">
                                <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Icon
                                    Assets</a>
                                <div class="text-gray-500 fs-7">Collection of SVG icons</div>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Label-->
                        <span class="badge badge-light fs-8">20 March</span>
                        <!--end::Label-->
                    </div> --}}

                @empty
                    <div class="text-center text-muted py-4">No hay notificaciones.</div>
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
