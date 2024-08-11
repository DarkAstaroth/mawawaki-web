<template>
    <div class="row align-items-center p-3">
        <div class="col-md-3 mb-3">
            <Card
                class="h-100 card-with-overlay"
                style="
                    background-image: url('/assets/ilustraciones/bienvenida.jpeg');
                "
            >
                <template #header> </template>
                <template #content>
                    <div
                        class="overlay-content d-flex justify-content-center align-items-center flex-column gap-5 py-10 text-center"
                    >
                        <h3 class="display-6 text-white font-bold text-center">
                            Bienvenidos!
                        </h3>
                        <p class="fw-bold">
                            Centro Integral de Terapias Equinas Mawawaki
                            Sarañani
                        </p>
                        <qrcode-vue
                            :value="valorQr"
                            :size="100"
                            level="L"
                            foreground="#fff"
                            background="transparent"
                        />
                    </div>
                </template>
            </Card>
        </div>

        <div class="col-md-3 mb-3">
            <Card
                class="h-100 d-flex justify-content-center align-items-center"
            >
                <template #header> </template>
                <template #content>
                    <div
                        class="d-flex justify'content align-items-center flex-column"
                    >
                        <img
                            width="150"
                            class="mb-5"
                            src="/assets/ilustraciones/birthday.svg"
                            alt="Foto de Perfil"
                        />
                        <h3 class="text-center">¡Feliz Cumpleaños!</h3>
                        <div></div>
                        <p class="text-center px-5">
                            Conoce a nuestros cumpleañeros del mes y celebra con
                            ellos en su día especial.
                        </p>
                        <Button @click="abrirModal">Ver</Button>
                    </div>
                </template>
            </Card>
        </div>

        <div class="col-md-3 mb-3" v-for="aviso in avisosGlobales">
            <Card
                class="h-100 d-flex justify-content-center align-items-center"
            >
                <template #header> </template>
                <template #content>
                    <div
                        class="d-flex justify'content align-items-center flex-column"
                    >
                        <img :src="aviso.imagen" width="150" class="mb-5" />
                        <h3 class="text-center">{{ aviso.titulo }}</h3>
                        <div></div>
                        <p class="text-center px-5">{{ aviso.descripcion }}</p>
                        <Button v-if="aviso.url">Ver detalles</Button>
                    </div>
                </template>
            </Card>
        </div>
    </div>

    <Dialog
        v-model:visible="visible"
        modal
        header="Lista de cumpleañeros"
        :style="{ width: '50vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <div class="col-12 col-md-12">
            <DataTable
                :value="usuariosCumpleanos"
                paginator
                :rows="10"
                :rowsPerPageOptions="[5, 10]"
                tableStyle="min-width: 50rem"
            >
                <Column
                    field="persona.paterno"
                    header="PATERNO"
                    style="width: 25%"
                ></Column>
                <Column
                    field="persona.materno"
                    header="MATERNO"
                    style="width: 25%"
                ></Column>
                <Column
                    field="persona.nombre"
                    header="NOMBRES"
                    style="width: 25%"
                ></Column>

                <Column
                    field="persona.fecha_nacimiento"
                    header="FECHA CUMPLEAÑOS"
                    style="width: 25%"
                ></Column>
            </DataTable>
        </div>
    </Dialog>
</template>

<script>
import { useDataAvisos } from "@/store/dataAvisos";
import QrcodeVue from "qrcode.vue";
import.meta.env.VITE_APP_BASE_URL;

export default {
    name: "AvisosComponent",
    components: {
        QrcodeVue,
    },
    data() {
        return {
            visible: false,
            usuariosCumpleanos: [],
            avisosGlobales: [],
            valorQr: `${import.meta.env.VITE_APP_BASE_URL}`,
        };
    },
    template: '<qrcode-vue :value="value"></qrcode-vue>',
    setup() {
        const store = useDataAvisos();
        return { store };
    },
    mounted() {
        this.store
            .obtenerAvisosGlobales()
            .then((respuesta) => {
                this.avisosGlobales = respuesta;
            })
            .catch(() => {
                this.avisosGlobales = [];
            });
    },
    methods: {
        abrirModal() {
            this.visible = true;
            this.store.obtenerCumpleanos().then((respuesta) => {
                console.log(respuesta);
                this.usuariosCumpleanos = respuesta;
            });
        },
    },
};
</script>

<style scoped>
.card-with-overlay {
    position: relative;
    background-size: cover;
    background-position: center top;
    border-radius: 2%;
}

.card-with-overlay::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.35);
    z-index: 1;
    border-radius: 5%;
}

.overlay-content {
    position: relative;
    z-index: 2;
    color: white;
}
</style>
