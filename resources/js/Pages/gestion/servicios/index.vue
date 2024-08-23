<template>
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-10 gap-4 mb-6">
            <div class="md:col-span-3 bg-gray-200 p-4 rounded-lg shadow">
                <h2 class="text-lg font-bold mb-4">Credencial</h2>
                <div v-if="pacienteActual" class="space-y-2">
                    <p>
                        <strong>Nombre:</strong>
                        {{ pacienteActual.persona.nombre }}
                        {{ pacienteActual.persona.paterno }}
                        {{ pacienteActual.persona.materno }}
                    </p>
                    <p><strong>CI:</strong> {{ pacienteActual.persona.ci }}</p>
                    <p>
                        <strong>Fecha de Nacimiento:</strong>
                        {{ pacienteActual.persona.fecha_nacimiento }}
                    </p>
                </div>
            </div>
            <div class="md:col-span-7 bg-gray-100 p-4 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Detalles usuario</h2>
                <div
                    v-if="pacienteActual"
                    class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                >
                    <p>
                        <strong>Tipo de Paciente:</strong>
                        {{ pacienteActual.tipo_paciente }}
                    </p>
                    <p>
                        <strong>Fecha de Ingreso:</strong>
                        {{ pacienteActual.fecha_ingreso }}
                    </p>
                    <p>
                        <strong>Estado de Salud:</strong>
                        {{ pacienteActual.estado_salud }}
                    </p>
                    <p>
                        <strong>Precondición:</strong>
                        {{ pacienteActual.precondicion }}
                    </p>
                    <p class="sm:col-span-2">
                        <strong>Contacto de Emergencia:</strong>
                        {{ pacienteActual.contacto_emergencia_nombre }} -
                        {{ pacienteActual.contacto_emergencia_telefono }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Botones de PrimeVue -->
        <div
            class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-6"
        >
            <div
                class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4"
            >
                <Button
                    label="Nuevo servicio"
                    icon="pi pi-plus"
                    class="p-button-primary"
                    @click="abrirDialogoNuevoServicio"
                />
                <Button
                    label="Scannear"
                    icon="pi pi-search"
                    class="p-button-secondary"
                    @click="scannear"
                />
            </div>
            <div
                class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4"
            >
                <Dropdown
                    v-model="filtroTipoServicio"
                    :options="tiposServicio"
                    optionLabel="name"
                    placeholder="Tipo de servicio"
                    class="w-full sm:w-auto"
                    @change="aplicarFiltros"
                />
                <Calendar
                    v-model="filtroRangoFechas"
                    selectionMode="range"
                    :manualInput="false"
                    placeholder="Rango de fechas"
                    class="w-full sm:w-auto"
                    @date-select="aplicarFiltros"
                />
            </div>
        </div>

        <!-- Card con tabla de servicios -->
        <Card>
            <template #title> Servicios Registrados </template>
            <template #content>
                <div class="overflow-x-auto">
                    <DataTable
                        :value="pacienteActual?.servicios || []"
                        :paginator="true"
                        :rows="10"
                        responsiveLayout="stack"
                        breakpoint="960px"
                        class="p-datatable-sm"
                    >
                        <Column field="tipo_servicio" header="Tipo de Servicio">
                            <template #body="slotProps">
                                {{ slotProps.data.tipo_servicio }}
                            </template>
                        </Column>
                        <Column
                            field="observaciones"
                            header="Observaciones"
                        ></Column>
                        <Column field="fecha_ingreso" header="Fecha de Ingreso">
                            <template #body="slotProps">
                                {{ slotProps.data.fecha_ingreso }}
                            </template>
                        </Column>
                        <Column field="fecha_final" header="Fecha Final">
                            <template #body="slotProps">
                                <span
                                    v-if="slotProps.data.fecha_final"
                                    class="inline-flex items-center px-2 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full"
                                >
                                    {{
                                        formatearFecha(
                                            slotProps.data.fecha_final
                                        )
                                    }}
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center px-2 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full"
                                >
                                    Sin finalizar
                                </span>
                            </template>
                        </Column>
                        <Column field="estado" header="Estado">
                            <template #body="slotProps">
                                <Badge
                                    :value="
                                        slotProps.data.estado
                                            ? 'Activo'
                                            : 'Inactivo'
                                    "
                                    :severity="
                                        slotProps.data.estado
                                            ? 'success'
                                            : 'danger'
                                    "
                                ></Badge>
                            </template>
                        </Column>
                        <Column header="Acciones" :exportable="false">
                            <template #body="slotProps">
                                <div class="flex justify-center space-x-2">
                                    <Button
                                        v-tooltip.bottom="{
                                            value: 'Ver detalles',
                                            showDelay: 300,
                                            hideDelay: 300,
                                        }"
                                        @click="
                                            verDetallesServicio(slotProps.data)
                                        "
                                        icon="pi pi-eye"
                                        severity="info"
                                        text
                                        rounded
                                        aria-label="Ver"
                                    />
                                    <Button
                                        v-if="isAdmin"
                                        v-tooltip.bottom="{
                                            value: 'Eliminar',
                                            showDelay: 300,
                                            hideDelay: 300,
                                        }"
                                        @click="
                                            confirmarEliminarServicio(
                                                $event,
                                                slotProps.data.id
                                            )
                                        "
                                        icon="pi pi-times"
                                        severity="danger"
                                        text
                                        rounded
                                        aria-label="Eliminar"
                                    />
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </template>
        </Card>

        <!-- Dialog para nuevo servicio -->
        <Dialog
            v-model:visible="dialogVisible"
            header="Nuevo Servicio"
            :style="{ width: '50vw' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
            :modal="true"
        >
            <form
                @submit.prevent="enviarNuevoServicio"
                class="p-fluid space-y-4"
            >
                <div class="field">
                    <label for="tipo_servicio">Tipo de Servicio</label>
                    <Dropdown
                        id="tipo_servicio"
                        v-model="nuevoServicio.tipo_servicio"
                        :options="tiposServicio"
                        optionLabel="name"
                        optionValue="value"
                        placeholder="Seleccione un tipo de servicio"
                        :class="{
                            'p-invalid':
                                v$.nuevoServicio.tipo_servicio.$invalid &&
                                v$.nuevoServicio.tipo_servicio.$dirty,
                        }"
                    />
                    <small
                        class="text-red-600"
                        v-if="v$.nuevoServicio.tipo_servicio.$error"
                    >
                        El tipo de servicio es requerido
                    </small>
                </div>
                <div class="field">
                    <label for="observaciones">Observaciones</label>
                    <Textarea
                        id="observaciones"
                        v-model="nuevoServicio.observaciones"
                        rows="3"
                    />
                </div>
                <div class="field">
                    <label for="fecha_ingreso">Fecha de Ingreso</label>
                    <Calendar
                        id="fecha_ingreso"
                        v-model="nuevoServicio.fecha_ingreso"
                        :class="{
                            'p-invalid':
                                v$.nuevoServicio.fecha_ingreso.$invalid &&
                                v$.nuevoServicio.fecha_ingreso.$dirty,
                        }"
                        dateFormat="dd/mm/yy"
                    />
                    <small
                        class="text-red-600"
                        v-if="v$.nuevoServicio.fecha_ingreso.$error"
                    >
                        La fecha de ingreso es requerida
                    </small>
                </div>
            </form>
            <template #footer>
                <Button
                    label="Cancelar"
                    icon="pi pi-times"
                    @click="cerrarDialogo"
                    class="p-button-text"
                />
                <Button
                    label="Guardar"
                    icon="pi pi-check"
                    @click="enviarNuevoServicio"
                    autofocus
                />
            </template>
        </Dialog>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import Button from "primevue/button";
