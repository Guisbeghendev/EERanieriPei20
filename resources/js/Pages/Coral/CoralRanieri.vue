<!-- resources/js/Pages/Coral/CoralRanieri.vue -->
<!--
    Página do "Coral Ranieri".
    Exibe a história do coral dividida em capítulos, navegável por botões.
    O conteúdo de cada capítulo é carregado dinamicamente de arquivos JS.
-->
<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue'; // Layout principal da aplicação
import { Head } from '@inertiajs/vue3';        // Para definir o título da página

const currentPage = ref(0); // Estado reativo para controlar o capítulo atual (índice)
const chapters = ref([]);   // Estado reativo para armazenar a lista de capítulos carregados

/**
 * Propriedade computada que retorna o objeto do capítulo atual.
 * Inclui um fallback para exibir uma mensagem de carregamento ou erro amigável.
 */
const currentChapter = computed(() => {
    // Verifica se a lista de capítulos existe, tem elementos e o índice atual é válido.
    if (chapters.value && chapters.value.length > 0 && currentPage.value < chapters.value.length) {
        return chapters.value[currentPage.value];
    }
    // Fallback padrão se os capítulos ainda não foram carregados ou se há um problema.
    return { title: 'Carregando História...', content: '<p class="text-center text-gray-500 dark:text-gray-400 italic">Aguarde enquanto carregamos os capítulos da história. Se a página não carregar, por favor, tente recarregar.</p>' };
});

/**
 * Propriedade computada que retorna o número total de capítulos.
 * Garante que a contagem seja 0 se 'chapters.value' for nulo ou vazio.
 */
const totalChapters = computed(() => chapters.value?.length || 0);

/** Propriedade computada para verificar se há um capítulo anterior disponível. */
const hasPrevious = computed(() => currentPage.value > 0);
/** Propriedade computada para verificar se há um capítulo seguinte disponível. */
const hasNext = computed(() => currentPage.value < totalChapters.value - 1);

/**
 * Função assíncrona para carregar os capítulos dinamicamente de arquivos JS.
 * Utiliza `import.meta.glob` para buscar todos os arquivos `.js` dentro da pasta `Capitulos`.
 */
const loadChapters = async () => {
    console.log('[CoralRanieri.vue] Iniciando carregamento de capítulos dinamicamente...');
    // Busca todos os módulos JS dentro da pasta 'Capitulos' (relativa ao componente atual).
    const modules = import.meta.glob('./Capitulos/*.js');

    const loaded = []; // Array temporário para armazenar os capítulos carregados.
    // Itera sobre os caminhos de todos os módulos encontrados.
    for (const path in modules) {
        try {
            // Importa o módulo e aguarda a resolução.
            const module = await modules[path]();
            // Verifica se o módulo e seu 'default export' são objetos válidos.
            if (module && typeof module.default === 'object' && module.default !== null) {
                loaded.push(module.default); // Adiciona o conteúdo padrão do módulo.
                console.log(`[CoralRanieri.vue] Capítulo carregado dinamicamente: ${path}`);
            } else {
                console.warn(`[CoralRanieri.vue] Pulando módulo de capítulo malformado ou vazio: ${path}`);
            }
        } catch (e) {
            console.error(`[CoralRanieri.vue] Erro ao carregar o módulo ${path}:`, e);
        }
    }

    if (loaded.length > 0) {
        // Ordena os capítulos numericamente com base no "Capítulo X:" do título.
        // Isso garante que os capítulos sejam exibidos na ordem correta (Capítulo 1, Capítulo 2, etc.).
        loaded.sort((a, b) => {
            const numA = parseInt(a.title.match(/Capítulo (\d+)/)?.[1] || 0); // Extrai o número do título.
            const numB = parseInt(b.title.match(/Capítulo (\d+)/)?.[1] || 0);
            return numA - numB; // Ordena crescentemente.
        });
    }

    chapters.value = loaded; // Atualiza a ref reativa 'chapters' com os capítulos ordenados.
    console.log('[CoralRanieri.vue] Número total de capítulos carregados e ordenados:', chapters.value.length);
};

// Hook de ciclo de vida: executa 'loadChapters' assim que o componente é montado no DOM.
onMounted(() => {
    loadChapters();
});

// Funções de navegação (inalteradas)
const goToPrevious = () => {
    if (hasPrevious.value) {
        currentPage.value--;
    }
};

const goToNext = () => {
    if (hasNext.value) {
        currentPage.value++;
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Coral Ranieri" />

        <template #title>
            <h1 class="text-roxo2 dark:text-roxo2-hover font-extrabold tracking-tight text-4xl lg:text-5xl drop-shadow-md">
                Coral Ranieri
            </h1>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-2xl shadow-xl p-8 md:p-10 lg:p-12 border border-gray-100 dark:border-gray-700">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-roxo2 dark:text-roxo2-hover mb-6 leading-tight text-center drop-shadow-sm">
                    {{ currentChapter.title }}
                </h2>

                <!--
                    Área para renderizar o conteúdo HTML do capítulo.
                    Ajustei para as classes 'prose' e 'dark:prose-invert' estarem aqui,
                    se não estavam antes e causaram o erro, é importante reavaliar o setup do Tailwind.
                    Mas, se estava funcionando, manterei como estava no seu último envio antes de minhas sugestões.
                -->
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg mb-8" v-html="currentChapter.content"></div>

                <div class="flex flex-col sm:flex-row justify-between items-center mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 gap-4">
                    <!-- Botão "Anterior" -->
                    <button
                        @click="goToPrevious"
                        :disabled="!hasPrevious"
                        class="px-8 py-3 w-full sm:w-auto
                               bg-roxo2 text-white font-bold rounded-full
                               shadow-lg hover:bg-roxo1-hover
                               disabled:bg-gray-300 dark:disabled:bg-gray-700
                               disabled:text-gray-500 dark:disabled:text-gray-400
                               disabled:cursor-not-allowed
                               transition duration-300 ease-in-out transform hover:scale-105 active:scale-95
                               focus:outline-none focus:ring-2 focus:ring-roxo2-focus focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                    >
                        Anterior
                    </button>

                    <!-- Indicador de capítulo atual. -->
                    <span class="text-gray-600 dark:text-gray-400 text-base sm:text-lg font-semibold whitespace-nowrap">
                        Capítulo {{ currentPage + 1 }} de {{ totalChapters }}
                    </span>

                    <!-- Botão "Próximo" -->
                    <button
                        @click="goToNext"
                        :disabled="!hasNext"
                        class="px-8 py-3 w-full sm:w-auto
                               bg-laranja1 text-preto1 font-bold rounded-full
                               shadow-lg hover:bg-laranja1-hover
                               disabled:bg-gray-300 dark:disabled:bg-gray-700
                               disabled:text-gray-500 dark:disabled:text-gray-400
                               disabled:cursor-not-allowed
                               transition duration-300 ease-in-out transform hover:scale-105 active:scale-95
                               focus:outline-none focus:ring-2 focus:ring-laranja1-focus focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                    >
                        Próximo
                    </button>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Adicione estilos específicos para esta página se necessário */
</style>
