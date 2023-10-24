<template>
    <div class="card card-bordered mb-5">
        <div class="card-header">
            <h3 class="card-title">Listado de caballos</h3>
            <div class="div card-toolbar">
                <a :href="route('caballo.nuevo')" class="btn btn-sm btn-success">
                    <i class="text-white far fa-plus"></i>
                    Nuevo
                </a>
            </div>
        </div>

        <div class="card-body">
            <input class="mb-5 form-control" type="text" v-model="busqueda" @input="filtrarCaballos"
                placeholder="Buscar..." />
        </div>
    </div>
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-xxl-4" v-for="(caballo, index) in caballos" :key="index">
            <div class="card card-flush h-xl-100">
                <div class="card-body py-9">
                    <div class="row gx-9 h-100">
                        <div class="col-sm-5 mb-10 mb-sm-0">
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-400px min-h-sm-100 h-100"
                                style="
                                    background-size: 100% 100%;
                                    background-image: url('/assets/media/stock/600x600/img-65.jpg');
                                "></div>
                        </div>

                        <div class="col-sm-7">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-7">
                                    <div class="d-flex flex-stack mb-6">
                                        <div class="flex-shrink-0 me-5">
                                            <span class="text-gray-400 fs-7 fw-bold me-2 d-block lh-1 pb-1">{{ caballo.apodo
                                            }}</span>
                                            <span class="text-gray-800 fs-1 fw-bold">{{ caballo.nombre }}</span>
                                        </div>

                                        <span class="badge badge-success flex-shrink-0 align-self-center py-3 px-4 fs-7">En
                                            Terapia</span>
                                    </div>

                                    <div class="d-flex align-items-center flex-wrap d-grid gap-2">
                                        <div class="d-flex align-items-center me-5 me-xl-13">
                                            <div class="symbol symbol-30px symbol-circle me-3">
                                                <img src="/assets/media/avatars/300-3.jpg" class="" alt="" />
                                            </div>

                                            <div class="m-0">
                                                <span class="fw-semibold text-gray-400 d-block fs-8">Terapeuta</span>
                                                <a href="../../demo1/dist/pages/user-profile/overview.html"
                                                    class="fw-bold text-gray-800 text-hover-primary fs-7">Manuel</a>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-30px symbol-circle me-3">
                                                <span class="symbol-label bg-success">
                                                    <i class="ki-duotone ki-abstract-41 fs-5 text-white">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                            </div>

                                            <div class="m-0">
                                                <span class="fw-semibold text-gray-400 d-block fs-8">Co - Terapeuta</span>
                                                <span class="fw-bold text-gray-800 fs-7">Victor</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <span class="fw-semibold text-gray-600 fs-6 mb-8 d-block">{{ caballo.notas }}</span>
                                </div>

                                <div class="d-flex flex-stack mt-auto bd-highlight justify-content-end">
                                    <a :href="route('caballo.detalle', caballo.id)"
                                        class="d-flex align-items-center text-primary opacity-75-hover fs-6 fw-semibold">Ver
                                        Detalles
                                        <i class="ki-duotone ki-exit-right-corner fs-4 ms-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span> </i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <nav>
        <ul class="pagination">
            <li class="page-item" :class="{ disabled: paginacion.paginaActual === 1 }">
                <a class="page-link" href="#" @click="cargarRoles(1)">Primera</a>
            </li>
            <li class="page-item" :class="{ disabled: paginacion.paginaActual === 1 }">
                <a class="page-link" href="#" @click="cargarRoles(paginacion.paginaActual - 1)">Anterior</a>
            </li>
            <li class="page-item" v-for="page in paginacion.ultimaPagina" :key="page"
                :class="{ active: paginacion.paginaActual === page }">
                <a class="page-link" href="#" @click="cargarRoles(page)">{{
                    page
                }}</a>
            </li>
            <li class="page-item" :class="{
                disabled:
                    paginacion.paginaActual === paginacion.ultimaPagina,
            }">
                <a class="page-link" href="#" @click="cargarRoles(paginacion.paginaActual + 1)">Siguiente</a>
            </li>
            <li class="page-item" :class="{
                disabled:
                    paginacion.paginaActual === paginacion.ultimaPagina,
            }">
                <a class="page-link" href="#" @click="cargarRoles(paginacion.ultimaPagina)">Última</a>
            </li>
        </ul>
    </nav>
</template>

<script>
import axios from "axios";

export default {
    name: "CaballoIndex",
    setup() { },
    data() {
        return {
            caballos: [],
            busqueda: "",
            parametro: "todos",
            paginacion: {
                total: 0,
                porPagina: 10,
                paginaActual: 1,
                ultimaPagina: 1,
                desde: 0,
                hasta: 0,
            },
        };
    },
    validations() { },
    mounted() {
        this.cargarCaballos(1);
    },
    methods: {
        filtrarCaballos() {
            this.cargarCaballos(1);
        },
        cargarCaballos(pagina) {
            const url =
                "/api/caballos?page=" +
                pagina +
                "&busqueda=" +
                this.busqueda
            axios
                .get(url)
                .then((response) => {
                    this.caballos = response.data.caballos;
                    this.paginacion = response.data.paginacion;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>
