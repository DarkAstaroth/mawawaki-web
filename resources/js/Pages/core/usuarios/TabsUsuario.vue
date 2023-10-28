<template>
    <div class="card card-bordered">
        <div class="card-body">

            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1"
                        @click="cambiarParametro('todos')">Asistencias</a>
                </li>
            </ul>

            <AsistenciasUsuario :usuario="usuario" />

        </div>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import VueMultiselect from 'vue-multiselect'
import AsistenciasUsuario from './Tabs/Asistencias.vue'

export default {
    name: "TabsUsuario",
    props: ["usuario"],
    components: { VueMultiselect, AsistenciasUsuario },

    setup() {
        return { v$: useVuelidate() };
    },

    data() {
        return {
            usuario: this.usuario,
            asistencias: []
        };
    },
    validations() {
        return {};
    },
    mounted() {

    },
    methods: {

    },
};
</script>


<style src="vue-multiselect/dist/vue-multiselect.css"></style>
