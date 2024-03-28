<template>
    <div>
        template de avisos
    </div>
</template>

<script>
import { useDataAvisos } from '@/store/dataAvisos';

export default {
    name: "AvisosIndex",
    setup() {
        const store = useDataAvisos()

        return { store }
    },
    mounted() {
        this.store.obtenerAvisosGlobales()
    },
}
</script>
