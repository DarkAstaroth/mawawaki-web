<template>
    <FichasIndex />
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de usuarios</h3>
            <div class="div card-toolbar flex gap-2" v-if="is('admin')">
                <button
                    type="button"
                    class="btn btn-sm btn-success"
                    @click="navigateToCreate"
                >
                    <i class="text-white far fa-plus"></i>
                    Nuevo
                </button>

                <button
                    type="button"
                    class="btn btn-sm btn-info"
                    @click="certificadosPDF"
                >
                    <i class="text-white far fa-file"></i>
                    Certificados
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

            <DataTable
                :value="store.usuarios"
                :paginator="true"
                :rows="10"
                :filters="filters"
                :rowHover="true"
                v-model:filters="filters"
                :resizableColumns="true"
                columnResizeMode="fit"
                class="p-datatable-sm"
                :rowsPerPageOptions="[10, 20, 50]"
                dataKey="id"
                :globalFilterFields="[
                    'persona.nombre',
                    'persona.paterno',
                    'persona.materno',
                    'email',
                ]"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} usuarios"
            >
                <template #header>
                    <div class="flex justify-content-between">
                        <InputText
                            v-model="filters['global'].value"
                            placeholder="Buscar..."
                            class="p-inputtext-sm"
                            style="width: 300px"
                        />
                    </div>
                </template>

                <Column header="Nombre" sortable>
                    <template #body="{ data }">
                        <div class="d-flex align-items-center">
                            <Avatar
                                :image="data.profile_photo_path"
                                alt="foto"
                                size="large"
                                shape="circle"
                            />
                            <div class="px-2 d-flex flex-column">
                                <div>
                                    {{ data.persona.nombre }}
                                    {{ data.persona.paterno }}
                                    {{ data.persona.materno }}
                                </div>
                                <div class="py-1 bd-highlight">
                                    <small>{{ data.email }}</small>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText
                            v-model="filterModel.value"
                            @input="filterCallback()"
                            placeholder="Buscar Nombre"
                            class="p-column-filter"
                        />
                    </template>
                </Column>

                <Column field="roles" header="Rol" sortable>
                    <template #body="{ data }">
                        <div class="d-flex gap-2">
                            <span
                                v-for="urol in data.roles"
                                :key="urol.id"
                                class="badge badge-secondary"
                                >{{ urol.name }}</span
                            >
                        </div>
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText
                            v-model="filterModel.value"

                            placeholder="Buscar Rol"
                            class="p-column-filter"
                        />
                    </template>
                </Column>

                <Column field="created_at" header="Creado en" sortable>
                    <template #body="{ data }">
                        {{
                            new Date(data.created_at).toLocaleString("es-ES", {
                                weekday: "long",
                                day: "numeric",
                                month: "long",
                                year: "numeric",
                                hour: "numeric",
                                minute: "numeric",
                                second: "numeric",
                            })
                        }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText
                            v-model="filterModel.value"
                            @input="filterCallback()"
                            placeholder="Buscar Fecha"
                            class="p-column-filter"
                        />
                    </template>
                </Column>

                <Column v-if="is('admin')" header="Estado">
                    <template #body="{ data }">
                        <InputSwitch
                            v-model="data.estado"
                            @change="$emit('cambiarEstado', data)"
                        />
                    </template>
                </Column>

                <Column header="Acciones">
                    <template #body="{ data }">
                        <a
                            :href="route('usuario.control', { id: data.id })"
                            v-if="is('admin|Asistente')"
                        >
                            <Button
                                icon="fi fi-rr-chart-user fs-1"
                                severity="info"
                                text
                                rounded
                                aria-label="Perfil"
                            />
                        </a>
                    </template>
                </Column>

                <template #empty>
                    <tr>
                        <td colspan="5" class="text-center">No hay datos</td>
                    </tr>
                </template>
            </DataTable>
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
import { FilterMatchMode } from "primevue/api";

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
            filters: {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
                "persona.nombre": {
                    value: null,
                    matchMode: FilterMatchMode.STARTS_WITH,
                },
                "persona.paterno": {
                    value: null,
                    matchMode: FilterMatchMode.STARTS_WITH,
                },
                "persona.materno": {
                    value: null,
                    matchMode: FilterMatchMode.STARTS_WITH,
                },
                "persona.email": {
                    value: null,
                    matchMode: FilterMatchMode.STARTS_WITH,
                },
                estado: { value: null, matchMode: FilterMatchMode.EQUALS },
            },
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
                var doc = new jsPDF("l", "pt", "letter");
                doc.setFontSize(10);
                var titulo = "Usuarios del sistema";
                var tituloWidth =
                    (doc.getStringUnitWidth(titulo) *
                        doc.internal.getFontSize()) /
                    doc.internal.scaleFactor;
                var x = (doc.internal.pageSize.getWidth() - tituloWidth) / 2;
                doc.text(titulo, x, 20);

                var startY = 30;

                var footer = function (data) {
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
                    styles: { fontSize: 10 }, // Establecer el tamaño de letra
                    didDrawPage: footer,
                };

                var today = new Date();
                var dd = String(today.getDate()).padStart(2, "0");
                var mm = String(today.getMonth() + 1).padStart(2, "0"); // Enero es 0
                var yyyy = today.getFullYear();
                var fecha = dd + "-" + mm + "-" + yyyy;
                var nombreArchivo = "Reporte_usuario_" + fecha + ".pdf";

                doc.autoTable(options);

                doc.save(nombreArchivo);
            });
        },

        certificadosPDF() {
            this.store
                .usuariosCertificado()
                .then((respuesta) => {
                    var doc = new jsPDF("l", "pt", "letter");
                    doc.setFontSize(10);
                    var titulo = "Usuarios con horas cumplidas (> 200 horas)";
                    var tituloWidth =
                        (doc.getStringUnitWidth(titulo) *
                            doc.internal.getFontSize()) /
                        doc.internal.scaleFactor;
                    var x =
                        (doc.internal.pageSize.getWidth() - tituloWidth) / 2;
                    doc.text(titulo, x, 20);

                    var startY = 30;

                    var footer = function (data) {
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

                    // Mapeo de los datos de usuarios a un formato de array
                    var bodyData = respuesta.data.usuarios.map((usuario) => [
                        usuario.nro,
                        usuario.paterno,
                        usuario.materno,
                        usuario.nombre,
                        usuario.email,
                        usuario.roles.join(", "),
                        usuario.fecha_cumple_250_horas,
                        `${usuario.tiempo_acumulado.horas} horas ${usuario.tiempo_acumulado.minutos} minutos ${usuario.tiempo_acumulado.segundos} segundos`,
                    ]);

                    var options = {
                        startY: startY,
                        head: respuesta.data.cabecera,
                        body: bodyData,
                        margin: { top: 20, bottom: 20 },
                        theme: "grid",
                        styles: { fontSize: 8 }, // Establecer el tamaño de letra
                        didDrawPage: footer,
                    };

                    var today = new Date();
                    var dd = String(today.getDate()).padStart(2, "0");
                    var mm = String(today.getMonth() + 1).padStart(2, "0"); // Enero es 0
                    var yyyy = today.getFullYear();
                    var fecha = dd + "-" + mm + "-" + yyyy;
                    var nombreArchivo =
                        "Reporte_usuario_certificado_" + fecha + ".pdf";

                    doc.autoTable(options);

                    doc.save(nombreArchivo);
                })
                .catch((error) => {
                    console.error("Error al generar el PDF:", error);
                });
        },
        navigateToCreate() {
            this.$router.push({ name: "clientes.create" });
        },
    },
};
</script>
