<template>
  <div class="card card-bordered mb-3">
    <div class="card-header">
      <h3 class="card-title">{{ rol.name }}</h3>
      <div class="card-toolbar">
        <button
          type="button"
          class="btn btn-sm btn-success"
          data-bs-toggle="modal"
          data-bs-target="#kt_modal_1"
          @click="asignarPermisos()"
        >
          <i class="far fa-save text-white"></i>
          Guardar cambios
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="form-group mb-5">
        <input
          type="text"
          v-model="name"
          id=""
          class="form-control"
          placeholder="Nombre rol"
          aria-describedby="helpId"
        />
        <div
          v-if="v$?.name.$error"
          class="m-2 fv-plugins-message-container invalid-feedback"
        >
          <div data-field="text_input" data-validator="notEmpty">
            El nombre de rol es requerido
          </div>
        </div>
      </div>
      <div class="form-group">
        <textarea
          class="form-control"
          v-model="description"
          id=""
          rows="3"
          placeholder="Descripción"
        ></textarea>
        <div
          v-if="v$?.description.$error"
          class="m-2 fv-plugins-message-container invalid-feedback"
        >
          <div data-field="text_input" data-validator="notEmpty">
            La descripcion es requerida
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div
      v-for="modulo in modulos"
      :key="modulo.id"
      class="col-12 col-md-4 mb-4"
    >
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">{{ modulo.nombre }}</h5>
          <p class="card-text">{{ modulo.descripcion }}</p>
          <h6>Permisos:</h6>
          <template v-if="modulo.permisos && modulo.permisos.length > 0">
            <div v-for="permiso in modulo.permisos" :key="permiso.id">
              <div
                class="form-check form-check-custom form-check-solid form-check-sm mb-2"
              >
                <input
                  class="form-check-input"
                  type="checkbox"
                  value=""
                  :id="permiso.id"
                  :checked="permisosRol.includes(permiso.id)"
                  @change="verficarPermiso(permiso.id)"
                />
                <label class="mx-2" :for="permiso.id">
                  {{ permiso.name }}
                </label>
              </div>
            </div>
          </template>
          <template v-else>
            <div>No hay permisos disponibles.</div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";

export default {
  name: "PermisoRol",
  props: ["rol", "permisos_rol"],
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      rolId: this.rol.id,
      name: this.rol.name,
      description: this.rol.description,
      roles: [],
      permisosRol: this.permisos_rol,
      modulos: [],
      modo: "editar",
      enviado: false,
    };
  },
  validations() {
    return {
      name: { required },
      description: { required },
    };
  },
  mounted() {
    this.cargarModulos(this.rol.id);
  },
  methods: {
    cargarModulos: function () {
      axios.get(`/api/modulo/permisos`).then((response) => {
        this.modulos = response.data;
      });
    },
    verficarPermiso(permisoId) {
      if (this.permisosRol.includes(permisoId)) {
        this.permisosRol = this.permisosRol.filter((id) => id !== permisoId);
      } else {
        this.permisosRol.push(permisoId);
      }
    },
    asignarPermisos() {
      axios
        .post(`/api/asignar/permisos/rol/${this.rolId}`, {
          permisos: this.permisosRol,
        })
        .then((response) => {
          Swal.fire({
            title: response.data.estado ? "Éxito" : "Upss..",
            text: response.data.mensaje,
            icon: response.data.estado ? "success" : "error",
            buttonsStyling: false,
            confirmButtonText: "Aceptar",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });
        })
        .catch((error) => {
          console.log("Hay un error");
        });
    },
    actualizarRol: function () {
      axios
        .put(`/api/roles/${this.rolId}`, {
          name: this.name,
          description: this.description,
        })
        .then((response) => {
          Swal.fire({
            title: "Éxito",
            text: "El rol se modificó correctamente",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Aceptar",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });
          $("#kt_modal_1").modal("hide");
          this.busqueda = "";
          this.cargarRoles(1);
        })
        .catch((error) => {});
    },
    eliminarRol: function (rolId) {
      Swal.fire({
        title: "¿Estás seguro?",
        text: "¡Esta acción eliminará el rol!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`/api/roles/${rolId}`)
            .then((response) => {
              Swal.fire({
                title: "Éxito",
                text: "El rol se eliminó correctamente",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Aceptar",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              });
              this.busqueda = "";
              this.cargarRoles(1);
            })
            .catch((error) => {});
        }
      });
    },
  },
};
</script>
