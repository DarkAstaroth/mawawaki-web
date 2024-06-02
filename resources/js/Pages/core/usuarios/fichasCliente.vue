<template>
    <div class="row">
        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div
                        class="w-100 d-flex flex-column justify-content-center align-items-center"
                    >
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                store.ficha.total
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="me-3 ki-duotone ki-user-square fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6"
                                >Total</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div
                        class="w-100 d-flex flex-column justify-content-center align-items-center"
                    >
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                store.ficha.activos
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i
                                class="me-3 text-success ki-duotone ki-user-square fs-1"
                            >
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6"
                                >Activos</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div
                        class="w-100 d-flex flex-column justify-content-center align-items-center"
                    >
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                store.ficha.no_activos
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i
                                class="me-3 text-danger ki-duotone ki-user-square fs-1"
                            >
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6"
                                >Inactivos</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div
                        class="w-100 d-flex flex-column justify-content-center align-items-center"
                    >
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                store.ficha.solicitudes
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i
                                class="me-3 text-info ki-duotone ki-questionnaire-tablet fs-1"
                            >
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6"
                                >Solicitudes</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div
                        class="w-100 d-flex flex-column justify-content-center align-items-center"
                    >
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                store.ficha.verificados
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i
                                class="me-3 text-success ki-duotone ki-shield-tick fs-1"
                            >
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6"
                                >Verificados</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2 mb-5">
            <div class="card">
                <div class="card-header p-5">
                    <div
                        class="w-100 d-flex flex-column justify-content-center align-items-center"
                    >
                        <div>
                            <span class="fs-3x fw-bold p-2">{{
                                store.ficha.por_verificar
                            }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i
                                class="me-3 text-warning ki-duotone ki-shield-tick fs-1"
                            >
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="opacity-75 pt-1 fw-semibold fs-6"
                                >Por verificar</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted } from "vue";
import { useDataUsuarios } from "../../../store/dataUsuario";

export default {
    props: ["rol"],
    setup() {
        const store = useDataUsuarios();
        return { store };
    },
    mounted() {
        console.log(this.store);
        this.store.cargarFichasUsuario("cliente");
    },
};
</script>
