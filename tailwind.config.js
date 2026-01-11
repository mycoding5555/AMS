import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['SF Pro Display', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'apple-bg': '#F5F5F7',
                'apple-text': '#1D1D1F',
                'apple-secondary': '#86868B',
                'apple-border': '#D5D5D7',
                'apple-blue': '#0071E3',
                'apple-blue-hover': '#0077ED',
            },
            spacing: {
                'safe': '1.5rem',
                'section': '3rem',
            },
            borderRadius: {
                'apple': '12px',
                'apple-lg': '20px',
            },
            boxShadow: {
                'apple-sm': '0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24)',
                'apple-md': '0 3px 6px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.12)',
            },
        },
    },

    plugins: [forms],
};
