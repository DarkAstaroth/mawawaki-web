<template>
    <div class="col-12 col-md-12 mb-5">
        <div class="d-flex gap-2 flex-sm-row flex-column">
            <Dropdown v-model="selectedTipoDocumento" :options="tiposDocumento" filter optionLabel="nombre"
                placeholder="Selecciona tipo de documento" class="w-100 md:w-14rem">
                <template #value="slotProps">
                    <div v-if="slotProps.value">{{ slotProps.value.nombre }}</div>
                    <span v-else>{{ slotProps.placeholder }}</span>
                </template>
                <template #option="slotProps">
                    <div>{{ slotProps.option.nombre }}</div>
                </template>
            </Dropdown>

            <div class="d-flex flex-row w-100">
                <div :class="['d-flex input-group d-flex align-items-center gap-2']">
                    <div class="">
                        <input type="file" class="form-control flex-1" id="inputArchivo"
                            @change="manejarCambioArchivo($event, 'carnet')" accept="application/pdf" />
                    </div>

                    <Button v-if="!this.store.cargarDocumento" icon="pi pi-upload" :class="[
                'rounded d-flex justify-content-center p-button-label gap-2',
                esResponsivo ? 'p-2' : '',
            ]" @click="subirArchivo('carnet')">
                        <Icon icon="material-symbols:upload" width="20" height="20" />
                        Subir
                    </Button>

                    <div v-else class="d-flex flex-grow-1 p-1">
                        <ProgressSpinner style="width: 30px; height: 30px" strokeWidth="5" />
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-12">
        <div class="table-responsive">
            <table v-if="this.store.documentos.length > 0" class="table table-bordered">
                <!-- Encabezado de la tabla -->
                <thead>
                    <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
                        <th class="min-w-150px">Tipo</th>
                        <th class="min-w-150px">Nombre original</th>
                        <th class="min-w-150px">Nombre asignado</th>
                        <th class="min-w-150px">Estado</th>
                        <th class="min-w-150px">Revisión</th>
                        <th class="min-w-150px">Documento</th>
                        <th class="min-w-150px">Acciones</th>
                    </tr>
                </thead>
                <!-- Cuerpo de la tabla -->
                <tbody>
                    <tr v-for="documento in this.store.documentos" :key="documento.id">
                        <!-- Contenido de cada fila -->
                        <td>{{ documento.tipo_documento.nombre }}
                            <Badge v-if="documento.tipo_documento.unico" value="único" severity="info"></Badge>
                        </td>
                        <td>{{ documento.descripcion }}</td>
                        <td>{{ documento.nombre_archivo }}</td>
                        <td>
                            <Badge :value="documento.completado === 1 ? 'Cargado' : 'Sin presentar'"
                                :severity="documento.completado === 1 ? 'success' : 'danger'"></Badge>
                        </td>
                        <td>
                            <Badge :value="documento.estado_revision === 1 ? 'Aprobado' : 'Pendiente'"
                                :severity="documento.estado_revision === 1 ? 'success' : 'warning'"></Badge>
                        </td>
                        <td>
                            <a :href="`${this.valorDominio}/storage/${documento.ruta_archivo}`" target="_blank"
                                class="btn btn-danger btn-sm">
                                <i class="fi fi-rr-file"></i>
                                Ver
                            </a>
                        </td>
                        <td>
                            <Button @click="confirm2($event, documento.id)" icon="pi pi-times" severity="danger" text
                                rounded aria-label="Cancel" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Mensaje cuando no hay documentos -->
            <div v-else class="text-center py-4">
                <img src="/assets/ilustraciones/sin_doc.svg" alt="" width="150" height="150" />
                <p>No hay documentos.</p>
            </div>
        </div>
    </div>
    <ConfirmPopup></ConfirmPopup>
    <Toast />
</template>

<script>
import { useDataPerfil } from "../../../../store/dataPerfil";
import axios from "axios";
import { useToast } from "primevue/usetoast";
import Dropdown from "primevue/dropdown";
import { ref } from "vue";
import { useConfirm } from "primevue/useconfirm";
import.meta.env.VITE_APP_BASE_URL;


