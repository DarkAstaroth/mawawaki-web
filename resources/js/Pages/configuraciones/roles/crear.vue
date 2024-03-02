<template></template>

<script>
import RolIndex from "./index.vue";
export default {
  data: function () {
    return {
      rolId: "",
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
      axios
        .post("/api/roles/agregar", {
          name: this.name,
          description: this.description,
        })
        .then((response) => {
          this.roles = response.data.data;
        })
        .catch((error) => {});
    },
    reiniciarFormulario: function () {
      this.name = "";
      this.description = "";
    },
  },
};
</script>
