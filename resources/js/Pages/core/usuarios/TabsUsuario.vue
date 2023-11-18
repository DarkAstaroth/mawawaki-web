<template>
    <div class="card card-bordered">
        <div class="card-body">

            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1"
                        @click="cambiarParametro('todos')">Asistencias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Documentos</a>
                </li>
            </ul>


            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                    <AsistenciasUsuario :usuario="usuario" />
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                    <DocumentacionUsuario :usuario="usuario" />
                </div>

            </div>

        </div>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import VueMultiselect from 'vue-multiselect'
import AsistenciasUsuario from './Tabs/Asistencias.vue'
import DocumentacionUsuario from './Tabs/Documentacion.vue'


export default {
    name: "TabsUsuario",
    props: ["usuario"],
    components: { VueMultiselect, AsistenciasUsuario, DocumentacionUsuario },

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
