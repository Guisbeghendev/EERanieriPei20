// Configurações do Tailwind CSS, incluindo plugins e a paleta de cores personalizada.

import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import flowbite from 'flowbite/plugin'; // Importa o plugin Flowbite para integração.

/** @type {import('tailwindcss').Config} */
export default {
    // Define os arquivos onde o Tailwind deve escanear por classes CSS.
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './node_modules/flowbite/**/*.js', // Importante: Inclui os arquivos JS do Flowbite para que suas classes sejam processadas.
    ],

    theme: {
        extend: {
            // Define a fonte padrão, usando Figtree e as fontes sans-serif padrão do sistema.
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // Define a paleta de cores personalizada do projeto.
            colors: {
                roxo1: '#61152d',
                roxo2: '#681630',
                preto1: '#0f0e0f',
                laranja1: '#c89c20',
                laranja2: '#cd862a',
                laranja3: '#c9583e',
                verde1: '#2e5935',
                azul1: '#1b365d',
                amarelo1: '#c5a100',
                vermelho1: '#9e1b1b',
                prata1: '#d1d1d1',
                branco1: '#f8f8f8',
                rosa1: '#a54161',
                dourado1: '#bfa14f',
                cinza1: '#8a8a8a',
                // Variações de Hover e Focus para as cores principais (adicionar aqui se forem usadas em classes customizadas)
                'roxo1-hover': '#7a1939',
                'roxo2-hover': '#7a1939',
                'roxo2-focus': '#9c2049',
                'laranja1-hover': '#e0b223',
                'laranja2-hover': '#e6a032',
                'laranja1-focus': '#f3c734',
            },
        },
    },

    // Adiciona os plugins do Tailwind CSS.
    plugins: [
        forms,      // Plugin para estilos de formulário padrão.
        flowbite,   // Plugin Flowbite para adicionar classes e funcionalidades.
    ],
};
