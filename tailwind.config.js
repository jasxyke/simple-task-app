/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/views/**/*.blade.php", "./resources/js/**/*.vue"],
    theme: {
        extend: {
            colors: {
                primary: "#F1F0E9",
                secondary: "#E9762B",
                dimGreen: "#41644A",
                darkGreen: "#0D4715",
            },
            fontFamily: {
                sans: [
                    "Instrument Sans",
                    "ui-sans-serif",
                    "system-ui",
                    "sans-serif",
                ],
            },
        },
    },
    plugins: [],
};
