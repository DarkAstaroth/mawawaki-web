<template>
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
      <div class="card mb-6">
        <div class="card-body">
          <div
            :class="[
              'd-flex',
              'flex-wrap',
              'justify-content-between',
              esResponsivo ? 'flex-column' : 'flex-row',
            ]"
          >
            <div
              :class="[
                'd-flex flex-grow-1',
                esResponsivo && 'flex-column align-items-center',
              ]"
            >
              <div class="me-10">
                <Avatar
                  :image="'/'.concat(this.store.usuario.profile_photo_path)"
                  class="mr-2"
                  size="xlarge"
                  shape="circle"
                />
              </div>

              <div
                :class="[
                  'd-flex flex-column justify-content-center  flex-grow-1',
                  esResponsivo ? 'align-items-center' : 'align-items-start',
                ]"
              >
                <div
                  class="d-flex align-items-center justify-content-center text-center"
                >
                  <a
                    href="#"
                    class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"
                  >
                    {{ this.store.usuario.persona.nombre }}
                    {{ this.store.usuario.persona.paterno }}
                    {{ this.store.usuario.persona.materno }}
                  </a>
                  <a href="#">
                    <i class="ki-duotone ki-verify fs-1 text-primary">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </a>
                </div>

                <div
                  class="d-flex justify-content-center flex-wrap fw-semibold fs-6 pe-2"
                >
                  <a
                    href="#"
                    class="d-flex align-items-center text-gray-400 text-hover-primary"
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
                <Button class="w-100" label="Editar" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <TabsUsuario />
    </div>
  </div>
</template>


<script>
import { useDataPerfil } from "../../../store/dataPerfil";
import VueMultiselect from "vue-multiselect";
import TabsUsuario from "./TabsUsuario.vue";

export default {
  name: "PerfilGlobal",

  components: { VueMultiselect, TabsUsuario },
  setup() {
    const store = useDataPerfil();
    return { store };
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
    verificarResponsivo() {
      this.esResponsivo = window.innerWidth < 768;
    },
  },
};
</script>