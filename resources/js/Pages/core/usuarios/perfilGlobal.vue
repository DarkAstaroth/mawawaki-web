<template>
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
      <div class="card mb-6">
        <div class="card-body">
          <div
            :class="[
              'd-flex ',
              'flex-wrap',
              'justify-content-center',
              esResponsivo ? 'flex-column' : 'flex-row',
            ]"
          >
            <div
              :class="[
                'd-flex flex-grow-1 justify-content-center',
                esResponsivo && 'flex-column align-items-center',
              ]"
            >
              <div
                :class="[
                  'd-flex justify-content-center',
                  esResponsivo ? 'w-100 mb-5' : 'me-10',
                ]"
              >
                <div class="container_usuario">
                  <img
                    :src="'/'.concat(this.store.usuario.profile_photo_path)"
                    alt=""
                    class="crop"
                    width="150"
                  />
                </div>
              </div>

              <div
                :class="[
                  'd-flex flex-column justify-content-center  flex-grow-1',
                  esResponsivo ? 'align-items-center' : 'align-items-start',
                ]"
              >
                <div
                  :class="[
                    'd-flex align-items-center justify-content-center text-center ',
                    esResponsivo ? 'flex-column gap-0' : 'gap-2',
                  ]"
                >
                  <a
                    href="#"
                    class="text-gray-900 text-hover-primary fs-2 fw-bold"
                  >
                    {{ this.store.usuario.persona.nombre }}
                  </a>

                  <a
                    href="#"
                    class="text-gray-900 text-hover-primary fs-2 fw-bold"
                  >
                    {{ this.store.usuario.persona.paterno }}
                    {{ this.store.usuario.persona.materno }}
                    <a href="#">
                      <i class="ki-duotone ki-verify fs-1 text-primary">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                    </a>
                  </a>
                </div>

                <div
                  class="d-flex justify-content-center flex-wrap fw-semibold fs-6 pe-2"
                >
                  <a
                    href="#"
                    class="d-flex align-items-center text-gray-400 text-hover-primary mb-3"
                  >
                    <i class="ki-duotone ki-sms fs-4 me-1">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                    {{ this.store.usuario.email }}
                  </a>
                </div>

                <div
                  :class="[
                    'd-flex flex-row w-100 gap-2 flex-wrap',
                    esResponsivo && 'justify-content-center mb-5',
                  ]"
                >
                  <Badge
                    v-for="(rol, index) in this.store.usuario.roles"
                    :key="index"
                    :value="rol.name"
                    severity="secondary"
                  ></Badge>
                </div>
              </div>
            </div>

            <div class="d-flex flex-column gap-3">
              <div class="w-full">
                <FileUpload
                  class="w-100"
                  mode="basic"
                  name="foto[]"
                  :url="`/api/imagen/usuario/${store.usuario.id}`"
                  accept="image/*"
                  chooseLabel="Subir Foto"
                  :maxFileSize="2097152"
                  @upload="onUpload"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <TabsUsuario />
    </div>
  </div>
  <Toast />
</template>


<script>
import { useDataPerfil } from "../../../store/dataPerfil";
import VueMultiselect from "vue-multiselect";
import TabsUsuario from "./TabsUsuario.vue";
import { useToast } from "primevue/usetoast";

export default {
  name: "PerfilGlobal",

  components: { VueMultiselect, TabsUsuario },
  setup() {
    const store = useDataPerfil();
    const toast = useToast();

    return { store, toast };
  },
  created() {
    this.verificarResponsivo();
    window.addEventListener("resize", this.verificarResponsivo);
  },
  destroyed() {
    window.removeEventListener("resize", this.verificarResponsivo);
  },
  data() {
    return {
      asistencias: [],
      esResponsivo: false,
    };
  },
  validations() {
    return {};
  },
  mounted() {},
  methods: {
    mostrarMensaje(tipo, titulo, texto) {
      this.toast.add({
        severity: tipo,
        summary: titulo,
        detail: texto,
        life: 2000,
      });
    },
    verificarResponsivo() {
      this.esResponsivo = window.innerWidth < 768;
    },
    async onUpload(event) {
      try {
        const response = event.xhr;

        if (response.status === 200) {
          this.mostrarMensaje(
            "success",
            "Operación Exitosa",
            "Foto subida con éxito, Recarga la página"
          );
        } else {
          this.mostrarMensaje("error", "Error", "No se puedo subir la foto");
        }
      } catch (error) {
        this.mostrarMensaje("error", "Error", "No se puedo subir la foto");
      }
    },
  },
};
</script>