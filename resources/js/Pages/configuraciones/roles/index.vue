<template>
  <div v-for="rol in roles">{{ rol.name }}</div>  
</template>
<script>
export default {
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
          this.roles = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>