import Card from "primevue/card";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Badge from "primevue/badge";
import Dialog from "primevue/dialog";
import Dropdown from "primevue/dropdown";
import Textarea from "primevue/textarea";
import Calendar from "primevue/calendar";
import { useDataPacientes } from "@/store/dataPaciente";

export default {
    components: {
        Button,
        Card,
        DataTable,
        Column,
        Badge,
        Dialog,
        Dropdown,
        Textarea,
        Calendar,
    },
    props: ["id"],
    setup() {
        const v$ = useVuelidate();
        const store = useDataPacientes();
        return { v$, store };
    },
    data() {
        return {
            pacienteActual: null,
            filtroTipoServicio: null,
            filtroRangoFechas: null,
            dialogVisible: false,
            isAdmin: false,
            tiposServicio: [
                { name: "TODOS", value: null },
                { name: "EQUITACIÓN", value: "EQUITACION" },
                { name: "EQUINOTERAPIA", value: "EQUINOTERAPIA" },
            ],
            nuevoServicio: {
                paciente_id: this.id,
                tipo_servicio: null,
                observaciones: "",
                fecha_ingreso: new Date(),
            },
        };
    },
    validations() {
        return {
            nuevoServicio: {
                tipo_servicio: { required },
                fecha_ingreso: { required },
            },
        };
    },
    created() {
        this.cargarPaciente();
    },
    methods: {
        async cargarPaciente() {
            try {
                const store = useDataPacientes();
                this.pacienteActual = await store.cargarPaciente(
                    this.id,
                    this.obtenerParametrosFiltro()
                );
            } catch (error) {
                console.error("Error al cargar el paciente:", error);
            }
        },
        obtenerParametrosFiltro() {
            return {
                tipo_servicio:
                    this.filtroTipoServicio &&
                    this.filtroTipoServicio.value !== null
                        ? this.filtroTipoServicio.value
                        : null,
                fecha_inicio:
                    this.filtroRangoFechas && this.filtroRangoFechas[0]
                        ? this.filtroRangoFechas[0].toISOString()
                        : null,
                fecha_fin:
                    this.filtroRangoFechas && this.filtroRangoFechas[1]
                        ? this.filtroRangoFechas[1].toISOString()
                        : null,
            };
        },
        aplicarFiltros() {
            this.cargarPaciente();
        },
        getTipoServicioAbrev(tipo) {
            const servicio = this.tiposServicio.find((s) => s.value === tipo);
            return servicio ? servicio.abrev : tipo;
        },
        formatearFecha(timestamp) {
            if (!timestamp) return "";
            const fecha = new Date(timestamp * 1000);
            return fecha.toLocaleDateString("es-ES", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        },
        abrirDialogoNuevoServicio() {
            this.dialogVisible = true;
            this.v$.$reset();
        },
        cerrarDialogo() {
            this.dialogVisible = false;
            this.v$.$reset();
            this.nuevoServicio = {
                paciente_id: this.id,
                tipo_servicio: null,
                observaciones: "",
                fecha_ingreso: new Date(),
            };
        },
        async enviarNuevoServicio() {
            this.v$.$touch();

            if (!this.v$.$invalid) {
                try {
                    const servicioParaEnviar = {
                        ...this.nuevoServicio,
                        tipo_servicio: this.nuevoServicio.tipo_servicio,
                    };

                    await this.store.registrarServicio(
                        this.id,
                        servicioParaEnviar
                    );

                    Swal.fire({
                        title: "¡Éxito!",
                        text: "El servicio ha sido registrado correctamente.",
                        icon: "success",
                        confirmButtonText: "OK",
                    });
                } catch (error) {
                    console.error("Error al registrar el servicio:", error);

                    Swal.fire({
                        title: "Error",
                        text: "No se pudo registrar el servicio. Por favor, inténtelo de nuevo.",
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                } finally {
                    this.cerrarDialogo();
                }
            }
        },
        verDetallesServicio(servicio) {
            console.log("Ver detalles del servicio:", servicio);
            // Implementa la lógica para ver detalles del servicio
        },
        confirmarEliminarServicio(event, id) {
            // Implementa la lógica para confirmar la eliminación del servicio
            console.log("Confirmar eliminación del servicio:", id);
        },
        scannear() {
            // Implementa la lógica para escanear
            console.log("Escanear");
        },
    },
};
</script>
