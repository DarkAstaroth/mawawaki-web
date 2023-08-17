<template>
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de usuarios</h3>
            <div class="div card-toolbar">
                <button type="button" class="btn btn-sm btn-success">
                    <i class="text-white far fa-plus"></i>
                    Nuevo
                </button>
            </div>
        </div>

        <div class="card-body">
            <input
                class="mb-5 form-control"
                type="text"
                v-model="busqueda"
                @input="filtrarRoles"
                placeholder="Buscar..."
            />
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead>
                        <tr
                            class="py-4 border-gray-200 fw-semibold fs-7 border-bottom"
                        >
                            <th class="min-w-150px">Nombre</th>
                            <th class="min-w-150px">Email</th>
                            <th class="min-w-150px">Creado en</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="usuario in usuarios" :key="usuario.id">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img
                                        :src="usuario.profile_photo_url"
                                        alt="foto"
                                        class="p-1 rounded-pill"
                                        width="50"
                                    />
                                    <div
                                        class="px-2 d-flex flex-column bd-highlight"
                                    >
                                        <div class="bd-highlight">
                                            {{ usuario.name }}
                                        </div>
                                        <div class="py-1 bd-highlight">
                                            <small>{{ usuario.email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ usuario.email }}</td>
                            <td>{{ usuario.created_at }}</td>
                            <td>Acciones</td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
            axios
                .get(url)
                .then((response) => {
                    this.usuarios = response.data.usuarios;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>
