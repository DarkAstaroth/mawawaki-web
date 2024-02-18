import { defineStore } from "pinia";
import axios from "axios";

export const useDataAutenticacion = defineStore("dataAutenticacion", {
    state: () => ({}),
    persist: true,
    actions: {
        async loginUsuario(email, password) {
            // console.log("entrando al login");
            axios.post("/api/verificar-login", { email, password });
        },
    },
});
