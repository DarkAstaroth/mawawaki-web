<template>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="grid grid-cols-1 sm:grid-cols-[25%_75%] gap-4">
                <div id="credencial" ref="elementToConvert">
                    <div class="relative">
                        <div class="absolute inset-0">
                            <img
                                src="/assets/ilustraciones/credencial.png"
                                alt=""
                                crossorigin="anonymous"
                            />
                        </div>
                        <div class="relative z-10 h-[500px]">
                            <div class="h-full">
                                <div class="flex flex-col items-center">
                                    <div class="container_usuario mt-20">
                                        <img
                                            :src="
                                                '/'.concat(
                                                    this.store.usuario
                                                        .profile_photo_path
                                                )
                                            "
                                            alt=""
                                            class="crop"
                                            width="150"
                                            crossorigin="anonymous"
                                        />
                                    </div>

                                    <div
                                        class="flex flex-col items-center font-bold text-xl text-gray-900 mt-5"
                                    >
                                        <span>
                                            {{
                                                this.store.usuario.persona
                                                    .nombre
                                            }}
                                        </span>

                                        <a>
                                            {{
                                                this.store.usuario.persona
                                                    .paterno
                                            }}
                                            {{
                                                this.store.usuario.persona
                                                    .materno
                                            }}
                                        </a>
                                    </div>

                                    <div class="flex gap-2">
                                        <div
                                            href="#"
                                            class="flex text-gray-400 mb-3"
                                        >
                                            <i
                                                class="ki-duotone ki-sms fs-4 me-1"
                                            >
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            {{ this.store.usuario.email }}
                                        </div>
                                        <a href="#">
                                            <i
                                                class="ki-duotone ki-verify fs-1 text-primary"
                                            >
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                    </div>

                                    <div class="flex gap-2">
                                        <Badge
                                            v-for="(
                                                rol, index
                                            ) in filteredRoles"
                                            :key="index"
                                            :value="rol.name"
                                            severity="secondary"
                                        ></Badge>
                                    </div>

                                    <div
                                        v-if="
                                            this.store.usuario.personal
                                                .codigo_personal
                                        "
                                        class="mt-5"
                                    >
                                        <qrcode-vue
                                            :value="
                                                this.store.usuario.personal
                                                    .codigo_personal
                                            "
                                            :size="80"
                                            level="L"
                                            foreground="#102820"
                                            background="transparent"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="columna2">
                    <div>
                        <div
                            class="flex gap-2 justify-center sm:justify-end mb-5 -mt-8 sm:mt-0"
                        >
                            <div class="flex">
                                <div class="">
                                    <FileUpload
                                        v-if="!this.store.cargarFoto"
                                        class=""
                                        mode="basic"
                                        name="foto[]"
                                        :url="`/api/imagen/usuario/${store.usuario.id}`"
                                        accept="image/*"
                                        chooseLabel="Subir Foto"
                                        :maxFileSize="3145728"
                                        @upload="onUpload"
                                        :invalid-file-size-message="`la imágen no debe superar los 3 MB`"
                                        @progress="100"
                                    />
                                    <div v-else class="flex flex-grow p-1">
                                        <ProgressSpinner
                                            style="width: 30px; height: 30px"
                                            strokeWidth="5"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <Button
                                    type="button"
                                    @click="convertAndDownloadImage"
                                    class="w-full"
                                    severity="info"
                                >
                                    <div
                                        class="flex justify-center gap-2 w-full"
                                    >
                                        <i
                                            class="text-white fi fi-br-download"
                                        ></i>
                                        <strong>Descargar</strong>
                                    </div>
                                </Button>
                            </div>
                        </div>
                    </div>

                    <TabsUsuario />
                </div>
            </div>
        </div>
    </div>

    <Toast />
</template>

<script>
import { useDataPerfil } from "../../../store/dataPerfil";
import VueMultiselect from "vue-multiselect";
import TabsUsuario from "./TabsUsuario.vue";
import { useToast } from "primevue/usetoast";
import QrcodeVue from "qrcode.vue";
import { toPng } from "html-to-image";

export default {
    name: "PerfilGlobal",

    components: { VueMultiselect, TabsUsuario, QrcodeVue },
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
            rolesToIgnore: ["personal", "Asistente"],
        };
    },
    template: '<qrcode-vue :value="value"></qrcode-vue>',
    validations() {
        return {};
    },
    computed: {
        filteredRoles() {
            return this.store.usuario.roles.filter(
                (rol) => !this.rolesToIgnore.includes(rol.name)
            );
        },
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
                this.store.cargarFoto = true;
                const response = event.xhr;

                if (response.status === 200) {
                    this.store.cargarFoto = false;
                    this.mostrarMensaje(
                        "success",
                        "Operación Exitosa",
                        "Foto subida con éxito, Recarga la página"
                    );
                    window.location.reload();
                } else {
                    this.store.cargarFoto = false;
                    this.mostrarMensaje(
                        "error",
                        "Error",
                        "No se puedo subir la foto"
                    );
                }
            } catch (error) {
                this.store.cargarFoto = false;
                this.mostrarMensaje(
                    "error",
                    "Error",
                    "No se puedo subir la foto"
                );
            }
        },
        descargarCredencial() {
            const element = document.getElementById("credencial");
            html2canvas(element).then((canvas) => {
                const link = document.createElement("a");
                link.download = "credencial.png";
                link.href = canvas.toDataURL("image/png");
                link.click();
            });
        },
        convertAndDownloadImage() {
            const element = this.$refs.elementToConvert;
            toPng(element, {
                cacheBust: true,
                useCORS: true,
            })
                .then((dataUrl) => {
                    const link = document.createElement("a");
                    link.href = dataUrl;
                    link.download = "credencial.png";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        },
    },
};
</script>
