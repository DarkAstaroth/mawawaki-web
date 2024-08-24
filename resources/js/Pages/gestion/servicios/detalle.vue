<template>
    <div class="container mx-auto px-4">
        <!-- Tabs para Sesiones y Pagos -->
        <TabView>
            <TabPanel header="Sesiones">
                <Button
                    label="Programar"
                    icon="pi pi-calendar-plus"
                    @click="abrirDialogoProgramarSesion"
                    :disabled="!hayPagosNoConsumidos"
                    class="mb-4"
                />
                <DataTable
                    :value="sesiones"
                    :paginator="true"
                    :rows="10"
                    responsiveLayout="scroll"
                >
                    <Column field="fecha_sesion" header="Fecha de Sesión">
                        <template #body="slotProps">
                            {{
                                slotProps.data.fecha_sesion
                                    ? formatearFecha(
                                          slotProps.data.fecha_sesion
                                      )
                                    : "Sin asignar"
                            }}
                        </template>
                    </Column>
                    <Column field="responsable" header="Responsable">
                        <template #body="slotProps">
                            {{
                                slotProps.data.responsable?.name ||
                                "Sin asignar"
                            }}
                        </template>
                    </Column>
                    <Column field="apoyo_id" header="Apoyo">
                        <template #body="slotProps">
                            {{
                                slotProps.data.asistente?.name || "Sin asignar"
                            }}
                        </template>
                    </Column>
                    <Column field="caballo_id" header="Caballo">
                        <template #body="slotProps">
                            {{ slotProps.data.caballo?.name || "Sin asignar" }}
                        </template>
                    </Column>
                    <Column field="estado_sesion" header="Estado">
                        <template #body="slotProps">
                            <Badge
                                :value="
                                    slotProps.data.estado_sesion ||
                                    'Sin asignar'
                                "
                                :severity="
                                    obtenerSeveridad(
                                        slotProps.data.estado_sesion
                                    )
                                "
                            />
                        </template>
                    </Column>
                    <Column header="Acciones">
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-pencil"
                                @click="
                                    abrirDialogoEditarSesion(slotProps.data)
                                "
                                class="p-button-text"
                            />
                        </template>
                    </Column>
                </DataTable>
            </TabPanel>

            <TabPanel header="Pagos">
                <Button
                    label="Nuevo Pago"
                    icon="pi pi-plus"
                    @click="abrirDialogoNuevoPago"
                    class="mb-4"
                />

                <DataTable
                    :value="pagos"
                    :paginator="true"
                    :rows="10"
                    responsiveLayout="scroll"
                >
                    <Column field="fecha_pago" header="Fecha de Pago">
                        <template #body="slotProps">
                            {{ formatearFecha(slotProps.data.fecha_pago) }}
                        </template>
                    </Column>
                    <Column field="monto" header="Monto">
                        <template #body="slotProps">
                            {{ slotProps.data.monto }} Bs
                        </template>
                    </Column>
                    <Column field="tipo_pago" header="Tipo de Pago" />
                    <Column field="estado" header="Estado">
                        <template #body="slotProps">
                            <Badge
                                :value="slotProps.data.estado"
                                :severity="
                                    obtenerSeveridadPago(slotProps.data.estado)
                                "
                            />
                        </template>
                    </Column>
                    <Column field="verificado" header="Verificado">
                        <template #body="slotProps">
                            <template v-if="slotProps.data.verificado">
                                <i
                                    class="pi pi-check-circle text-green-500"
                                ></i>
                            </template>
                            <template v-else>
                                <Badge
                                    value="No verificado"
                                    severity="warning"
                                />
                            </template>
                        </template>
                    </Column>
                    <Column header="Comprobante">
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-image"
                                @click="verComprobante(slotProps.data)"
                                v-if="slotProps.data.comprobante"
                                class="p-button-text"
                            />
                        </template>
                    </Column>
                    <Column header="Acciones">
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-check"
                                @click="
                                    verificarPago($event, slotProps.data.id)
                                "
                                class="p-button-text"
                            />
                            <Button
                                icon="pi pi-trash"
                                @click="eliminarPago($event, slotProps.data.id)"
                                class="p-button-text p-button-danger"
                            />
                        </template>
                    </Column>
                </DataTable>
            </TabPanel>
        </TabView>

        <Dialog
            v-model:visible="dialogoEditarSesionVisible"
            header="Editar Sesión"
            :style="{ width: '50vw' }"
            :modal="true"
        >
            <form @submit.prevent="guardarEdicionSesion" class="p-fluid">
                <div class="field">
                    <label for="fecha_sesion">Fecha de Sesión</label>
                    <Calendar
                        id="fecha_sesion"
                        v-model="sesionEditada.fecha_sesion"
                        dateFormat="dd/mm/yy"
                    />
                </div>
                <div class="field">
                    <label for="responsable">Responsable</label>
                    <Dropdown
                        class="w-100"
                        v-model="responsableSeleccionados"
                        :options="usuariosFiltro"
                        filter
                        optionLabel="name"
                        placeholder="Selecciona Usuarios"
                    />
                </div>
                <div class="field">
                    <label for="apoyo_id">Apoyo</label>
                    <Dropdown
                        class="w-100"
                        v-model="asistenteSeleccionados"
                        :options="usuariosFiltro"
                        filter
                        optionLabel="name"
                        placeholder="Selecciona Usuarios"
                    />
                </div>
                <div class="field">
                    <label for="caballo_id">Caballo</label>
                    <Dropdown
                        id="caballo_id"
                        v-model="sesionEditada.caballo_id"
                        :options="caballos"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Seleccione un caballo"
                    />
                </div>
                <div class="field">
                    <label for="estado_sesion">Estado</label>
                    <Dropdown
                        id="estado_sesion"
                        v-model="sesionEditada.estado_sesion"
                        :options="estadosSesion"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Seleccione un estado"
                    />
                </div>
                <div class="field">
                    <label for="observaciones">Observaciones</label>
                    <Textarea
                        id="observaciones"
                        v-model="sesionEditada.observaciones"
                        rows="3"
                    />
                </div>
            </form>
            <template #footer>
                <Button
                    label="Cancelar"
                    icon="pi pi-times"
                    @click="cerrarDialogoEditarSesion"
                    class="p-button-text"
                />
                <Button
                    label="Guardar"
                    icon="pi pi-check"
                    @click="guardarEdicionSesion"
                    autofocus
                />
            </template>
        </Dialog>

        <Dialog
            v-model:visible="dialogoSesionVisible"
            header="Nueva Sesión"
            :style="{ width: '50vw' }"
            :modal="true"
        >
            <form @submit.prevent="programarSesiones" class="p-fluid space-y-3">
                <div class="field mb-4">
                    <label for="pago" class="block mb-2 font-bold"
                        >Pago a aplicar</label
                    >
                    <Dropdown
                        id="pago"
                        v-model="pagoSeleccionado"
                        :options="pagosNoConsumidos"
                        optionLabel="label"
                        placeholder="Seleccione un pago"
                        @change="calcularSesionesDisponibles"
                        class="w-full"
                    >
                        <template #option="slotProps">
                            {{ slotProps.option.label }} - Monto:
                            {{ slotProps.option.monto }} Bs
                        </template>
                    </Dropdown>
                </div>

                <!-- Resumen del pago seleccionado -->
                <div
                    v-if="pagoSeleccionado"
                    class="bg-gray-100 p-4 rounded-lg mb-4"
                >
                    <h3 class="font-bold mb-2">Resumen del Pago</h3>
                    <p>
                        <strong>Fecha:</strong>
                        {{ formatearFecha(pagoSeleccionado.fecha_pago) }}
                    </p>
                    <p>
                        <strong>Monto:</strong> {{ pagoSeleccionado.monto }} BOB
                    </p>
                    <p>
                        <strong>Tipo de Pago:</strong>
                        {{ pagoSeleccionado.tipo_pago }}
                    </p>
                    <p>
                        <strong>ID Transacción:</strong>
                        {{ pagoSeleccionado.id_transaccion }}
                    </p>
                </div>

                <div class="field mb-4">
                    <label class="block mb-2 font-bold"
                        >Sesiones Disponibles</label
                    >
                    <div class="p-2 bg-gray-100 rounded">
                        {{ sesionesDisponibles }}
                    </div>
                </div>

                <Button
                    label="Programar"
                    icon="pi pi-calendar-plus"
                    type="submit"
                    :disabled="!pagoSeleccionado || sesionesDisponibles === 0"
                    class="w-full"
                />
            </form>
        </Dialog>
        <!-- Diálogo para nuevo pago -->
        <Dialog
            v-model:visible="dialogoPagoVisible"
            header="Nuevo Pago"
            :style="{ width: '90vw', maxWidth: '500px' }"
            :modal="true"
            :contentStyle="{ maxHeight: '80vh', overflow: 'auto' }"
        >
            <form @submit.prevent="guardarNuevoPago" class="p-fluid space-y-6">
                <div class="field">
                    <label for="fecha_pago" class="font-semibold mb-2 block"
                        >Fecha de Pago</label
                    >
                    <Calendar
                        id="fecha_pago"
                        v-model="nuevoPago.fecha_pago"
                        dateFormat="dd/mm/yy"
                        class="w-full"
                        :class="{
                            'p-invalid':
                                v$.nuevoPago.fecha_pago.$invalid &&
                                v$.nuevoPago.fecha_pago.$dirty,
                        }"
                    />
                    <small
                        class="p-error"
                        v-if="
                            v$.nuevoPago.fecha_pago.$invalid &&
                            v$.nuevoPago.fecha_pago.$dirty
                        "
                    >
                        La fecha de pago es requerida
                    </small>
                </div>

                <div class="field">
                    <label for="monto" class="font-semibold mb-2 block"
                        >Monto</label
                    >
                    <InputNumber
                        id="monto"
                        v-model="nuevoPago.monto"
                        mode="currency"
                        currency="BOB"
                        locale="es-BO"
                        class="w-full"
                        :class="{
                            'p-invalid':
                                v$.nuevoPago.monto.$invalid &&
                                v$.nuevoPago.monto.$dirty,
                        }"
                    />
                    <small
                        class="p-error"
                        v-if="
                            v$.nuevoPago.monto.$invalid &&
                            v$.nuevoPago.monto.$dirty
                        "
                    >
                        El monto es requerido y debe ser mayor a 0
                    </small>
                </div>

                <div class="field">
                    <label for="tipo_pago" class="font-semibold mb-2 block"
                        >Tipo de Pago</label
                    >
                    <Dropdown
                        id="tipo_pago"
                        v-model="nuevoPago.tipo_pago"
                        :options="tiposPago"
                        placeholder="Seleccione un tipo de pago"
                        :filter="true"
                        class="w-full"
                        :class="{
                            'p-invalid':
                                v$.nuevoPago.tipo_pago.$invalid &&
                                v$.nuevoPago.tipo_pago.$dirty,
                        }"
                    />
                    <small
                        class="p-error"
                        v-if="
                            v$.nuevoPago.tipo_pago.$invalid &&
                            v$.nuevoPago.tipo_pago.$dirty
                        "
                    >
                        El tipo de pago es requerido
                    </small>
                </div>

                <div class="field">
                    <label for="id_transaccion" class="font-semibold mb-2 block"
                        >ID de Transacción</label
                    >
                    <InputText
                        id="id_transaccion"
                        v-model="nuevoPago.id_transaccion"
                        class="w-full"
                        :class="{
                            'p-invalid':
                                v$.nuevoPago.id_transaccion.$invalid &&
                                v$.nuevoPago.id_transaccion.$dirty,
                        }"
                    />
                    <small
                        class="p-error"
                        v-if="
                            v$.nuevoPago.id_transaccion.$invalid &&
                            v$.nuevoPago.id_transaccion.$dirty
                        "
                    >
                        El ID de transacción es requerido
                    </small>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="field">
                        <label for="nit" class="font-semibold mb-2 block"
                            >NIT</label
                        >
                        <InputText
                            id="nit"
                            v-model="nuevoPago.nit"
                            class="w-full"
                            :class="{
                                'p-invalid':
                                    v$.nuevoPago.nit.$invalid &&
                                    v$.nuevoPago.nit.$dirty,
                            }"
                        />
                        <small
                            class="p-error"
                            v-if="
                                v$.nuevoPago.nit.$invalid &&
                                v$.nuevoPago.nit.$dirty
                            "
                        >
                            El NIT es requerido
                        </small>
                    </div>
                    <div class="field">
                        <label
                            for="razon_social"
                            class="font-semibold mb-2 block"
                            >Razón Social</label
                        >
                        <InputText
                            id="razon_social"
                            v-model="nuevoPago.razon_social"
                            class="w-full"
                            :class="{
                                'p-invalid':
                                    v$.nuevoPago.razon_social.$invalid &&
                                    v$.nuevoPago.razon_social.$dirty,
                            }"
                        />
                        <small
                            class="p-error"
                            v-if="
                                v$.nuevoPago.razon_social.$invalid &&
                                v$.nuevoPago.razon_social.$dirty
                            "
                        >
                            La razón social es requerida
                        </small>
                    </div>
                </div>
                <!--
                <div class="field-checkbox">
                    <Checkbox
                        id="facturado"
                        v-model="nuevoPago.facturado"
                        :binary="true"
                    />
                    <label for="facturado" class="ml-2">Facturado</label>
                </div> -->

                <div class="field">
                    <label for="estado_pago" class="font-semibold mb-2 block"
                        >Estado del Pago</label
                    >
                    <Dropdown
                        id="estado_pago"
                        v-model="nuevoPago.estado"
                        :options="estadosPago"
                        placeholder="Seleccione un estado"
                        :filter="true"
                        class="w-full"
                        :class="{
                            'p-invalid':
                                v$.nuevoPago.estado.$invalid &&
                                v$.nuevoPago.estado.$dirty,
                        }"
                    />
                    <small
                        class="p-error"
                        v-if="
                            v$.nuevoPago.estado.$invalid &&
                            v$.nuevoPago.estado.$dirty
                        "
                    >
                        El estado del pago es requerido
                    </small>
                </div>

                <div class="field">
                    <label for="comprobante" class="font-semibold mb-2 block"
                        >Comprobante</label
                    >
                    <div class="flex items-center">
                        <input
                            type="file"
                            class="form-control flex-1"
                            id="inputArchivo"
                            @change="
                                manejarCambioArchivo($event, 'comprobante')
                            "
                            accept="image/*"
                        />
                    </div>
                </div>

                <div class="field">
                    <label for="notas" class="font-semibold mb-2 block"
                        >Notas</label
                    >
                    <Textarea
                        id="notas"
                        v-model="nuevoPago.notas"
                        rows="3"
                        class="w-full"
                    />
                </div>
            </form>
            <template #footer>
                <Button
                    label="Cancelar"
                    icon="pi pi-times"
                    @click="cerrarDialogoPago"
                    class="p-button-text"
                />
                <Button
                    label="Guardar"
                    icon="pi pi-check"
                    @click="guardarNuevoPago"
                    autofocus
                />
            </template>
        </Dialog>

        <div class="mt-6">
            <Button label="Volver" icon="pi pi-arrow-left" @click="volver" />
        </div>
    </div>
    <ConfirmPopup></ConfirmPopup>