export default {
    name: "DocumentacionUsuario",
    components: { Dropdown },
    created() {
        this.verificarResponsivo();
        window.addEventListener("resize", this.verificarResponsivo);
    },
    destroyed() {
        window.removeEventListener("resize", this.verificarResponsivo);
    },
    setup() {
        const toast = useToast();
        const selectedTipoDocumento = ref(null);
        const archivoSeleccionado = ref(null);
        const store = useDataPerfil();
        const confirm = useConfirm();


        const confirm2 = (event, idArchivo) => {
            confirm.require({
                target: event.currentTarget,
                message: "¿Quieres borrar el registro?",
                icon: "pi pi-info-circle",
                rejectClass: "p-button-secondary p-button-outlined p-button-sm me-5",
                acceptClass: "p-button-danger p-button-sm",
                rejectLabel: "Cancelar",
                acceptLabel: "Borrar",
                accept: () => {
                    store.eliminarDocumento(idArchivo).then(() => {
                        store.obtenerDocumentosUsuario(store.usuario.id)
                        toast.add({
                            severity: "success",
                            summary: "Confirmado",
                            detail: "Se borró el archivo",
                            life: 3000,
                        });
                    });
                },
                reject: () => {
                    toast.add({
                        severity: "error",
                        summary: "Cancelado",
                        detail: "No se realizó la acción",
                        life: 3000,
                    });
                },
            });
        };

        return { store, toast, selectedTipoDocumento, archivoSeleccionado, confirm2 };
    },
    data() {
        return {
            tiposDocumento: [],
            esResponsivo: false,
            valorDominio: import.meta.env.VITE_APP_BASE_URL
        };
    },
    mounted() {
        this.obtenerTiposDocumento();
        this.obtenerDocumentos();
        this.store.obtenerDocumentosUsuario(this.store.usuario.id)
    },
    methods: {
        verificarResponsivo() {
            this.esResponsivo = window.innerWidth < 768;
        },
        obtenerTiposDocumento() {
            const url = "/api/tipos-documento";

            axios
                .get(url)
                .then((response) => {
                    this.tiposDocumento = response.data;
                })
                .catch((error) => {
                    console.error(
                        "Error al obtener tipos de documento",
                        error.response.data
                    );
                });
        },
        manejarCambioArchivo(event) {
            this.archivoSeleccionado = event.target.files[0];
        },
        subirArchivo() {
            const tipoDocumento = this.selectedTipoDocumento;
            const archivo = this.archivoSeleccionado;

            if (!tipoDocumento) {
                this.toast.add({
                    severity: "info",
                    summary: "Aviso",
                    detail: "Debes escoger un tipo de documento",
                    life: 3000,
                });
                return;
            }

            if (archivo) {
                this.store.cargarDocumento = true
                const url = `/api/subir-archivo/${this.store.usuario.id}`;

                const formData = new FormData();
                formData.append("tipo_documento_id", tipoDocumento.id);
                formData.append("archivo", archivo);

                axios
                    .post(url, formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then((response) => {
                        this.toast.add({
                            severity: "success",
                            summary: "Confirmado",
                            detail: "el archivo se subió correctamente",
                            life: 3000,
                        });
                        this.store.obtenerDocumentosUsuario(this.store.usuario.id);

                    })
                    .catch((error) => {
                        this.toast.add({
                            severity: "error",
                            summary: "Cancelado",
                            detail: "El archivo seleccionado ya se subió",
                            life: 3000,
                        });

                    });
                this.selectedTipoDocumento = null;
                this.archivoSeleccionado = null;
                const input = document.getElementById("inputArchivo");
                if (input) {
                    input.value = null;
                }
                this.store.obtenerDocumentosUsuario(this.store.usuario.id);
            } else {
                this.toast.add({
                    severity: "info",
                    summary: "Aviso",
                    detail: "Debes seleccionar un archivo",
                    life: 3000,
                });
            }
        },

        obtenerDocumentos() {
            const url = `/api/obtener-documentacion/${this.store.usuario.id}`;

            axios
                .get(url)
                .then((response) => {
                    this.documentos = response.data.data;
                })
                .catch((error) => {
                    console.error("Error al obtener documentos", error.response.data);
                });
        },
    },
};
</script>
