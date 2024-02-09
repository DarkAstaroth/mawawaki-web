<template>
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
      <div class="card mb-6">
        <div class="card-body pt-9 pb-0">
          <div class="d-flex flex-wrap flex-sm-nowrap">
            <div class="me-7 mb-4">
              <div
                class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative"
              >
                <img
                  :src="`/${this.store.usuario.profile_photo_path}`"
                  alt="foto"
                />
                <div
                  class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"
                ></div>
              </div>
            </div>

            <div class="flex-grow-1">
              <div
                class="d-flex justify-content-between align-items-start flex-wrap mb-2"
              >
                <div class="d-flex flex-column">
                  <div class="d-flex align-items-center mb-2">
                    <a
                      href="#"
                      class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"
                    >
                      {{ this.store.usuario.persona.nombre }}
                      {{ this.store.usuario.persona.paterno }}
                      {{ this.store.usuario.persona.materno }}</a
                    >
                    <a href="#">
                      <i class="ki-duotone ki-verify fs-1 text-primary">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                    </a>
                  </div>

                  <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                    <a
                      href="#"
                      class="d-flex align-items-center text-gray-400 text-hover-primary mb-2"
                    >
                      <i class="ki-duotone ki-sms fs-4 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span> </i
                      >{{ this.store.usuario.email }}</a
                    >
                  </div>
                </div>
              </div>

              <div class="d-flex flex-wrap flex-stack">
                <div class="d-flex flex-column flex-grow-1 pe-8">
                  <div class="d-flex flex-wrap">
                    <div
                      class="border border-gray-300 border-dashed rounded min-w-10px py-3 px-4 me-6 mb-3"
                    >
                      <div
                        class="d-flex align-items-center justify-content-center"
                      >
                        <div
                          class="fs-2 fw-bold"
                          data-kt-countup="true"
                          data-kt-countup-value="2"
                          data-kt-countup-prefix=""
                        >
                          0
                        </div>
                      </div>

                      <div class="fw-semibold fs-6 text-gray-400">Horas</div>
                    </div>

                    <div
                      class="border border-gray-300 border-dashed rounded min-w-10px py-3 px-4 me-6 mb-3"
                    >
                      <div
                        class="d-flex align-items-center justify-content-center"
                      >
                        <div
                          class="fs-2 fw-bold"
                          data-kt-countup="true"
                          data-kt-countup-value="40"
                          data-kt-countup-prefix=""
                        >
                          0
                        </div>
                      </div>

                      <div class="fw-semibold fs-6 text-gray-400">Minutos</div>
                    </div>
                    <div
                      class="border border-gray-300 border-dashed rounded min-w-10px py-3 px-4 me-6 mb-3"
                    >
                      <div
                        class="d-flex align-items-center justify-content-center"
                      >
                        <div
                          class="fs-2 fw-bold"
                          data-kt-countup="true"
                          data-kt-countup-value="35"
                          data-kt-countup-prefix=""
                        >
                          0
                        </div>
                      </div>

                      <div class="fw-semibold fs-6 text-gray-400">Segundos</div>
                    </div>
                  </div>
                </div>

                <div
                  class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3"
                >
                  <div
                    class="d-flex justify-content-between w-100 mt-auto mb-2"
                  >
                    <span class="fw-semibold fs-6 text-gray-400"
                      >Estado Perfil</span
                    >
                    <span class="fw-bold fs-6">50%</span>
                  </div>
                  <div class="h-5px mx-3 w-100 bg-light mb-3">
                    <div
                      class="bg-success rounded h-5px"
                      role="progressbar"
                      style="width: 50%"
                      aria-valuenow="50"
                      aria-valuemin="0"
                      aria-valuemax="100"
                    ></div>
                  </div>
                </div>
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
  data() {
    return {
      asistencias: [],
    };
  },
  validations() {
    return {};
  },
  mounted() {},
  methods: {},
};
</script>