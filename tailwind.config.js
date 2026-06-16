import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                display: ['Playfair Display', ...defaultTheme.fontFamily.serif],
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                warm: {
                    50: '#faf6f1',
                    100: '#f5ede3',
                    200: '#ede0d0',
                    300: '#dcc4a7',
                    400: '#c9a57a',
                    500: '#bc8f5c',
                    600: '#a67748',
                    700: '#8b5e3d',
                    800: '#744d36',
                    900: '#604030',
                    950: '#342119',
                },
                brand: {
                    50: '#fdf4f0',
                    100: '#fae6dc',
                    200: '#f4ccb8',
                    300: '#eba98a',
                    400: '#e0805a',
                    500: '#d86439',
                    600: '#c9644a',
                    700: '#a84a34',
                    800: '#893e2e',
                    900: '#6f372b',
                    950: '#3c1a14',
                },
                slate: {
                    50: '#f0f3f5',
                    100: '#d9e0e5',
                    200: '#b7c4cc',
                    300: '#8ba0ae',
                    400: '#637d8f',
                    500: '#3b5c6f',
                    600: '#2f4d5e',
                    700: '#273f4e',
                    800: '#233642',
                    900: '#1f2e39',
                    950: '#141f27',
                },
            },
            animation: {
                'fade-in': 'fadeIn 0.6s ease-out forwards',
                'slide-up': 'slideUp 0.5s ease-out forwards',
                'slide-down': 'slideDown 0.3s ease-out forwards',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideDown: {
                    '0%': { opacity: '0', transform: 'translateY(-8px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    },
    plugins: [forms, typography],
};
