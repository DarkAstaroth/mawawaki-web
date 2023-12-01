<template>
  <div class="card card-bordered">
    <div class="card-header">
      <h3 class="card-title">Listado de Actividades</h3>
      <div class="div card-toolbar">
        <button
          type="button"
          class="btn btn-sm btn-success"
          @click="nuevaActividad"
        >
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
        @input="filtrarActividades"
        placeholder="Buscar..."
      />
      <div class="table-responsive">
        <table class="table table-striped table-sm table-bordered">
          <thead>
            <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
              <th class="min-w-150px">Título</th>
              <th class="min-w-150px">Descripción</th>
              <th class="max-w-100px">Fecha</th>
              <th class="max-w-100px">Verificada</th>
              <th class="max-w-100px">Destacada</th>
              <th class="min-w-150px">Acciones</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="actividad in store.actividades" :key="actividad.id">
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  {{ actividad.titulo }}
                </div>
              </td>
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  {{ actividad.descripcion }}
                </div>
              </td>
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  {{ formatearFecha(actividad.fecha) }}
                </div>
              </td>
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  <span
                    v-if="actividad.verificada"
                    class="badge badge-success badge-lg"
                  >
                    Verificada
                  </span>
                  <span v-else class="badge badge-secondary badge-lg">
                    No verificada
                  </span>
                </div>
              </td>
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  <span
                    v-if="actividad.destacada"
                    class="badge badge-success badge-lg"
                  >
                    Destacada
                  </span>
                  <span v-else class="badge badge-secondary badge-lg">
                    No Destacada
                  </span>
                </div>
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
                          @click="verificar(actividad.id)"
                          ><i class="bi bi-patch-check-fill"></i>Verificar</a
                        >
                      </li>

                      <li>
                        <a class="dropdown-item" @click="destacar(actividad.id)"
                          ><i class="bi bi-star-fill"></i> Destacar</a
                        >
                      </li>

                      <li>
                        <a
                          class="dropdown-item"
                          @click="eliminarActividad(actividad.id)"
                          ><i class="bi bi-trash"></i> Eliminar</a
                        >
                      </li>
                    </ul>
                  </div>
                </div>
              </td>
            </tr>
            <tr v-if="store.actividades.length === 0">
              <td colspan="6" class="text-center">No hay datos</td>
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
    v-model:visible="modalActividad"
    modal
    header="Nueva Actividad"
    position="top"
    :style="{ width: '40rem' }"
  >
    <form class="input-feild" v-on:submit.prevent="enviarActividad">
      <div class="">
        <div class="form-group">
          <div class="row">
            <div class="col-12 mb-5">
              <div class="d-flex flex-column gap-2">
                <label for="titulo">Fecha</label>
                <Calendar
                  showIcon
                  class="w-100"
                  id="calendar-12h"
                  v-model="actividad.fecha"
                />
              </div>
            </div>

            <div class="col-12 mb-5">
              <div class="d-flex flex-column gap-2">
                <label for="titulo">Título</label>
                <InputText v-model="actividad.titulo" />
              </div>
            </div>

            <div class="col-12">
              <div class="d-flex flex-column gap-2">
                <label for="username"> Descripcion</label>
                <Textarea v-model="actividad.descripcion" rows="5" cols="30" />
              </div>
            </div>
          </div>

          <div class="row mt-5">
            <div class="col">
              <button class="btn btn-sm btn-success">
                Registrar Actividad
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
import { useDataUsuarios } from "../../../store/dataUsuario";
import { useToast } from "primevue/usetoast";

export default {
  name: "ActividadesUsuario",
  props: ["usuario"],
  data() {
    return {
      busqueda: "",
      parametro: "todos",
      modalActividad: false,
      actividad: {
        titulo: "",
        descripcion: "",
        fecha: "",
      },
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
  setup() {
    const store = useDataUsuarios();
    const toast = useToast();
    return { store, toast };
  },
  mounted() {
    this.store.cargarActividades(
      1,
      this.busqueda,
      this.parametro,
      this.usuario.id
    );
  },
  methods: {
    recargar() {
      this.store.cargarActividades(
        1,
        this.busqueda,
        this.parametro,
        this.usuario.id
      );
    },
    filtrarActividades() {
      this.store.cargarActividades(
        1,
        this.busqueda,
        this.parametro,
        this.usuario.id
      );
    },
    formatearFecha(fechaUnix) {
      const fecha = new Date(fechaUnix * 1000);
      return fecha.toLocaleString("es-ES", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
      });
    },
    mostrarMensaje(tipo, titulo, texto) {
      this.toast.add({
        severity: tipo,
        summary: titulo,
        detail: texto,
        life: 2000,
      });
    },
    nuevaActividad() {
      console.log("Dialog");
      this.modalActividad = true;
    },
    async enviarActividad() {
      this.store
        .registrarActividad(this.usuario.id, this.actividad)
        .then((respuesta) => {
          this.mostrarMensaje(
            "success",
            "Operación Exitosa",
            "Actividad registrada con éxito"
          );
        })
        .catch((error) => {
          this.mostrarMensaje(
            "error",
            "Error",
            "No se puedo registrar la actividad"
          );
        });
      this.recargar();
      this.modalActividad = false;
      this.actividad = {
        titulo: "",
        descripcion: "",
        fecha: "",
      };
    },
    async verificar(id) {
      this.store
        .verificarActividad(id)
        .then((response) => {
          this.mostrarMensaje(
            "success",
            "Operación Exitosa",
            "Actividad verificada con éxito"
          );
        })
        .catch((error) => {
          this.mostrarMensaje(
            "error",
            "Error",
            "No se puedo verificar la actividad"
          );
        });
      this.recargar();
    },
    async destacar(id) {
      this.store
        .destacarActividad(id)
        .then((response) => {
          this.mostrarMensaje(
            "success",
            "Operación Exitosa",
            "Actividad destacada con éxito"
          );
        })
        .catch((error) => {
          this.mostrarMensaje(
            "error",
            "Error",
            "No se puedo destacar la actividad"
          );
        });
      this.recargar();
    },
    async eliminarActividad(idActividad) {
      if (confirm("¿Estás seguro de que deseas eliminar esta actividad?")) {
        this.store
          .eliminarActividad(idActividad)
          .then((respuesta) => {
            this.mostrarMensaje(
              "success",
              "Operación Exitosa",
              "Actividad eliminada con éxito"
            );
          })
          .catch((error) => {
            this.mostrarMensaje(
              "error",
              "Error",
              "No se puedo eliminar la actividad"
            );
          });
        this.recargar();
      }
    },
  },
};
</script>