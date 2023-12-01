<template>
  <FichasIndex />
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
      <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
        <li v-for="tab in tabs" :key="tab.parametro" class="nav-item">
          <a
            :class="{
              'nav-link': true,
              active: tab.parametro === parametro,
            }"
            data-bs-toggle="tab"
            :href="'#kt_tab_pane_' + tab.id"
            @click="cambiarParametro(tab.parametro)"
          >
            {{ tab.nombre }}
          </a>
        </li>
      </ul>

      <input
        class="mb-5 form-control"
        type="text"
        v-model="busqueda"
        @input="filtrarUsuarios"
        placeholder="Buscar..."
      />
      <div class="table-responsive">
        <table class="table table-striped table-sm table-bordered">
          <thead>
            <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
              <th class="min-w-150px">Nombre</th>
              <th class="min-w-150px">Rol</th>
              <th class="max-w-100px">Creado en</th>
              <th class="max-w-100px">Estado</th>
              <th class="min-w-150px">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="usuario in store.usuarios" :key="usuario.id">
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  <img
                    :src="usuario.profile_photo_path"
                    alt="foto"
                    class="p-1 rounded-pill"
                    width="50"
                  />
                  <div class="px-2 d-flex flex-column">
                    <div>
                      {{ usuario.persona.nombre }}
                      {{ usuario.persona.paterno }}
                      {{ usuario.persona.materno }}
                    </div>
                    <div class="py-1 bd-highlight">
                      <small>
                        {{ usuario.email }}
                      </small>
                    </div>
                  </div>
                </div>
              </td>
              <td class="align-middle">
                <div class="d-flex gap-2">
                  <div v-for="urol in usuario.roles" :key="urol.id">
                    <span class="badge badge-secondary">{{ urol.name }}</span>
                  </div>
                </div>
              </td>
              <td class="align-middle">
                {{
                  new Date(usuario.created_at).toLocaleString("es-ES", {
                    weekday: "long",
                    day: "numeric",
                    month: "long",
                    year: "numeric",
                    hour: "numeric",
                    minute: "numeric",
                    second: "numeric",
                  })
                }}
              </td>
              <td class="align-middle">
                <InputSwitch
                  v-model="usuario.estado"
                  @click="cambiarEstado(usuario)"
                />
              </td>
              <td class="align-middle">
                <div class="d-flex">
                  <div class="dropdown">
                    <button
                      class="btn btn-secondary dropdown-toggle btn-sm"
                      type="button"
                      id="dropdownMenuButton1"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                      data-boundary="viewport"
                    >
                      Acciones
                    </button>
                    <ul
                      class="dropdown-menu"
                      aria-labelledby="dropdownMenuButton1"
                    >
                      <li>
                        <a
                          class="dropdown-item"
                          data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse"
                          data-bs-placement="bottom"
                          title="Ver perfil"
                          :href="route('usuario.control', { id: usuario.id })"
                          ><i class="bi bi-eye-fill fs-4"></i> Ver Perfil</a
                        >
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          data-bs-toggle="modal"
                          data-bs-target="#kt_modal_1"
                          ><i class="bi bi-pencil-square fs-4"></i>

                          Editar</a
                        >
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          data-bs-toggle="modal"
                          data-bs-target="#kt_modal_1"
                          @click="resetModalData(true, usuario)"
                          ><i class="bi bi-envelope fs-4"></i>

                          Notificar</a
                        >
                      </li>
                    </ul>
                  </div>
                </div>
              </td>
            </tr>
            <tr v-if="store.usuarios.length === 0">
              <td colspan="4" class="text-center">No hay datos</td>
            </tr>
          </tbody>
        </table>
      </div>
      <nav>
        <ul class="pagination">
          <li
            class="page-item"
            :class="{ disabled: paginacion.paginaActual === 1 }"
          >
            <a class="page-link" href="#" @click="cargarRoles(1)">Primera</a>
          </li>
          <li
            class="page-item"
            :class="{ disabled: paginacion.paginaActual === 1 }"
          >
            <a
              class="page-link"
              href="#"
              @click="cargarRoles(paginacion.paginaActual - 1)"
              >Anterior</a
            >
          </li>
          <li
            class="page-item"
            v-for="page in paginacion.ultimaPagina"
            :key="page"
            :class="{ active: paginacion.paginaActual === page }"
          >
            <a class="page-link" href="#" @click="cargarRoles(page)">{{
              page
            }}</a>
          </li>
          <li
            class="page-item"
            :class="{
              disabled: paginacion.paginaActual === paginacion.ultimaPagina,
            }"
          >
            <a
              class="page-link"
              href="#"
              @click="cargarRoles(paginacion.paginaActual + 1)"
              >Siguiente</a
            >
          </li>
          <li
            class="page-item"
            :class="{
              disabled: paginacion.paginaActual === paginacion.ultimaPagina,
            }"
          >
            <a
              class="page-link"
              href="#"
              @click="cargarRoles(paginacion.ultimaPagina)"
              >Última</a
            >
          </li>
        </ul>
      </nav>
    </div>
  </div>

  <Dialog
    v-model:visible="modalNotificacion"
    modal
    header="Enviar notificacion"
    position="top"
    :style="{ width: '50rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
  >
    <form class="input-feild" v-on:submit.prevent="enviarNotificacion">
      <div class="">
        <div class="form-group">
          <div class="row">
            <div class="col-12 mb-5">
              <div class="d-flex flex-column gap-2">
                <label for="username">Tipo de notificación</label>
                <Dropdown
                  v-model="selectedTipoNotificacion"
                  :options="tiposNotificacion"
                  filter
                  optionLabel="nombre"
                  placeholder="Selecciona tipo de archivo"
                  class="w-100 md:w-14rem"
                >
                  <template #value="slotProps">
                    <div v-if="slotProps.value">
                      {{ slotProps.value.nombre }}
                    </div>
                    <span v-else>{{ slotProps.placeholder }}</span>
                  </template>
                  <template #option="slotProps">
                    <div>{{ slotProps.option.nombre }}</div>
                  </template>
                </Dropdown>
              </div>
            </div>

            <div class="col-12 mb-5">
              <div class="d-flex flex-column gap-2">
                <label for="titulo">Título</label>
                <InputText v-model="notificacion.titulo" />
              </div>
            </div>

            <div class="col-12">
              <div class="d-flex flex-column gap-2">
                <label for="username"> Mensaje</label>
                <Textarea v-model="notificacion.mensaje" rows="5" cols="30" />
              </div>
            </div>
          </div>

          <div class="row mt-5">
            <div class="col">
              <button class="btn btn-sm btn-success">
                Enviar Notificación
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </Dialog>

  <Toast />
