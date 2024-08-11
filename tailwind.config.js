import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    plugins: [require("tailwindcss-primeui")],
    content: ["./resources/**/*.blade.php", "./resources/js/**/*.vue"],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                urbanist: ["Urbanist", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
