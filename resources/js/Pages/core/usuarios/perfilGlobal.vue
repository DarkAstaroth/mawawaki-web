<template>
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Navbar-->
      <div class="card mb-6">
        <div class="card-body pt-9 pb-0">
          <!--begin::Details-->
          <div class="d-flex flex-wrap flex-sm-nowrap">
            <!--begin: Pic-->
            <div class="me-7 mb-4">
              <div
                class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative"
              >
                <img :src="`/${usuario.profile_photo_path}`" alt="foto" />
                <div
                  class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"
                ></div>
              </div>
            </div>
            <!--end::Pic-->
            <!--begin::Info-->
            <div class="flex-grow-1">
              <!--begin::Title-->
              <div
                class="d-flex justify-content-between align-items-start flex-wrap mb-2"
              >
                <!--begin::User-->
                <div class="d-flex flex-column">
                  <!--begin::Name-->
                  <div class="d-flex align-items-center mb-2">
                    <a
                      href="#"
                      class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"
                    >
                      {{ this.usuario.persona.nombre }}
                      {{ this.usuario.persona.paterno }}
                      {{ this.usuario.persona.materno }}</a
                    >
                    <a href="#">
                      <i class="ki-duotone ki-verify fs-1 text-primary">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                    </a>
                  </div>
                  <!--end::Name-->
                  <!--begin::Info-->
                  <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                    <a
                      href="#"
                      class="d-flex align-items-center text-gray-400 text-hover-primary mb-2"
                    >
                      <i class="ki-duotone ki-sms fs-4 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span> </i
                      >{{ this.usuario.email }}</a
                    >
                  </div>
                  <!--end::Info-->
                </div>
                <!--end::User-->
              </div>
              <!--end::Title-->
              <!--begin::Stats-->
              <div class="d-flex flex-wrap flex-stack">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column flex-grow-1 pe-8">
                  <!--begin::Stats-->
                  <div class="d-flex flex-wrap">
                    <!--begin::Stat-->
                    <div
                      class="border border-gray-300 border-dashed rounded min-w-10px py-3 px-4 me-6 mb-3"
                    >
                      <!--begin::Number-->
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
                      <!--end::Number-->
                      <!--begin::Label-->
                      <div class="fw-semibold fs-6 text-gray-400">Horas</div>
                      <!--end::Label-->
                    </div>

                    <div
                      class="border border-gray-300 border-dashed rounded min-w-10px py-3 px-4 me-6 mb-3"
                    >
                      <!--begin::Number-->
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
                      <!--end::Number-->
                      <!--begin::Label-->
                      <div class="fw-semibold fs-6 text-gray-400">Minutos</div>
                      <!--end::Label-->
                    </div>
                    <div
                      class="border border-gray-300 border-dashed rounded min-w-10px py-3 px-4 me-6 mb-3"
                    >
                      <!--begin::Number-->
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
                      <!--end::Number-->
                      <!--begin::Label-->
                      <div class="fw-semibold fs-6 text-gray-400">Segundos</div>
                      <!--end::Label-->
                    </div>
                    <!--end::Stat-->
                  </div>
                  <!--end::Stats-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Progress-->
                <div
                  class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3"
                >
                  <div
                    class="d-flex justify-content-between w-100 mt-auto mb-2"
                  >
                    <span class="fw-semibold fs-6 text-gray-400"
                      >Profile Compleation</span
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
                <!--end::Progress-->
              </div>
              <!--end::Stats-->
            </div>
            <!--end::Info-->
          </div>
          <!--end::Details-->
          <!--begin::Navs-->

          <!--begin::Navs-->
        </div>
      </div>
      <!--end::Navbar-->

      <TabsUsuario :usuario="usuario" />
    </div>
    <!--end::Content container-->
  </div>
</template>



<script>
import { useVuelidate } from "@vuelidate/core";
import VueMultiselect from "vue-multiselect";
import TabsUsuario from "./TabsUsuario.vue";

export default {
  name: "PerfilGlobal",
  props: ["usuario"],
  components: { VueMultiselect, TabsUsuario },

  setup() {
    return { v$: useVuelidate() };
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