<template>
    <form class="input-feild" @submit.prevent="crearAviso">
        <div class="d-flex justify-content-between gap-5 mb-5">
            <div class="d-flex aling-items-center justify-content-center gap-2">
                <i class="fi fi-rr-comment-alt fs-1 text-success"></i>
                <h3 class="card-title">Crear nuevo Aviso</h3>
            </div>
            <button type="submit" class="btn btn-success btn-sm">
                Guardar
            </button>
        </div>

        <div
            :class="[
                'd-flex gap-5',
                esResponsivo ? ' flex-column ' : 'flex-row',
            ]"
        >
            <div :class="[esResponsivo ? 'col-12' : 'col-6']">
                <div class="card p-10 flex-grow-1">
                    <div class="d-flex flex-column gap-2">
                        <label for="titulo">Titulo</label>
                        <InputText
                            v-model="aviso.titulo"
                            class="text-capitalize"
                        />
                        <div
                            v-if="v$?.titulo.$error"
                            class="fv-plugins-message-container invalid-feedback"
                        >
                            <div
                                data-field="text_input"
                                data-validator="notEmpty"
                            >
                                El titulo es requerido
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="calendar-12h" class="font-bold block mb-2">
                            Descripcion
                        </label>
                        <Textarea
                            class="w-100 text-capitalize"
                            v-model="aviso.descripcion"
                            rows="5"
                            placeholder="Descripción"
                        />
                        <div
                            v-if="v$?.descripcion.$error"
                            class="fv-plugins-message-container invalid-feedback"
                        >
                            <div
                                data-field="text_input"
                                data-validator="notEmpty"
                            >
                                La descripción del evento es requerida
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="titulo" class="mb-2">Imagen</label>
                        <input
                            type="file"
                            class="form-control flex-1"
                            id="inputArchivo"
                            @change="manejarCambioArchivo($event)"
                            accept=".svg, .jpg, .jpeg, .png"
                        />
                    </div>

                    <div class="d-flex gap-2">
                        <div>
                            <label for="ingreso" class="ml-5 px-2"
                                >Global</label
                            >
                            <div class="p-1 mt-1">
                                <InputSwitch v-model="aviso.global" />
                            </div>
                        </div>
                        <div>
                            <label for="ingreso" class="ml-5 px-2"
                                >Principal</label
                            >
                            <div class="p-1 mt-1">
                                <InputSwitch v-model="aviso.principal" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <Toast />
</template>

<script>
import { useDataAvisos } from "@/store/dataAvisos";
import { useToast } from "primevue/usetoast";
import { useRouter } from "vue-router";

export default {
    name: "CrearAviso",
    props: ["usuario"],
    data() {
        return {
            aviso: {
                usuario_id: this.usuario.id,
                titulo: "",
                descripcion: "",
                archivo: null,
                global: false,
                principal: false,
            },
            esResponsivo: false,
        };
    },
    setup() {
        const store = useDataAvisos();
        const toast = useToast();

        return { store, toast };
    },
    methods: {
        crearAviso() {
            this.store
                .crearAviso(this.aviso)
                .then(() => {
                    this.toast.add({
                        severity: "success",
                        summary: "Confirmado",
                        detail: "El aviso se creo correctamente",
                        life: 3000,
                    });

                    setTimeout(() => {
                        window.location.href = "/dashboard/gestion/avisos";
                    }, 2000);
                })
                .catch(() => {
                    console.log("error");
                });
            this.aviso.titulo = "";
            this.aviso.descripcion = "";
            this.aviso.archivo = null;
            this.aviso.global = false;
            this.aviso.principal = false;
        },
        manejarCambioArchivo(event) {
            if (event.target.files && event.target.files.length > 0) {
                const archivo = event.target.files[0];

                const extension = archivo.name.split(".").pop().toLowerCase();
                if (["svg", "jpg", "jpeg", "png"].includes(extension)) {
                    this.aviso.archivo = archivo;
                } else {
                    alert("Por favor seleccione un archivo SVG, JPG o PNG.");
                }
            }
        },
        verificarResponsivo() {
            this.esResponsivo = window.innerWidth < 768;
        },
    },
    created() {
        this.verificarResponsivo();
        window.addEventListener("resize", this.verificarResponsivo);
    },
    destroyed() {
        window.removeEventListener("resize", this.verificarResponsivo);
    },
};
</script>
