<!-- resources/js/Pages/Auth/VerifyEmail.vue -->
<!--
    Página de Verificação de E-mail.
    Exibida para usuários que se registraram, mas ainda não verificaram seu e-mail.
    Permite reenviar o link de verificação ou sair.
-->
<script setup>
import { computed } from 'vue';               // Importa 'computed' para criar propriedades reativas.
import AppLayout from '@/Layouts/AppLayout.vue';      // Layout principal da aplicação.
import PrimaryButton from '@/Components/PrimaryButton.vue'; // Botão principal de ação.
import { Head, Link, useForm } from '@inertiajs/vue3';     // Funções do Inertia.js.

// Define as props que esta página pode receber, como o status (se o link foi enviado).
const props = defineProps({
    status: {
        type: String,
    },
});

// Inicializa um formulário vazio, pois apenas uma requisição POST simples é necessária.
const form = useForm({});

// Função para submeter o formulário e reenviar o e-mail de verificação.
const submit = () => {
    // Envia a requisição POST para a rota padrão do Breeze para reenviar o e-mail de verificação.
    form.post('/email/verification-notification');
};

// Propriedade computada para verificar se o status indica que o link de verificação foi enviado.
const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <AppLayout>
        <!-- Define o título da página na aba do navegador. -->
        <Head title="Confirme seu E-mail" />

        <!-- Slot para o título na seção principal do AppLayout. -->
        <template #title>
            <h1 class="text-roxo2 dark:text-roxo2-hover font-extrabold tracking-tight text-4xl lg:text-5xl drop-shadow-md">
                Verifique Seu E-mail
            </h1>
        </template>

        <!-- Contêiner centralizado para o conteúdo, com um design de cartão dividido. -->
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-5xl flex flex-col lg:flex-row bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <!-- Seção de informações com gradiente de fundo. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center bg-gradient-to-br from-laranja1 via-laranja2 to-roxo2 text-white p-10 py-12 lg:py-0">
                    <div class="text-center space-y-6">
                        <h2 class="text-4xl lg:text-5xl font-bold mb-4 drop-shadow-md">Confirme Seu Endereço de E-mail</h2>
                        <p class="text-lg lg:text-xl text-prata1 opacity-90">
                            Para sua segurança, clique no link de verificação que enviamos para o seu e-mail. Se não o encontrou, podemos reenviar!
                        </p>
                        <div class="mt-8">
                            <!-- Imagem do logo no painel de informações. -->
                            <img src="/images/logo/prof_ranieri - fundo branco.png" alt="Logo Ranieri" class="h-28 w-auto mx-auto drop-shadow-lg filter brightness-110" />
                        </div>
                    </div>
                </div>

                <!-- Seção de instruções e botões de ação. -->
                <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 md:p-12 lg:p-16">
                    <div class="w-full max-w-md space-y-6">
                        <!-- Mensagem de sucesso quando o link de verificação é enviado. -->
                        <div v-if="verificationLinkSent" class="mb-6 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm font-medium border border-green-200 dark:border-green-800">
                            Um novo link de verificação foi enviado para o e-mail cadastrado.
                        </div>

                        <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed text-center">
                            Agradecemos por se cadastrar! Antes de continuar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você? Se você não recebeu o e-mail, teremos o prazer de lhe enviar outro.
                        </p>

                        <!-- Formulário com botões para reenviar o e-mail ou sair. -->
                        <form @submit.prevent="submit" class="flex flex-col sm:flex-row items-center justify-between mt-6 space-y-4 sm:space-y-0 sm:space-x-4">
                            <!-- Botão para reenviar o e-mail de verificação. -->
                            <PrimaryButton
                                class="w-full sm:w-auto px-6 py-3 bg-roxo1 hover:bg-roxo1-hover text-prata1 font-semibold rounded-lg shadow-md transition-all duration-300 ease-in-out transform hover:scale-105"
                                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                :disabled="form.processing"
                            >
                                Reenviar E-mail de Verificação
                            </PrimaryButton>

                            <!-- Link/Botão para sair da sessão. -->
                            <Link
                                :href="'/logout'"
                                method="post"
                                as="button"
                                class="w-full sm:w-auto text-sm text-gray-600 dark:text-gray-300 underline hover:text-roxo2 dark:hover:text-laranja2 transition-colors duration-200 text-center"
                            >
                                Sair
                            </Link>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
