<template>
  <h3 class="card-title">Roles</h3>
  <div class="card-toolbar">
    <button
      type="button"
      class="btn btn-sm btn-success"
      data-bs-toggle="modal"
      data-bs-target="#kt_modal_1"
    >
      <i class="far fa-plus text-white"></i>
      Nuevo
    </button>
  </div>

  <div class="modal fade" tabindex="-1" id="kt_modal_1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Crear Rol</h3>

          <!--begin::Close-->
          <div
            class="btn btn-icon btn-sm btn-active-light-primary ms-2"
            data-bs-dismiss="modal"
            aria-label="Close"
          >
            <i class="ki-duotone ki-cross fs-1"
              ><span class="path1"></span><span class="path2"></span
            ></i>
          </div>
          <!--end::Close-->
        </div>
        <!-- <form action=""> -->
        <form class="input-feild" v-on:submit.prevent="enviarRol">
          <div class="modal-body">
            <div class="form-group mb-5">
              <input
                type="text"
                v-model="name"
                id=""
                class="form-control"
                placeholder="Nombre rol"
                aria-describedby="helpId"
              />
            </div>

            <div class="form-group">
              <textarea
                class="form-control"
                v-model="description"
                id=""
                rows="3"
                placeholder="Descripción"
              ></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
              Cerrar
            </button>
            <button
              type="submit"
              class="btn btn-success"
              data-bs-dismiss="modal"
            >
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>


<script>
import RolIndex from "./index.vue";
export default {
  data: function () {
    return {
      name: "",
      description: "",
    };
  },

  methods: {
    enviarRol: function () {
      this.crearRol();
      this.reiniciarFormulario();
      RolIndex.methods.cargarRoles();
    },
    crearRol: function () {
      console.log("creando rol");
      console.log(this.name);
      console.log(this.description);
    },
    reiniciarFormulario: function () {
      this.name = "";
      this.description = "";
    },
  },
};
</script>