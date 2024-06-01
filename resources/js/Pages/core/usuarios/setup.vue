<template>
    <div>
      <div class="stepper stepper-pills" id="kt_stepper_example_basic">
        <div class="stepper-nav flex-center flex-wrap mb-10">
          <div
            class="stepper-item mx-8 my-4"
            :class="{ current: pasoActivo === 0 }"
            data-kt-stepper-element="nav"
          >
            <div class="stepper-wrapper d-flex align-items-center">
              <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">1</span>
              </div>

              <div class="stepper-label">
                <h3 class="stepper-title">Paso 1</h3>

                <div class="stepper-desc">Tipo de cuenta</div>
              </div>
            </div>

            <div class="stepper-line h-40px"></div>
          </div>

          <div
            class="stepper-item mx-8 my-4"
            :class="{ current: pasoActivo === 1 }"
            data-kt-stepper-element="nav"
          >
            <div class="stepper-wrapper d-flex align-items-center">
              <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">2</span>
              </div>

              <div class="stepper-label">
                <h3 class="stepper-title">Paso 2</h3>

                <div class="stepper-desc">Datos relevantes</div>
              </div>
            </div>

            <div class="stepper-line h-40px"></div>
          </div>
        </div>

        <div v-if="pasoActivo === 0">
          <div class="d-flex flex-column gap-3">
            <input
              type="radio"
              class="btn-check"
              v-model="opcionSeleccionada"
              value="personal"
              id="opcionPersonal"
            />
            <label
              class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-5"
              for="opcionPersonal"
            >
              <i class="ki-duotone ki-user-square fs-4x me-4">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
              </i>

              <span class="d-block fw-semibold text-start">
                <span class="text-gray-900 fw-bold d-block fs-3">Personal</span>
                <span class="text-muted fw-semibold fs-6"
                  >Administradores, Pasantes, Voluntarios, etc
                </span>
              </span>
            </label>

            <input
              type="radio"
              class="btn-check"
              v-model="opcionSeleccionada"
              value="cliente"
              id="opcionCliente"
            />
            <label
              class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center"
              for="opcionCliente"
            >
              <i class="ki-duotone ki-heart-circle fs-4x me-4">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>

              <span class="d-block fw-semibold text-start">
                <span class="text-gray-900 fw-bold d-block fs-3">Cliente</span>
                <span class="text-muted fw-semibold fs-6"
                  >Pacientes del centro de terapias</span
                >
              </span>
            </label>
          </div>

          <button class="btn btn-primary mt-5" @click="irAlSiguientePaso">
            <span class="indicator-label">Siguiente</span>
          </button>
        </div>

        <div
          v-if="opcionSeleccionada && pasoActivo === 1"
          class="mb-10 d-grid mt-5"
        >
          <form @submit.prevent="enviarFormulario">
            <div v-if="opcionSeleccionada === 'personal'">
              <input type="hidden" name="tipo" value="personal" />
              <div class="d-flex flex-column gap-2 mb-5">
                <label for="universidad">Universidad</label>
                <InputText
                  id="universidad"
                  v-model="datosPersonal.universidad"
                  aria-describedby="universidad"
                  placeholder="Solo Sigla. Ejemplo (UMSA)"
                  @input="validarCampos"
                />
                <small
                  class="text-danger"
                  v-if="enviado || v$?.datosPersonal.universidad.$error"
                  >La Universidad es requerida</small
                >
              </div>

              <div class="d-flex flex-column gap-2 mb-5">
                <label for="facultad">Facultad</label>
                <InputText
                  id="facultad"
                  v-model="datosPersonal.facultad"
                  aria-describedby="facultad"
                  placeholder="Solo Sigla. Ejemplo (FHCE)"
                  @input="validarCampos"
                />
                <small
                  class="text-danger"
                  v-if="enviado || v$?.datosPersonal.facultad.$error"
                  >La Facultad es requerida</small
                >
              </div>

              <div class="d-flex flex-column gap-2 mb-5">
                <label for="facultad">Carrera</label>
                <InputText
                  id="facultad"
                  v-model="datosPersonal.carrera"
                  aria-describedby="facultad"
                  placeholder="Ejemplo Psicología"
                  @input="validarCampos"
                />
                <small
                  class="text-danger"
                  v-if="enviado || v$?.datosPersonal.carrera.$error"
                  >La Carrera es requerida</small
                >
              </div>
            </div>
            <div v-else-if="opcionSeleccionada === 'cliente'">
              <input type="hidden" name="tipo" value="cliente" />
              <div class="d-flex flex-column gap-2">
                <label for="ocupacion">Ocupación</label>
                <InputText
                  id="ocupacion"
                  v-model="ocupacion"
                  aria-describedby="ayudaOcupacion"
                  placeholder="Ingrese su ocupación"
                />
              </div>
            </div>

            <button class="btn btn-secondary mt-5" @click="irAlPasoAnterior">
              <span class="indicator-label">Anterior</span>
            </button>

            <button type="submit" class="btn btn-primary mt-5 ms-5">
              <span class="indicator-label">Enviar solicitud</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </template>

  <script>
  import axios from "axios";
  import InputText from "primevue/inputtext";
  import Dropdown from "primevue/dropdown";
  import { useVuelidate } from "@vuelidate/core";
  import { required } from "@vuelidate/validators";

  export default {
    components: { InputText, Dropdown },
    props: ["usuario"],
    setup() {
      return { v$: useVuelidate() };
    },
    data() {
      return {
        datosPersonal: { universidad: "", facultad: "", carrera: "" },
        datosCliente: { ocupacion: "" },
        usuario: this.usuario,
        paisSeleccionado: null,
        paises: [
          { label: "País 1", value: "pais1" },
          { label: "País 2", value: "pais2" },
        ],
        opcionSeleccionada: "personal",
        inputPersonal: "",
        ocupacion: "",
        pasoActivo: 0,
        tipoCuenta: this.usuario.gauth_type,
        enviado: false,
      };
    },
    validations() {
      if (this.opcionSeleccionada === "personal") {
        return {
          datosPersonal: {
            universidad: { required },
            facultad: { required },
            carrera: { required },
          },
        };
      }
      return {};
    },
    mounted() {},
    methods: {
      irAlSiguientePaso() {
        if (this.opcionSeleccionada) {
          this.irAlPaso(1);
        }
      },
      irAlPasoAnterior() {
        this.irAlPaso(0);
      },
      enviarFormulario() {
        this.enviado = true;
        this.v$.$touch();
        if (this.v$.$invalid) {
          return;
        }
        axios
          .post(`/api/invitado/enviar/solicitud`, {
            tipo: this.opcionSeleccionada,
            datos:
              this.opcionSeleccionada === "cliente"
                ? this.datosCliente
                : this.datosPersonal,
            usuario_id: this.usuario.id,
          })
          .then((response) => {
            Swal.fire({
              title: "Éxito",
              text: "Solicitud enviada con éxito",
              icon: "success",
              buttonsStyling: false,
              confirmButtonText: "Aceptar",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            }).then(() => {
              window.location.href = route("verificar.cuenta");
            });
          })
          .catch((error) => {});
      },
      irAlPaso(indice) {
        this.pasoActivo = indice;
      },
      validarCampos() {
        this.enviado = false;
        this.v$.$touch();
      },
    },
  };
  </script>
