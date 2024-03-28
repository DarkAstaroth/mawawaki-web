<template>
    <FichasIndex />
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de usuarios</h3>
            <div class="div card-toolbar">
                <button type="button" class="btn btn-sm btn-success me-5">
                    <i class="text-white far fa-plus"></i>
                    Nuevo
                </button>

                <button
                    type="button"
                    class="btn btn-sm btn-danger"
                    @click="generarPDF"
                >
                    <i class="text-white far fa-file"></i>
                    PDF
                </button>
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                <li v-for="tab in tabs" :key="tab.parametro" class="nav-item">
                    <a
                        :class="{
                            'nav-link': true,
                            active: tab.parametro === parametro,
                        }"
                        data-bs-toggle="tab"
                        :href="'#kt_tab_pane_' + tab.id"
                        @click="cambiarParametro(tab.parametro)"
                    >
                        {{ tab.nombre }}
                    </a>
                </li>
            </ul>

            <InputText
                class="w-100 mb-5"
                type="text"
                v-model="busqueda"
                @input="filtrarUsuarios"
                placeholder="Buscar usuario..."
            />
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead>
                        <tr
                            class="py-4 border-gray-200 fw-semibold fs-7 border-bottom"
                        >
                            <th class="min-w-150px">Nombre</th>
                            <th class="min-w-150px">Rol</th>
                            <th class="min-w-200px">Creado en</th>
                            <th class="max-w-100px">Estado</th>
                            <th class="min-w-10px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="usuario in store.usuarios" :key="usuario.id">
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <img
                                        :src="usuario.profile_photo_path"
                                        alt="foto"
                                        class="p-1 rounded-pill"
                                        width="50"
                                    />
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ usuario.persona.nombre }}
                                            {{ usuario.persona.paterno }}
                                            {{ usuario.persona.materno }}
                                        </div>
                                        <div class="py-1 bd-highlight">
                                            <small>
                                                {{ usuario.email }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-2">
                                    <div
                                        v-for="urol in usuario.roles"
                                        :key="urol.id"
                                    >
                                        <span class="badge badge-secondary">{{
                                            urol.name
                                        }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                {{
                                    new Date(usuario.created_at).toLocaleString(
                                        "es-ES",
                                        {
                                            weekday: "long",
                                            day: "numeric",
                                            month: "long",
                                            year: "numeric",
                                            hour: "numeric",
                                            minute: "numeric",
                                            second: "numeric",
                                        }
                                    )
                                }}
                            </td>
                            <td class="align-middle">
                                <InputSwitch
                                    v-model="usuario.estado"
                                    @click="cambiarEstado(usuario)"
                                />
                            </td>
                            <td class="align-middle">
                                <div class="d-flex">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-secondary dropdown-toggle btn-sm"
                                            type="button"
                                            id="dropdownMenuButton1"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            data-boundary="viewport"
                                        ></button>
                                        <ul
                                            class="dropdown-menu"
                                            aria-labelledby="dropdownMenuButton1"
                                        >
                                            <li>
                                                <a
                                                    class="dropdown-item"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-custom-class="tooltip-inverse"
                                                    data-bs-placement="bottom"
                                                    title="Ver perfil"
                                                    :href="
                                                        route(
                                                            'usuario.control',
                                                            { id: usuario.id }
                                                        )
                                                    "
                                                    ><i
                                                        class="bi bi-eye-fill fs-4"
                                                    ></i>
                                                    Ver Perfil</a
                                                >
                                            </li>
                                            <li>
                                                <a
                                                    href="#"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_1"
                                                    ><i
                                                        class="bi bi-pencil-square fs-4"
                                                    ></i>

                                                    Editar</a
                                                >
                                            </li>
                                            <li>
                                                <a
                                                    href="#"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_1"
                                                    @click="
                                                        resetModalData(
                                                            true,
                                                            usuario
                                                        )
                                                    "
                                                    ><i
                                                        class="bi bi-envelope fs-4"
                                                    ></i>

                                                    Notificar</a
                                                >
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="store.usuarios.length === 0">
                            <td colspan="5" class="text-center">
                                No hay datos
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav>
                <ul class="pagination">
                    <li
                        class="page-item"
                        :class="{ disabled: paginacion.paginaActual === 1 }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click="cambiarPaginacion(1)"
                            >Primera</a
                        >
                    </li>
                    <li
                        class="page-item"
                        :class="{ disabled: paginacion.paginaActual === 1 }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click="
                                cambiarPaginacion(paginacion.paginaActual - 1)
                            "
                            >Anterior</a
                        >
                    </li>
                    <li
                        class="page-item"
                        v-for="page in paginacion.ultimaPagina"
                        :key="page"
                        :class="{ active: paginacion.paginaActual === page }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click="cambiarPaginacion(page)"
                            >{{ page }}</a
                        >
                    </li>
                    <li
                        class="page-item"
                        :class="{
                            disabled:
                                paginacion.paginaActual ===
                                paginacion.ultimaPagina,
                        }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click="
                                cambiarPaginacion(paginacion.paginaActual + 1)
                            "
                            >Siguiente</a
                        >
                    </li>
                    <li
                        class="page-item"
                        :class="{
                            disabled:
                                paginacion.paginaActual ===
                                paginacion.ultimaPagina,
                        }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click="cambiarPaginacion(paginacion.ultimaPagina)"
                            >Última</a
                        >
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <Dialog
        v-model:visible="modalNotificacion"
        modal
        header="Enviar notificacion"
        position="top"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <form class="input-feild" v-on:submit.prevent="enviarNotificacion">
            <div class="">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 mb-5">
                            <div class="d-flex flex-column gap-2">
                                <label for="username"
                                    >Tipo de notificación</label
                                >
                                <Dropdown
                                    v-model="selectedTipoNotificacion"
                                    :options="tiposNotificacion"
                                    filter
                                    optionLabel="nombre"
                                    placeholder="Selecciona tipo de archivo"
                                    class="w-100 md:w-14rem"
                                >
                                    <template #value="slotProps">
                                        <div v-if="slotProps.value">
                                            {{ slotProps.value.nombre }}
                                        </div>
                                        <span v-else>{{
                                            slotProps.placeholder
                                        }}</span>
                                    </template>
                                    <template #option="slotProps">
                                        <div>{{ slotProps.option.nombre }}</div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <div class="d-flex flex-column gap-2">
                                <label for="titulo">Título</label>
                                <InputText v-model="notificacion.titulo" />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex flex-column gap-2">
                                <label for="username"> Mensaje</label>
                                <Textarea
                                    v-model="notificacion.mensaje"
                                    rows="5"
                                    cols="30"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col">
                            <button class="btn btn-sm btn-success">
                                Enviar Notificación
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </Dialog>

    <Toast />
