import { defineStore } from "pinia";
import axios from "axios";

export const useDataAutenticacion = defineStore("dataAutenticacion", {
    state: () => ({}),
    persist: true,
    actions: {
        async loginUsuario(email, password) {
            axios.post("/api/verificar-login", { email, password });
        },
    },
});
