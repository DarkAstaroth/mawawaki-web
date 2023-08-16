<template>
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de usuarios</h3>
        </div>
    </div>
</template>

<script>
export default {
    name: "UsuariosIndex",
    setup() {},
    data() {
        return {
            usuarios: [],
            busqueda: "",
            paginacion: {
                total: 0,
                porPagina: 10,
                paginaActual: 1,
                ultimaPagina: 1,
                desde: 0,
                hasta: 0,
            },
        };
    },
    validations() {},
    mounted() {
        this.cargarUsuarios(1);
    },
    methods: {
        cargarUsuarios(pagina) {
            const url =
                "/api/usuarios?page=" + pagina + "&busqueda=" + this.busqueda;
            console.log(url);
        },
    },
};
</script>
