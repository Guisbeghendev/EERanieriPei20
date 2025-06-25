// Configura a aplicação Vue com Inertia.js, integra Flowbite e adiciona tratamento de erros.

// Importa o CSS principal da aplicação.
import '../css/app.css';

// Importa o arquivo de bootstrap (que configura Axios e o token CSRF).
import './bootstrap';

// Importa a biblioteca Flowbite para componentes de UI interativos.
// Certifica-se de que Flowbite.js é carregado e inicializado.
import 'flowbite';

// Importa as funções essenciais do Inertia.js e Vue.
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';

// Define o nome da aplicação, usando a variável de ambiente Vite ou um padrão.
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Cria a aplicação Inertia.js.
createInertiaApp({
    // Define o título da página no navegador, combinando o título da página Vue com o nome da aplicação.
    title: (title) => `${title} - ${appName}`,

    // Resolve dinamicamente os componentes de página Vue.
    // Isso permite que o Inertia encontre e carregue as páginas Vue na pasta 'Pages'.
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),

    // Configura e monta a aplicação Vue no elemento raiz ('el').
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            // .use(ZiggyVue, Ziggy) // REMOVIDO: O Ziggy não será usado neste projeto, conforme Módulo 1.
            .mount(el);
    },

    // Configura a barra de progresso do Inertia (exibida durante navegações).
    progress: {
        color: '#4B5563', // Cor da barra de progresso.
    },

    // Tratamento global de erros para requisições Inertia.js.
    // Especialmente útil para lidar com erros de sessão/CSRF (419) e não autorizado (401).
    onError: (error) => {
        // Se o erro for 419 (Page Expired) ou 401 (Unauthorized),
        // recarrega a página atual para obter um novo token CSRF e recomeçar a sessão/autenticação.
        if (error.response && (error.response.status === 419 || error.response.status === 401)) {
            console.error('Erro de sessão/CSRF (419/401 detectado). Recarregando a página para renovar a sessão...');
            window.location.reload(); // Força o recarregamento completo da página.
            return false; // Previne que o Inertia continue processando este erro.
        }
        // Para outros erros não relacionados a sessão/CSRF, apenas loga no console.
        console.error('Erro Inertia inesperado:', error);
    },
});