</template>

<script>
import { ref, reactive, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useVuelidate } from "@vuelidate/core";
import { required, numeric, minValue } from "@vuelidate/validators";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Badge from "primevue/badge";
import Dialog from "primevue/dialog";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import Calendar from "primevue/calendar";
import Dropdown from "primevue/dropdown";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import FileUpload from "primevue/fileupload";
import { useDataPacientes } from "@/store/dataPaciente";
import.meta.env.VITE_APP_BASE_URL;
import { useConfirm } from "primevue/useconfirm";
import { useDataEventos } from "@/store/dataEventos";

export default {
    name: "ServicioDetalle",
    components: {
        Button,
        DataTable,
        Column,
        Badge,
        Dialog,
        TabView,
        TabPanel,
        Calendar,
        Dropdown,
        Textarea,
        InputNumber,
        FileUpload,
    },
    props: {
        idPaciente: { type: String, required: true },
        idServicio: { type: String, required: true },
    },
    computed: {
        hayPagosNoConsumidos() {
            return this.pagos.some((pago) => !pago.consumido);
        },
        pagosNoConsumidos() {
            return this.pagos
                .filter((pago) => !pago.consumido)
                .map((pago) => ({
                    ...pago,
                    label: `${this.formatearFecha(pago.fecha_pago)} - ID: ${
                        pago.id_transaccion
                    }`,
                }));
        },
    },
    setup() {
        const v$ = useVuelidate();
        const store = useDataPacientes();
        const confirm = useConfirm();
        const storeEvento = useDataEventos();
        const responsableSeleccionados = ref();
        const asistenteSeleccionados = ref();
        const usuariosFiltro = ref();

        const verificarPago = (event, idPago) => {
            confirm.require({
                target: event.currentTarget,
                message: "¿Quieres verificar el Pago?",
                icon: "pi pi-info-circle",
                rejectClass:
                    "p-button-secondary p-button-outlined p-button-sm me-5",
                acceptClass: "p-button-success p-button-sm",
                rejectLabel: "Cancelar",
                acceptLabel: "Verificar",
                accept: () => {
                    console.log(idPago);
                },
            });
        };

        const eliminarPago = (event, idPago) => {
            confirm.require({
                target: event.currentTarget,
                message: "¿Quieres eliminar el pago?",
                icon: "pi pi-info-circle",
                rejectClass:
                    "p-button-secondary p-button-outlined p-button-sm me-5",
                acceptClass: "p-button-danger p-button-sm",
                rejectLabel: "Cancelar",
                acceptLabel: "Borrar",
                accept: () => {
                    console.log(idPago);
                },
            });
        };

        return {
            v$,
            store,
            storeEvento,
            verificarPago,
            eliminarPago,
            responsableSeleccionados,
            asistenteSeleccionados,
            usuariosFiltro,
        };
    },
    data() {
        return {
            pacienteActual: null,
            servicio: null,
            sesiones: [],
            pagos: [],
            dialogoSesionVisible: false,
            dialogoPagoVisible: false,
            nuevaSesion: {
                fecha_sesion: null,
                responsable: null,
                apoyo: null,
                caballo: null,
                estado_sesion: null,
                observaciones: "",
            },
            nuevoPago: {
                fecha_pago: null,
                monto: 0,
                tipo_pago: null,
                id_transaccion: "",
                razon_social: "",
                nit: "",
                facturado: false,
                estado: null,
                comprobante: null,
                notas: "",
            },
            personal: [
                { id: 1, nombre: "Juan Pérez" },
                { id: 2, nombre: "María García" },
            ],
            caballos: [
                { id: 1, nombre: "Tornado" },
                { id: 2, nombre: "Relámpago" },
            ],
            estadosSesion: ["Programada", "Completada", "Cancelada"],
            tiposPago: ["Efectivo", "Tarjeta", "Transferencia"],
            estadosPago: ["Pendiente", "Completado", "Cancelado"],
            sesionesDisponibles: 0,
            pagoSeleccionado: null,
            dialogoEditarSesionVisible: false,
            sesionEditada: {},
            caballos: [],
            estadosSesion: [
                { label: "En Progreso", value: "En Progreso" },
                { label: "Completado", value: "Completado" },
                { label: "Cancelado", value: "Cancelado" },
                { label: "Pendiente", value: "Pendiente" },
                { label: "Programado", value: "Programado" },
                { label: "Finalizado", value: "Finalizado" },
            ],
        };
    },
    validations() {
        return {
            nuevoPago: {
                fecha_pago: { required },
                monto: { required, numeric, minValue: minValue(0) },
                tipo_pago: { required },
                id_transaccion: { required },
                razon_social: { required },
                nit: { required },
                estado: { required },
            },
        };
    },
    methods: {
        async cargarDatos() {
            try {
                const store = useDataPacientes();
                this.pacienteActual = await store.cargarPaciente(
                    this.idPaciente
                );
                this.servicio = this.pacienteActual.servicios.find(
                    (s) => s.id === this.idServicio
                );
                if (!this.servicio) {
                    console.error("Servicio no encontrado");
                } else {
                    await this.cargarSesiones();
                    await this.cargarPagos();
                }
            } catch (error) {
                console.error("Error al cargar los datos:", error);
            }
        },
        async cargarSesiones() {
            try {
                const response = await this.store.obtenerSesionesServicio(
                    this.idServicio
                );
                this.sesiones = response;
            } catch (error) {
                Swal.fire({
                    title: "Error",
                    text: "No se pudieron cargar las sesiones. Por favor, intente de nuevo.",
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
        },
        async cargarPagos() {
            try {
                const response = await this.store.obtenerPagosServicio(
                    this.idServicio
                );
                this.pagos = response;
            } catch (error) {
                console.error("Error al cargar los pagos:", error);
            }
        },
        async cargarCaballos() {
            try {
                const response = await this.store.obtenerCaballos();
                this.caballos = response.map((caballo) => ({
                    value: caballo.id,
                    label: `${caballo.nombre} (${
                        caballo.apodo || "Sin apodo"
                    })`,
                }));
            } catch (error) {
                console.error("Error al cargar los caballos:", error);
            }
        },
        abrirDialogoNuevaSesion() {
            this.dialogoSesionVisible = true;
        },
        calcularSesionesDisponibles() {
            if (this.pagoSeleccionado && this.servicio.precio_sesion) {
                this.sesionesDisponibles = Math.floor(
                    this.pagoSeleccionado.monto / this.servicio.precio_sesion
                );
            } else {
                this.sesionesDisponibles = 0;
            }
            console.log(
                "Monto del pago:",
                this.pagoSeleccionado ? this.pagoSeleccionado.monto : 0
            );
            console.log("Precio por sesión:", this.servicio.precio_sesion);
            console.log("Sesiones disponibles:", this.sesionesDisponibles);
        },
        abrirDialogoNuevoPago() {
            this.dialogoPagoVisible = true;
        },
        cerrarDialogoSesion() {
            this.dialogoSesionVisible = false;
            Object.assign(this.nuevaSesion, {
                fecha_sesion: null,
                responsable: null,
                apoyo: null,
                caballo: null,
                estado_sesion: null,
                observaciones: "",
            });
        },
        cerrarDialogoPago() {
            this.dialogoPagoVisible = false;
            Object.assign(this.nuevoPago, {
                fecha_pago: null,
                monto: 0,
                tipo_pago: null,
                id_transaccion: "",
                razon_social: "",
                nit: "",
                facturado: false,
                estado: null,
                comprobante: null,
                notas: "",
            });
        },
        formatearFecha(fecha) {
            return new Date(fecha).toLocaleDateString();
        },
        obtenerSeveridad(estado) {
            switch (estado) {
                case "Completada":
                    return "success";
                case "Pendiente":
                    return "warning";
                case "Cancelada":
                    return "danger";
                default:
                    return "info";
            }
        },
        obtenerSeveridadPago(estado) {
            switch (estado) {
                case "completado":
                    return "success";
                case "pendiente":
                    return "warning";
                case "cancelado":
                    return "danger";
                default:
                    return "info";
            }
        },
        async guardarNuevaSesion() {
            try {
                // Implementar lógica para guardar la nueva sesión
                this.cerrarDialogoSesion();
            } catch (error) {
                console.error("Error al guardar la sesión:", error);
            }
        },
        async guardarNuevoPago() {
            console.log("enrtra");
            this.v$.$touch();
            if (this.v$.$invalid) {
                return;
            }

            try {
                await this.store.registrarPago(this.idServicio, this.nuevoPago);

                Swal.fire({
                    title: "¡Éxito!",
                    text: "El pago ha sido registrado correctamente.",
                    icon: "success",
                    confirmButtonText: "Ok",
                });

                this.cerrarDialogoPago();
                await this.cargarPagos(); // Recargar los pagos
            } catch (error) {
                console.error("Error al guardar el pago:", error);

                Swal.fire({
                    title: "Error",
                    text: "Hubo un problema al registrar el pago. Por favor, inténtelo de nuevo.",
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
        },
        editarSesion(sesion) {
            console.log("Editar sesión:", sesion);
        },
        eliminarSesion(sesion) {
            console.log("Eliminar sesión:", sesion);
        },
        editarPago(pago) {
            console.log("Editar pago:", pago);
        },
        eliminarPago(pago) {
            console.log("Eliminar pago:", pago);
        },
        verComprobante(pago) {
            if (pago.comprobante) {
                const url = `${import.meta.env.VITE_APP_BASE_URL}/${
                    pago.comprobante
                }`;
                window.open(url, "_blank");
            } else {
                console.error("No hay comprobante disponible para este pago");
                // Opcionalmente, puedes mostrar un mensaje al usuario
                Swal.fire({
                    title: "Error",
                    text: "No hay comprobante disponible para este pago",
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
        },
        volver() {
            this.$router.back();
        },
        manejarCambioArchivo(event, tipo) {
            const archivo = event.target.files[0];
            console.log(archivo);
            if (archivo) {
                // Check if the file is an image
                if (!archivo.type.startsWith("image/")) {
                    // Show an error message if the file is not an image
                    Swal.fire({
                        title: "Error",
                        text: "Por favor, seleccione un archivo de imagen válido.",
                        icon: "error",
                        confirmButtonText: "Ok",
                    });
                    return;
                }

                // Check file size (e.g., max 5MB)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (archivo.size > maxSize) {
                    Swal.fire({
                        title: "Error",
                        text: "El archivo es demasiado grande. Por favor, seleccione un archivo menor a 5MB.",
                        icon: "error",
                        confirmButtonText: "Ok",
                    });
                    return;
                }

                // If all checks pass, assign the file to the appropriate property
                if (tipo === "comprobante") {
                    this.nuevoPago.comprobante = archivo;
                } else {
                    // Handle other file types if necessary
                }
                console.log(this.nuevoPago);
                // Optionally, you can also create a preview of the image
                this.createImagePreview(archivo);
            }
        },
        createImagePreview(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        abrirDialogoProgramarSesion() {
            if (this.hayPagosNoConsumidos) {
                this.dialogoSesionVisible = true;
            } else {
                // Opcional: mostrar un mensaje explicando por qué está deshabilitado
                Swal.fire({
                    title: "No se puede programar",
                    text: "No hay pagos disponibles para programar una nueva sesión.",
                    icon: "warning",
                    confirmButtonText: "Ok",
                });
            }
        },
        async programarSesiones() {
            if (!this.pagoSeleccionado || this.sesionesDisponibles === 0) {
                return;
            }

            try {
                await this.store.programarSesiones({
                    servicioId: this.idServicio,
                    pagoId: this.pagoSeleccionado.id,
                    sesionesDisponibles: this.sesionesDisponibles,
                });

                Swal.fire({
                    title: "¡Éxito!",
                    text: `Se han programado ${this.sesionesDisponibles} sesiones correctamente.`,
                    icon: "success",
                    confirmButtonText: "Ok",
                });

                this.cerrarDialogoSesion();
                await this.cargarSesiones();
                await this.cargarPagos();
            } catch (error) {
                console.error("Error al programar las sesiones:", error);
                Swal.fire({
                    title: "Error",
                    text: "Hubo un problema al programar las sesiones. Por favor, inténtelo de nuevo.",
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
        },
        abrirDialogoEditarSesion(sesion) {
            this.sesionEditada = { ...sesion };
            this.dialogoEditarSesionVisible = true;
        },
        cerrarDialogoEditarSesion() {
            this.dialogoEditarSesionVisible = false;
            this.sesionEditada = {};
        },
        async guardarEdicionSesion() {
            try {
                // Aquí debes implementar la lógica para guardar los cambios en el backend
                await this.store.actualizarSesion(this.sesionEditada.id, {
                    ...this.sesionEditada,
                    usuario: this.responsableSeleccionados,
                    apoyo: this.asistenteSeleccionados,
                });

                await this.cargarSesiones(); // Recargar las sesiones para reflejar los cambios
                Swal.fire({
                    title: "¡Éxito!",
                    text: "La sesión ha sido actualizada correctamente.",
                    icon: "success",
                    confirmButtonText: "Ok",
                });
            } catch (error) {
                Swal.fire({
                    title: "Error",
                    text: "Hubo un problema al actualizar la sesión. Por favor, inténtelo de nuevo.",
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
            this.cerrarDialogoEditarSesion();
        },
    },
    mounted() {
        this.cargarDatos();
        this.cargarCaballos();
        this.storeEvento.obtenerUsuarios().then(() => {
            this.usuariosFiltro = this.storeEvento.usuarios
                .filter((usuario) => usuario.personal && usuario.personal.id)
                .map((usuario) => {
                    let nombreCompleto = "";
                    if (usuario.persona.nombre !== "") {
                        nombreCompleto =
                            `${usuario.persona.nombre} ${usuario.persona.paterno} ${usuario.persona.materno}`.trim();
                    }
                    return {
                        name: nombreCompleto,
                        id: usuario.personal.id,
                    };
                });
            console.log(this.usuariosFiltro);
        });
    },
};
</script>