</template>

<script>
import { ref } from "vue";
import { useDataUsuarios } from "../../../store/dataUsuario";
import FichasIndex from "./fichasIndex.vue";
import { useToast } from "primevue/usetoast";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

export default {
    name: "UsuariosIndex",
    components: { FichasIndex },
    setup() {
        const store = useDataUsuarios();
        const selectedTipoNotificacion = ref(null);
        const toast = useToast();

        return { store, toast, selectedTipoNotificacion };
    },
    data() {
        return {
            items: [
                {
                    label: "Options",
                    items: [
                        {
                            label: "Refresh",
                            icon: "pi pi-refresh",
                        },
                        {
                            label: "Export",
                            icon: "pi pi-upload",
                        },
                    ],
                },
            ],
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
            tiposNotificacion: [
                { id: "info", nombre: "Informativo" },
                { id: "success", nombre: "Confirmación" },
                { id: "warning", nombre: "Alerta" },
            ],
            tabs: [
                { id: 1, nombre: "Todos", parametro: "todos" },
                { id: 2, nombre: "Activos", parametro: "activos" },
                { id: 3, nombre: "Solicitudes", parametro: "solicitudes" },
                { id: 4, nombre: "Verificados", parametro: "verificados" },
                { id: 5, nombre: "Por verificar", parametro: "porVerificar" },
                { id: 6, nombre: "Inactivos", parametro: "inactivos" },
            ],
            notificacion: { tipo: "", titulo: "", mensaje: "" },
            usuarioNotificacionID: "",
            modalNotificacion: false,
            todos: [
                { title: "todo 1", description: "description 1" },
                { title: "todo 2", description: "description2" },
                { title: "todo 3", description: "description 3" },
                { title: "todo 4", description: "description 4" },
                { title: "todo 5", description: "description 5" },
            ],
        };
    },
    validations() {},
    mounted() {
        this.store
            .cargarUsuarios(1, this.busqueda, this.parametro)
            .then((respuesta) => {
                this.paginacion = respuesta;
            });
    },
    methods: {
        cambiarPaginacion(pagina, busqueda, parametro) {
            this.store
                .cargarUsuarios(pagina, this.busqueda, this.parametro)
                .then((respuesta) => {
                    this.paginacion = respuesta;
                });
        },

        abrirMenu(event) {
            this.$refs.menu[1].toggle(event);
            this.$refs.menu[1].toggle(event);
        },
        mostrarMensaje(tipo, titulo, texto) {
            this.toast.add({
                severity: tipo,
                summary: titulo,
                detail: texto,
                life: 2000,
            });
        },
        filtrarUsuarios() {
            this.store
                .cargarUsuarios(1, this.busqueda, this.parametro)
                .then((respuesta) => {
                    this.paginacion = respuesta;
                });
        },
        cambiarParametro(tabSeleccionada) {
            this.parametro = tabSeleccionada;
            this.store
                .cargarUsuarios(1, this.busqueda, this.parametro)
                .then((respuesta) => {
                    this.paginacion = respuesta;
                });
        },

        async cambiarEstado(usuario) {
            const respuesta = await this.store
                .actualizarEstado(usuario.id, usuario.estado)
                .then((respuesta) => {
                    this.mostrarMensaje(
                        "success",
                        "Operación Exitosa",
                        "Estado actualizado correctamente"
                    );
                })
                .catch((error) => {
                    this.mostrarMensaje(
                        "error",
                        "Error",
                        "No se puedo cambiar el estado del usuario"
                    );
                });
            this.store.cargarUsuarios(1, this.busqueda, this.parametro);
        },
        resetModalData: function (estado, usuario) {
            this.usuarioNotificacionID = usuario.id;
            this.modalNotificacion = estado;
        },
        async enviarNotificacion() {
            this.store
                .mandarNotificacion(
                    this.usuarioNotificacionID,
                    this.selectedTipoNotificacion.id,
                    this.notificacion
                )
                .then((respuesta) => {
                    this.mostrarMensaje(
                        "success",
                        "Operación Exitosa",
                        "Notificación con enviada éxito"
                    );
                })
                .catch((error) => {
                    this.mostrarMensaje(
                        "error",
                        "Error",
                        "No se puedo enviar la notificación"
                    );
                });
            this.notificacion = { tipo: "", titulo: "", mensaje: "" };
            this.modalNotificacion = false;
        },
        generarPDF() {
            this.store.usuariosPDF().then((respuesta) => {
                var doc = new jsPDF();

                var img = new Image();
                img.src =
                    "http://localhost:8000/assets/media/logos/logo-equino.png";
                doc.addImage(img, "JPEG", 10, 10, 25, 6);

                var titulo = "Usuarios del sistema";
                var tituloWidth =
                    (doc.getStringUnitWidth(titulo) *
                        doc.internal.getFontSize()) /
                    doc.internal.scaleFactor;
                var x = (doc.internal.pageSize.getWidth() - tituloWidth) / 2;
                doc.text(titulo, x, 20);

                var startY = 30;

                var footer = function (data) {
                    var pageCount = doc.internal.getNumberOfPages();
                    var str = "Página " + data.pageNumber;
                    var pageWidth = doc.internal.pageSize.getWidth();
                    var textWidth =
                        (doc.getStringUnitWidth(str) *
                            doc.internal.getFontSize()) /
                        doc.internal.scaleFactor;
                    doc.setFontSize(10);
                    doc.text(
                        str,
                        pageWidth - data.settings.margin.right - textWidth,
                        doc.internal.pageSize.getHeight() - 10
                    );
                    doc.text(
                        "Centro Integral de Terapias Equinas",
                        doc.internal.pageSize.getWidth() / 2,
                        doc.internal.pageSize.getHeight() - 10,
                        { align: "center" }
                    );
                };

                var options = {
                    startY: startY,
                    head: respuesta.data.cabecera,
                    body: respuesta.data.usuarios,
                    margin: { top: 20, bottom: 20 },
                    theme: "grid",
                    didDrawPage: footer,
                };

                // Obtener la fecha actual
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, "0");
                var mm = String(today.getMonth() + 1).padStart(2, "0"); // Enero es 0
                var yyyy = today.getFullYear();
                var fecha = dd + "-" + mm + "-" + yyyy;

                // Generar el nombre del archivo PDF con la fecha actual
                var nombreArchivo = "Reporte_usuario_" + fecha + ".pdf";

                doc.autoTable(options);

                doc.save(nombreArchivo);
            });
        },
    },
};
</script>
