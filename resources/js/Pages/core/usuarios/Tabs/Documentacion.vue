<template>
    <div class="col-12 col-md-12">
        <div class="d-flex gap-2">

            <Dropdown v-model="selectedTipoDocumento" :options="tiposDocumento" filter optionLabel="nombre"
                placeholder="Selecciona tipo de archivo" class="w-50 md:w-14rem">
                <template #value="slotProps">
                    <div v-if="slotProps.value">{{ slotProps.value.nombre }}</div>
                    <span v-else>{{ slotProps.placeholder }}</span>
                </template>
                <template #option="slotProps">
                    <div>{{ slotProps.option.nombre }}</div>
                </template>
            </Dropdown>

            <div class="flex-1">
                <div class="input-group w-100">
                    <input type="file" class="form-control" id="inputArchivo"
                        @change="manejarCambioArchivo($event, 'carnet')" accept="application/pdf" />
                    <button class="btn btn-primary" @click="subirArchivo('carnet')">
                        Subir
                    </button>
                </div>
            </div>
        </div>
    </div>
    <h2 class="my-10">Mis documentos</h2>
    <div class="col-12 col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
                        <th class="min-w-150px">Tipo</th>
                        <th class="min-w-150px">Nombre</th>
                        <th class="min-w-150px">Estado</th>
                        <th class="min-w-150px">Documento</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="documento in documentos" :key="documento.id">
                        <td>{{ documento.tipos_documento[0].nombre }}</td>
                        <td>{{ documento.nombre_archivo }}</td>
                        <td>{{ documento.completado ? 'Completado' : 'Sin presentar' }}</td>
                        <td>
                            <a :href="`http://localhost:8000/storage/${documento.ruta_archivo}`" target="_blank"
                                class="btn btn-icon-primary btn-text-primary">
                                <i class="fa-regular fa-file-pdf fs-1"></i>
                                Ver
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { useToast } from "vue-toastification";
import Dropdown from 'primevue/dropdown';
import { ref } from "vue";

export default {
    name: "DocumentacionUsuario",
    props: ["usuario"],
    components: { Dropdown },

    setup() {
        const toast = useToast();
        const selectedTipoDocumento = ref(null);
        const archivoSeleccionado = ref(null);

        return { toast, selectedTipoDocumento, archivoSeleccionado }
    },
    data() {
        return {
            usuario: this.usuario,
            tiposDocumento: [],
            documentos: []
        };
    },
    mounted() {
        this.obtenerTiposDocumento();
        this.obtenerDocumentos();
    },
    methods: {
        obtenerTiposDocumento() {
            const url = '/api/tipos-documento';

            axios.get(url)
                .then(response => {
                    this.tiposDocumento = response.data;
                })
                .catch(error => {
                    console.error('Error al obtener tipos de documento', error.response.data);
                });
        },
        manejarCambioArchivo(event) {
            this.archivoSeleccionado = event.target.files[0];
        },
        subirArchivo() {
            const tipoDocumento = this.selectedTipoDocumento;
            const archivo = this.archivoSeleccionado;

            if (!tipoDocumento) {
                this.toast.info("Por favor, selecciona un tipo de documento.");
                return;
            }

            if (archivo) {
                const url = `/api/subir-archivo/${this.usuario.id}`;

                const formData = new FormData();
                formData.append('tipo_documento_id', tipoDocumento.id);
                formData.append('archivo', archivo);

                axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                    .then(response => {
                        this.toast.success("Archivo subido correctamente!");
                        this.selectedTipoDocumento = null;
                        this.archivoSeleccionado = null;
                        const input = document.getElementById('inputArchivo');
                        if (input) {
                            input.value = null;
                        }
                        this.obtenerDocumentos();
                    })
                    .catch(error => {
                        this.toast.error("Error al subir el archivo!");
                    });
            } else {
                this.toast.warning("No se seleccionó ningún archivo.");
            }
        },

        obtenerDocumentos() {
            const url = `/api/obtener-documentacion/${this.usuario.id}`;

            axios.get(url)
                .then(response => {
                    console.log(response)
                    this.documentos = response.data.data;
                })
                .catch(error => {
                    console.error('Error al obtener documentos', error.response.data);
                });
        },
    },
};
</script>
   