</template>

<script>
import { ref } from "vue";
import { useDataUsuarios } from "../../../store/dataUsuario";
import FichasIndex from "./fichasIndex.vue";
import { useToast } from "primevue/usetoast";

export default {
  name: "UsuariosIndex",
  components: { FichasIndex },
  setup() {
    const store = useDataUsuarios();
    const selectedTipoNotificacion = ref(null);
    const toast = useToast();

    return { store, toast, selectedTipoNotificacion };
  },
  data() {
    return {
      busqueda: "",
      parametro: "todos",
      paginacion: {
        total: 0,
        porPagina: 10,
        paginaActual: 1,
        ultimaPagina: 1,
        desde: 0,
        hasta: 0,
      },
      tiposNotificacion: [
        { id: "info", nombre: "Informativo" },
        { id: "success", nombre: "Confirmación" },
        { id: "warning", nombre: "Alerta" },
      ],
      tabs: [
        { id: 1, nombre: "Todos", parametro: "todos" },
        { id: 2, nombre: "Activos", parametro: "activos" },
        { id: 3, nombre: "Solicitudes", parametro: "solicitudes" },
        { id: 4, nombre: "Verificados", parametro: "verificados" },
        { id: 5, nombre: "Por verificar", parametro: "porVerificar" },
        { id: 6, nombre: "Inactivos", parametro: "inactivos" },
      ],
      notificacion: { tipo: "", titulo: "", mensaje: "" },
      usuarioNotificacionID: "",
      modalNotificacion: false,
    };
  },
  validations() {},
  mounted() {
    this.store.cargarUsuarios(1, this.busqueda, this.parametro);
  },
  methods: {
    mostrarMensaje(tipo, titulo, texto) {
      this.toast.add({
        severity: tipo,
        summary: titulo,
        detail: texto,
        life: 2000,
      });
    },
    filtrarUsuarios() {
      this.store.cargarUsuarios(1, this.busqueda, this.parametro);
    },
    cambiarParametro(tabSeleccionada) {
      this.parametro = tabSeleccionada;
      this.store.cargarUsuarios(1, this.busqueda, this.parametro);
    },

    async cambiarEstado(usuario) {
      const respuesta = await this.store
        .actualizarEstado(usuario.id, usuario.estado)
        .then((respuesta) => {
          this.mostrarMensaje(
            "success",
            "Operación Exitosa",
            "Estado actualizado correctamente"
          );
        })
        .catch((error) => {
          this.mostrarMensaje(
            "error",
            "Error",
            "No se puedo cambiar el estado del usuario"
          );
        });
      this.store.cargarUsuarios(1, this.busqueda, this.parametro);
    },
    resetModalData: function (estado, usuario) {
      this.usuarioNotificacionID = usuario.id;
      this.modalNotificacion = estado;
    },
    async enviarNotificacion() {
      this.store
        .mandarNotificacion(
          this.usuarioNotificacionID,
          this.selectedTipoNotificacion.id,
          this.notificacion
        )
        .then((respuesta) => {
          this.mostrarMensaje(
            "success",
            "Operación Exitosa",
            "Notificación con enviada éxito"
          );
        })
        .catch((error) => {
          this.mostrarMensaje(
            "error",
            "Error",
            "No se puedo enviar la notificación"
          );
        });
      this.notificacion = { tipo: "", titulo: "", mensaje: "" };
      this.modalNotificacion = false;
    },
  },
};
</script>
