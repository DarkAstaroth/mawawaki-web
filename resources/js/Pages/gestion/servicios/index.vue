<template>
    <div class="container mx-auto px-4">
        <!-- <div class="grid grid-cols-1 md:grid-cols-10 gap-4 mb-6">
            <div class="md:col-span-3 rounded-lg shadow">
                <div class="flex gap-20 w-full justify-center">
                    <div id="credencial" ref="elementToConvert">
                        <div class="relative w-[300px]">
                            <div class="absolute inset-0">
                                <img
                                    src="/assets/ilustraciones/credencial.png"
                                    alt=""
                                    crossorigin="anonymous"
                                />
                            </div>
                            <div class="relative z-10 h-[450px]">
                                <div class="h-full">
                                    <div class="flex flex-col items-center">
                                        <div class="container_usuario mt-20">
                                            <img
                                                :src="
                                                    '/'.concat(
                                                        this.pacienteActual
                                                            .profile_photo_path
                                                    )
                                                "
                                                alt=""
                                                class="crop"
                                                width="150"
                                                crossorigin="anonymous"
                                            />
                                        </div>

                                        <div
                                            class="flex flex-col items-center font-bold text-xl text-gray-900 mt-5"
                                        >
                                            <span>
                                                {{
                                                    this.pacienteActual.persona
                                                        .nombre
                                                }}
                                            </span>

                                            <a>
                                                {{
                                                    this.pacienteActual.persona
                                                        .paterno
                                                }}
                                                {{
                                                    this.pacienteActual.persona
                                                        .materno
                                                }}
                                            </a>
                                        </div>

                                        <div
                                            v-if="this.pacienteActual.codigo"
                                            class="mt-5"
                                        >
                                            <qrcode-vue
                                                :value="
                                                    this.pacienteActual.codigo
                                                "
                                                :size="90"
                                                level="L"
                                                foreground="#102820"
                                                background="transparent"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        </div> -->

        <!-- Botones de PrimeVue -->
        <div
            class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-6"
        >
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
            <div
                class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4"
            >
                <Button
                    label="Nuevo servicio"
                    icon="pi pi-plus"
                    class="p-button-primary"
                    @click="abrirDialogoNuevoServicio"
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
                                <Badge
                                    :severity="
                                        getBadgeSeverity(
                                            slotProps.data.tipo_servicio
                                        )
                                    "
                                />
                                {{ slotProps.data.tipo_servicio }}
                            </template>
                        </Column>
                        <Column field="precio_sesion" header="Precio Sesion">
                            <template #body="slotProps">
                                {{ slotProps.data.precio_sesion }} Bs
                            </template>
                        </Column>

                        <Column
                            field="sesiones_disponibles"
                            header="Sesiones disponibles"
                        >
                            <template #body="slotProps">
                                {{ slotProps.data.sesiones_disponibles }}
                            </template>
                        </Column>
                        <Column
                            field="sesiones_realizadas"
                            header="Sesiones Realizadas"
                        >
                            <template #body="slotProps">
                                {{ slotProps.data.sesiones_realizadas }}
                            </template>
                        </Column>
                        <Column field="fecha_ingreso" header="Fecha de Ingreso">
                            <template #body="slotProps">
                                {{ slotProps.data.fecha_ingreso }}
                            </template>
                        </Column>
                        <Column
                            field="ultima_actualizacion"
                            header="Ultima actualización"
                        >
                            <template #body="slotProps">
                                <span
                                    v-if="slotProps.data.ultima_actualizacion"
                                >
                                    {{ slotProps.data.ultima_actualizacion }}
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center px-2 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full"
                                >
                                    Sin comenzar
                                </span>
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
                        :options="tiposServicioModal"
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
                    <label for="precio_sesion">Precio por Sesión</label>
                    <InputNumber
                        id="precio_sesion"
                        v-model="nuevoServicio.precio_sesion"
                        mode="currency"
                        currency="BOB"
                        locale="es-BO"
                        :class="{
                            'p-invalid':
                                v$.nuevoServicio.precio_sesion.$invalid &&
                                v$.nuevoServicio.precio_sesion.$dirty,
                        }"
                    />
                    <small
                        class="text-red-600"
                        v-if="v$.nuevoServicio.precio_sesion.$error"
                    >
                        El precio por sesión es requerido
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
import { required, numeric, minValue } from "@vuelidate/validators";
import Button from "primevue/button";
import Card from "primevue/card";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Badge from "primevue/badge";
import Dialog from "primevue/dialog";
import Dropdown from "primevue/dropdown";
import Textarea from "primevue/textarea";
import Calendar from "primevue/calendar";
import InputNumber from "primevue/inputnumber";
import { useDataPacientes } from "@/store/dataPaciente";
import QrcodeVue from "qrcode.vue";

export default {
    components: {
        QrcodeVue,
        Button,
        Card,
        DataTable,
        Column,
        Badge,
        Dialog,
        Dropdown,
        Textarea,
        Calendar,
        InputNumber,
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
            tiposServicioModal: [
                { name: "Equitación", value: "equitacion" },
                { name: "Equinoterapia", value: "equinoterapia" },
            ],
            nuevoServicio: {
                paciente_id: this.id,
                tipo_servicio: null,
                precio_sesion: null,
                observaciones: "",
                fecha_ingreso: new Date(),
            },
        };
    },
    validations() {
        return {
            nuevoServicio: {
                tipo_servicio: { required },
                precio_sesion: { required, numeric, minValue: minValue(0) },
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
                console.log(this.pacienteActual);
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
        getBadgeSeverity(tipoServicio) {
            switch (tipoServicio) {
                case "EQUINOTERAPIA":
                    return "success";
                case "EQUITACION":
                    return "info";
                default:
                    return "primary";
            }
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
                precio_sesion: null,
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
                        precio_sesion: parseFloat(
                            this.nuevoServicio.precio_sesion
                        ),
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
                    await this.cargarPaciente();
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
            this.$router.push({
                name: "servicios.detalle",
                params: { idPaciente: this.id, idServicio: servicio.id },
            });
        },
        confirmarEliminarServicio(event, id) {
            // Implementa la lógica para confirmar la eliminación del servicio
            console.log("Confirmar eliminación del servicio:", id);
        },
    },
};
</script>
