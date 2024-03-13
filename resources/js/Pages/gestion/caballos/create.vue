<template>
  <form
    class="input-feild"
    v-on:submit.prevent="modo === 'editar' ? editarCaballo() : agregarCaballo()"
  >
    <div class="card card-bordered mb-5">
      <div class="card-header">
        <h3 class="card-title">Nuevo registro de caballo</h3>
        <div class="div card-toolbar">
          <button type="submit" class="btn btn-sm btn-success">
            <i class="text-white far fa-plus"></i>
            Guardar cambios
          </button>
        </div>
      </div>

      <div class="card-body">
        <div class="d-flex flex-column flex-md-row gap-12">
          <div>
            <div
              class="image-input image-input-outline"
              data-kt-image-input="true"
              style="background-image: url(/assets/media/svg/avatars/blank.png)"
            >
              <div
                class="image-input-wrapper w-125px h-125px"
                style="background-image: url(/assets/media/avatars/blank.png)"
              ></div>

              <label
                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="change"
                data-bs-toggle="tooltip"
                data-bs-dismiss="click"
                title="Change avatar"
              >
                <i class="ki-duotone ki-pencil fs-6"
                  ><span class="path1"></span><span class="path2"></span
                ></i>

                <input
                  type="file"
                  name="foto_caballo"
                  accept=".png, .jpg, .jpeg"
                />
                <input type="hidden" name="foto_caballo" />
              </label>

              <span
                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="cancel"
                data-bs-toggle="tooltip"
                data-bs-dismiss="click"
                title="Cancel avatar"
              >
                <i class="ki-outline ki-cross fs-3"></i>
              </span>

              <span
                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="remove"
                data-bs-toggle="tooltip"
                data-bs-dismiss="click"
                title="Remove avatar"
              >
                <i class="ki-outline ki-cross fs-3"></i>
              </span>
            </div>
          </div>

          <div class="w-100">
            <div class="form-group row">
              <div class="col-12 col-md-6 mb-5">
                <input
                  type="text"
                  v-model="nombre"
                  id=""
                  class="form-control"
                  placeholder="Nombre del caballo"
                  aria-describedby="helpId"
                />
                <div
                  v-if="v$?.nombre.$error"
                  class="m-2 fv-plugins-message-container invalid-feedback"
                >
                  <div data-field="text_input" data-validator="notEmpty">
                    El nombre del caballo es requerido
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6 mb-5">
                <input
                  type="text"
                  v-model="apodo"
                  id=""
                  class="form-control"
                  placeholder="Apodo del caballo"
                  aria-describedby="helpId"
                />
              </div>
            </div>

            <div class="form-group row">
              <div class="col-12 col-md-3 mb-5">
                <input
                  type="text"
                  v-model="raza"
                  id=""
                  class="form-control"
                  placeholder="Raza del caballo"
                  aria-describedby="helpId"
                />
                <div
                  v-if="v$?.raza.$error"
                  class="m-2 fv-plugins-message-container invalid-feedback"
                >
                  <div data-field="text_input" data-validator="notEmpty">
                    La raza del caballo es requerida
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-3 mb-5">
                <input
                  type="text"
                  v-model="color"
                  id=""
                  class="form-control"
                  placeholder="Color de pelaje"
                  aria-describedby="helpId"
                />
                <div
                  v-if="v$?.color.$error"
                  class="m-2 fv-plugins-message-container invalid-feedback"
                >
                  <div data-field="text_input" data-validator="notEmpty">
                    El color de pelaje es requerido
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-3 mb-5">
                <VueDatePicker
                  class="startDate"
                  v-model="fecha_nacimiento"
                  placeholder="Fecha de Nacimiento"
                  format="yyyy/MM/dd"
                >
                </VueDatePicker>
              </div>
              <div class="col-12 col-md-3 mb-5">
                <VueMultiselect
                  :close-on-select="true"
                  v-model="generoSeleccionado"
                  :options="generos"
                  selectLabel="Selecionar género"
                  placeholder="Buscar género..."
                  selectedLabel="Seleccionado"
                  :show-labels="false"
                >
                  <template v-slot:noResult>
                    <span>No se encontraron resultados</span>
                  </template>
                </VueMultiselect>
              </div>
            </div>

            <div class="form-group">
              <textarea
                class="form-control"
                v-model="notas"
                id=""
                rows="3"
                placeholder="Notas"
              ></textarea>
              <div
                v-if="v$?.notas.$error"
                class="m-2 fv-plugins-message-container invalid-feedback"
              >
                <div data-field="text_input" data-validator="notEmpty">
                  La nota es requerida
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div v-if="modo === 'editar'" class="mt-5">
    <button
      class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger"
      @click="eliminarCaballo(this.caballo.id)"
    >
      Eliminar
    </button>
  </div>
</template>

<script>
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import VueMultiselect from "vue-multiselect";

import FileUpload from "primevue/fileupload";

export default {
  name: "CaballoForm",
  props: ["caballo", "modo"],
  components: { VueDatePicker, VueMultiselect, FileUpload },
  setup() {},
  data() {
    return {
      nombre: this.modo === "editar" ? this.caballo.nombre : "",
      apodo: this.modo === "editar" ? this.caballo.apodo : "",
      raza: this.modo === "editar" ? this.caballo.raza : "",
      color: this.modo === "editar" ? this.caballo.color_pelaje : "",
      generoSeleccionado: this.modo === "editar" ? this.caballo.genero : "",
      generos: ["Macho", "Hembra", "Castrado"],
      notas: this.modo === "editar" ? this.caballo.notas : "",
      modo: this.modo,
      fecha_nacimiento:
        this.modo === "editar" ? this.caballo.fecha_nacimiento : null,
    };
  },
  validations() {},
  mounted() {},
  methods: {
    onAdvancedUpload(event) {
      const uploadedFile = event.files[0];
    },
    agregarCaballo: function () {
      axios
        .post("/api/caballo/agregar", {
          nombre: this.nombre,
          apodo: this.apodo,
          raza: this.raza,
          color: this.color,
          genero: this.generoSeleccionado,
          notas: this.notas,
          fecha_nacimiento: this.fecha_nacimiento,
        })
        .then((response) => {
          Swal.fire({
            title: "Éxito",
            text: "Caballo agregado correctamente",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Aceptar",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          }).then(() => {
            window.location.href = route("caballos.index");
          });
        })
        .catch((error) => {});
    },

    editarCaballo: function () {
      axios
        .patch(`/api/caballo/${this.caballo.id}`, {
          nombre: this.nombre,
          apodo: this.apodo,
          raza: this.raza,
          color: this.color,
          genero: this.generoSeleccionado,
          notas: this.notas,
          fecha_nacimiento: this.fecha_nacimiento,
        })
        .then((response) => {
          Swal.fire({
            title: "Éxito",
            text: "Datos modificados correctamente",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Aceptar",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          }).then(() => {
            window.location.href = route("caballos.index");
          });
        })
        .catch((error) => {});
    },
    eliminarCaballo(idCaballo) {
      Swal.fire({
        title: "¿Estás seguro?",
        text: "¡Esta acción eliminará el registro!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`/api/caballo/${idCaballo}`)
            .then((response) => {
              Swal.fire({
                title: "Éxito",
                text: "El registro se eliminó correctamente",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Aceptar",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              }).then(() => {
                window.location.href = route("caballos.index");
              });
            })
            .catch((error) => {});
        }
      });
    },
  },
};
</script>
