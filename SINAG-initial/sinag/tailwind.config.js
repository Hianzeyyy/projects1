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
                sans: ['Figtree', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                sinagblue: {
                    DEFAULT: '#1A237E',
                    50: '#e8eaf6',
                    100: '#c5cae9',
                    200: '#9fa8da',
                    300: '#7986cb',
                    400: '#5c6bc0',
                    500: '#3949ab',
                    600: '#303f9f',
                    700: '#283593',
                    800: '#1A237E',
                },
                sinagpurple: '#6d28d9',
                sinagyellow: '#fbbf24',
                sinaggray: '#6b7280',
            },
            borderRadius: {
                'xl': '1.25rem',
                '2xl': '2rem',
            },
        },
    },

    plugins: [forms],
};