<!-- resources/js/Layouts/AppLayout.vue -->
<!--
    Este é o componente de layout principal para a aplicação.
    Ele define a estrutura visual comum para todas as páginas,
    incluindo o cabeçalho, barra de navegação, rodapé e um slot para o conteúdo da página.
-->
<template>
    <!-- Contêiner principal com um fundo animado de gradiente e que ocupa a altura mínima da tela -->
    <div class="flex flex-col min-h-screen animated-bg">
        <!--
            Componente de cabeçalho, visível apenas em telas maiores (desktop).
            Contém o logo principal da escola.
        -->
        <div class="hidden md:block">
            <Header />
        </div>

        <!--
            Componente da barra de navegação, sempre visível.
            Inclui links de navegação e o menu de usuário/login.
        -->
        <div>
            <Navbar />
        </div>

        <!--
            Seção principal do conteúdo.
            Ocupa o espaço restante verticalmente e centraliza o conteúdo.
        -->
        <main class="flex-grow flex justify-center px-4">
            <!--
                Contêiner interno para o conteúdo da página, com estilo de cartão.
                Possui fundo branco semi-transparente, cantos arredondados, sombra e largura máxima.
            -->
            <div class="w-full bg-white bg-opacity-90 rounded-2xl shadow-2xl m-5 p-6 max-w-6xl dark:bg-gray-800 dark:bg-opacity-90">
                <!--
                    Slot para o título da página.
                    Aparece apenas se a página filha fornecer conteúdo para este slot.
                -->
                <div v-if="$slots.title" class="mb-6 border-b pb-4 text-3xl font-bold text-center text-laranja2 dark:text-gray-200">
                    <slot name="title" />
                </div>

                <!-- Slot principal onde o conteúdo da página atual será renderizado. -->
                <slot />
            </div>
        </main>

        <!-- Componente de rodapé, que contém informações de contato e redes sociais. -->
        <div>
            <Footer />
        </div>
    </div>
</template>

<script setup>
import Header from '@/Components/Layout/Header.vue';
import Navbar from '@/Components/Layout/Navbar.vue';
import Footer from '@/Components/Layout/Footer.vue';
</script>

<style scoped>
/*
    Estilos para o fundo animado de gradiente.
    Define um gradiente de cores que se move suavemente,
    criando um efeito visual dinâmico e moderno.
*/
.animated-bg {
    background: linear-gradient(
        270deg,
        #c89c20, /* laranja 1 */
        #cd862a, /* laranja 2 */
        #c9583e, /* laranja 3 */
        #61152d, /* roxo 1 */
        #681630  /* roxo 2 */
    );
    background-size: 1000% 1000%; /* Garante que o gradiente seja grande o suficiente para a animação */
    animation: bgGradientMove 30s ease infinite; /* Aplica a animação ao gradiente */
}

/*
    Definição da animação 'bgGradientMove'.
    Faz com que a posição do fundo do gradiente se mova da esquerda para a direita e vice-versa.
*/
@keyframes bgGradientMove {
    0% {
        background-position: 0% 50%; /* Posição inicial do gradiente */
    }
    50% {
        background-position: 100% 50%; /* Posição intermediária (deslocada para a direita) */
    }
    100% {
        background-position: 0% 50%; /* Retorna à posição inicial, criando um loop suave */
    }
}
</style>
