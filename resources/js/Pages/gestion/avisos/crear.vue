<template>
    <form class="input-feild" v-on:submit.prevent="crearEvento">
        <div class="d-flex justify-content-between gap-5 mb-5">
            <div class="d-flex aling-items-center justify-content-center gap-2">
                <i class="fi fi-rr-comment-alt fs-1 text-success"></i>
                <h3 class="card-title">Crear nuevo Aviso</h3>
            </div>
            <button type="submit" class="btn btn-success btn-sm">Guardar</button>
        </div>

        <div :class="['d-flex gap-5', esResponsivo ? ' flex-column ' : 'flex-row']">
            <div :class="[esResponsivo ? 'col-12' : 'col-6']">
                <div class="card p-10 flex-grow-1">

                    <div class="d-flex flex-column gap-2">
                        <label for="titulo">Titulo</label>
                        <InputText v-model="titulo" class="text-capitalize" />
                        <div v-if="v$?.titulo.$error" class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">
                                El titulo es requerido
                            </div>
                        </div>
                    </div>


                    <div class="mb-2">
                        <label for="calendar-12h" class="font-bold block mb-2">
                            Descripcion
                        </label>
                        <Textarea class="w-100 text-capitalize" v-model="descripcion" rows="5"
                            placeholder="Descripción" />
                        <div v-if="v$?.descripcion.$error" class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">
                                La descripción del evento es requerida
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-5">
                        <label for="titulo">URL</label>
                        <InputText v-model="URL" class="text-capitalize" />
                        <div v-if="v$?.titulo.$error" class="fv-plugins-message-container invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty">
                                El titulo es requerido
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <div>
                            <label for="ingreso" class="ml-5 px-2">Global</label>
                            <div class="p-1 mt-1">
                                <InputSwitch v-model="global" />
                            </div>
                        </div>
                        <div>
                            <label for="ingreso" class="ml-5 px-2">Principal</label>
                            <div class="p-1 mt-1">
                                <InputSwitch v-model="principal" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-6 w-100">
                        <MultiSelect class="w-100" v-model="usuariosSeleccionados" :options="usuariosFiltro" filter
                            optionLabel="name" placeholder="Selecciona Usuarios" :maxSelectedLabels="3" />
                    </div>
                </div>
            </div>
        </div>
    </form>
    <Toast />
</template>

<script>
export default {
    name: "CrearAviso",
    data() {
        return {
            titulo: "",
            descripcion: "",
            url: "",
            global: false,
            principal: false,
            esResponsivo: false,

        }
    },
    methods: {
        created() {
            this.verificarResponsivo();
            window.addEventListener("resize", this.verificarResponsivo);
        },
        destroyed() {
            window.removeEventListener("resize", this.verificarResponsivo);
        },
        verificarResponsivo() {
            this.esResponsivo = window.innerWidth < 768;
        },
    }
}
</script>
