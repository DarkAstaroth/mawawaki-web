<template>
  <table class="table table-striped table-sm table-bordered">
    <thead>
      <tr class="fw-semibold fs-7 border-bottom border-gray-200 py-4">
        <th>Nombre</th>
        <th>Descripción</th>
        <th width="15%">Acciones</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="rol in roles" :key="rol.id">
        <td>{{ rol.name }}</td>
        <td>{{ rol.description }}</td>
        <td class="align-items-center">
          <div class="d-flex">
            <a href="#" class="btn btn-icon btn-active-light-primary"
              ><i class="bi bi-eye-fill fs-4"></i
            ></a>
            <a
              href="#"
              class="btn btn-icon btn-active-light-warning"
              data-bs-toggle="tooltip"
              data-bs-custom-class="tooltip-inverse"
              data-bs-placement="bottom"
              title="Editar permisos"
              ><i class="bi bi-pencil-square fs-4"></i
            ></a>
            <a
              type="button"
              class="btn btn-icon btn-active-light-danger eliminar-rol"
              data-bs-toggle="tooltip"
              data-bs-custom-class="tooltip-inverse"
              data-bs-placement="bottom"
              title="Eliminar rol"
            >
              <i class="bi bi-trash3-fill fs-4"></i>
            </a>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  name: "RolIndex",
  data: function () {
    return {
      roles: [],
    };
  },
  mounted() {
    this.cargarRoles();
  },
  methods: {
    cargarRoles: function () {
      axios
        .get("/api/roles")
        .then((response) => {
          console.log(response);
          this.roles = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>