<!-- resources/js/Pages/Home.vue -->
<!--
    Esta é a versão oficial da página inicial, com um design mais elaborado
    e responsivo, utilizando o layout padrão da aplicação.
-->
<template>
    <AppLayout>
        <!-- Define o título da página para a aba do navegador. -->
        <Head title="Home" />

        <!--
            Contêiner principal da seção de destaque na home.
            Define largura máxima para desktop e centralização.
        -->
        <div class="mx-auto w-full max-w-[1280px]">
            <!--
                Seção de banner principal com imagem de fundo, responsividade e centralização de conteúdo.
                A classe `bg-[url('/images/home_slide/slide1.jpg')]` define a imagem de fundo.
                Certifique-se de que o arquivo `public/images/home_slide/slide1.jpg` exista.
            -->
            <section class="relative w-full min-h-[400px] sm:min-h-[420px] md:min-h-[550px] lg:min-h-[650px] xl:min-h-[750px] flex items-center justify-center bg-cover bg-no-repeat bg-center bg-[url('/images/home_slide/slide1.jpg')]">
                <!--
                    Máscara de gradiente sobre a imagem de fundo para melhorar a legibilidade do texto.
                    Possui uma animação de fade suave.
                -->
                <div class="absolute inset-0 mask-fade"></div>

                <!-- Conteúdo textual e botões, centralizados e com alto z-index para ficar acima da máscara. -->
                <div class="relative z-10 px-4 mx-auto max-w-screen-xl text-center flex flex-col justify-center items-center w-full">
                    <!-- Título principal da página, responsivo e com sombra para melhor visualização. -->
                    <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-snug text-white md:text-5xl lg:text-6xl drop-shadow-lg">
                        SEJA BEM VINDO(A) AO SITE DA ESCOLA P.E.I. PROFESSOR JOSÉ RANIERI!
                    </h1>
                    <!-- Parágrafo introdutório, com texto condicional baseado no status de autenticação. -->
                    <p class="mb-8 text-lg font-normal text-gray-200 lg:text-xl sm:px-10 lg:px-24 drop-shadow">
                        <template v-if="$page.props.auth.user">
                            Acesse seu painel!
                        </template>
                        <template v-else>
                            Já tem cadastro?
                        </template>
                    </p>
                    <!-- Contêiner para os botões de ação, responsivo (coluna em mobile, linha em desktop). -->
                    <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                        <template v-if="$page.props.auth.user">
                            <!-- Botão para o painel do usuário logado. -->
                            <Link
                                href="/dashboard"
                                class="inline-flex justify-center items-center py-3 px-6 text-xl font-semibold text-center rounded-full bg-laranja2 text-preto1 hover:bg-laranja1-hover focus:ring-4 focus:ring-laranja1-focus transition duration-300 ease-in-out transform hover:scale-105"
                            >
                                Meu Painel
                                <!-- Ícone de seta para indicar ação. -->
                                <svg class="w-4 h-4 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </Link>
                        </template>
                        <template v-else>
                            <!-- Botão de Login para usuários não autenticados. -->
                            <Link
                                href="/login"
                                class="inline-flex justify-center items-center py-3 px-6 text-xl font-semibold text-center rounded-full bg-laranja2 text-preto1 hover:bg-laranja1-hover focus:ring-4 focus:ring-laranja1-focus transition duration-300 ease-in-out transform hover:scale-105"
                            >
                                Entre!
                                <svg class="w-4 h-4 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </Link>
                            <!-- Botão de Registro para novos usuários. -->
                            <Link
                                href="/register"
                                class="inline-flex justify-center items-center py-3 px-6 sm:ms-4 text-xl font-semibold text-center rounded-full bg-roxo2 text-white border border-roxo2 hover:bg-roxo2-hover focus:ring-4 focus:ring-roxo2-focus transition duration-300 ease-in-out transform hover:scale-105"
                            >
                                Não? Faça aqui!
                            </Link>
                        </template>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue' // Importa o componente de layout principal.
import {Head, Link, usePage} from '@inertiajs/vue3' // Importa Head, Link e usePage do Inertia.js.
</script>

<style scoped>
/*
    Estilos para a máscara de fundo com gradiente animado.
    Cria um overlay semitransparente sobre a imagem de fundo do banner.
*/
.mask-fade {
    /* Define o gradiente de cores da máscara, de escuro para mais claro no topo. */
    background: linear-gradient(to top, rgba(17, 24, 39, 0.9), rgba(55, 65, 81, 0.7));
    /* Aplica uma animação para suavizar a transição da máscara. */
    animation: fadeMask 8s ease forwards;
}

/*
    Definição da animação 'fadeMask'.
    Altera a opacidade e as cores do gradiente ao longo do tempo.
*/
@keyframes fadeMask {
    from {
        background: linear-gradient(to top, rgba(17, 24, 39, 0.9), rgba(55, 65, 81, 0.7));
    }
    to {
        background: linear-gradient(to top, rgba(17, 24, 39, 0.6), rgba(55, 65, 81, 0.3));
    }
}
</style>
