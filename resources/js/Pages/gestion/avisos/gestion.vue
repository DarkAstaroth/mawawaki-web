<template>
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Listado de avisos</h3>
            <div class="div card-toolbar">
                <a :href="route('avisos.crear')" type="button" class="btn btn-sm btn-success">
                    <i class="text-white far fa-plus"></i>
                    Nuevo
                </a>
            </div>
        </div>

        <div class="card-body">
            <input class="mb-5 form-control" type="text" v-model="busqueda" @input="filtrarAvisos"
                placeholder="Buscar..." />
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead>
                        <tr class="py-4 border-gray-200 fw-semibold fs-7 border-bottom">
                            <th class="min-w-150px">Titulo</th>
                            <th class="min-w-150px">Descripcion</th>
                            <th class="min-w-150px">Tipo</th>
                            <th class="min-w-200px">Estado</th>
                            <th class="min-w-150px">Global</th>
                            <th class="min-w-150px">Principal</th>
                            <th class="min-w-50px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="aviso in store.avisos" :key="aviso.id">
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ aviso.titulo }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ aviso.descripcion }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="px-2 d-flex flex-column">
                                        <div>
                                            {{ aviso.tipo }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <InputSwitch v-model="aviso.estado" />
                            </td>
                            <td class="align-middle">
                                <InputSwitch v-model="aviso.global" />
                            </td>
                            <td class="align-middle">
                                <InputSwitch v-model="aviso.principal" />
                            </td>

                            <td class="align-middle">
                                <Button @click="confirmarEliminacion($event, aviso.id)" icon="fi fi-br-trash"
                                    severity="danger" text rounded aria-label="Cancel" />

                            </td>
                        </tr>
                        <tr v-if="store.avisos.length === 0">
                            <td colspan="7" class="text-center">No hay datos</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                <nav>
                    <ul class="pagination">
                        <li class="page-item" :class="{
                    disabled: this.store.paginacion.paginaActual === 1,
                }">
                            <a class="page-link" href="#" @click="cambiarPaginacion(1)">
                                <Icon icon="material-symbols:keyboard-double-arrow-left" width="24" height="24" />
                            </a>
                        </li>
                        <li class="page-item" :class="{
                    disabled: this.store.paginacion.paginaActual === 1,
                }">
                            <a class="page-link" href="#" @click="
                    cambiarPaginacion(this.store.paginacion.paginaActual - 1)
                    ">
                                <Icon icon="iconamoon:arrow-left-2" width="24" height="24" />
                            </a>
                        </li>
                        <li class="page-item" v-for="page in this.store.paginacion.ultimaPagina" :key="page" :class="{
                    active: this.store.paginacion.paginaActual === page,
                }">
                            <a class="page-link" href="#" @click="cambiarPaginacion(page)">{{
                    page
                }}</a>
                        </li>
                        <li class="page-item" :class="{
                    disabled:
                        this.store.paginacion.paginaActual ===
                        this.store.paginacion.ultimaPagina,
                }">
                            <a class="page-link" href="#" @click="
                    cambiarPaginacion(this.store.paginacion.paginaActual + 1)
                    ">
                                <Icon icon="iconamoon:arrow-right-2" width="24" height="24" />
                            </a>
                        </li>
                        <li class="page-item" :class="{
                    disabled:
                        this.store.paginacion.paginaActual ===
                        this.store.paginacion.ultimaPagina,
                }">
                            <a class="page-link" href="#" @click="
                    cambiarPaginacion(this.store.paginacion.ultimaPagina)
          ">
                                <Icon icon="material-symbols:keyboard-double-arrow-right" width="24" height="24" />
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>


    <Toast />
    <ConfirmPopup></ConfirmPopup>

</template>

<script>
import { useDataAvisos } from '@/store/dataAvisos';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

export default {
    name: 'AvisosGestion',
    data() {
        return {
            busqueda: "",
        }
    },
    setup() {
        const store = useDataAvisos()
        const toast = useToast();
        const confirm = useConfirm();


        const confirmarEliminacion = (event, idAviso) => {
            confirm.require({
                target: event.currentTarget,
                message: "¿Quieres borrar el registro?",
                icon: "pi pi-info-circle",
                rejectClass: "p-button-secondary p-button-outlined p-button-sm me-5",
                acceptClass: "p-button-danger p-button-sm",
                rejectLabel: "Cancelar",
                acceptLabel: "Borrar",
                accept: () => {
                    store.eliminarAviso(idAviso).then(() => {
                        store.cargarAvisos(1, '')
                        toast.add({
                            severity: "success",
                            summary: "Confirmado",
                            detail: "Se borró el registro",
                            life: 3000,
                        });
                    });
                },
                reject: () => {
                    toast.add({
                        severity: "error",
                        summary: "Cancelado",
                        detail: "No se realizó la acción",
                        life: 3000,
                    });
                },
            });
        };


        return { store, confirmarEliminacion }
    },
    mounted() {
        this.store.cargarAvisos(1, this.busqueda)
    },
    methods: {
        filtrarAvisos() {
            this.store.cargarAvisos(1, this.busqueda)
        },
    }
}
</script>
