<!-- resources/js/Pages/Auth/ForgotPassword.vue -->
<!--
    Página de Recuperação de Senha.
    Permite que usuários solicitem um link para redefinir sua senha caso a tenham esquecido.
-->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';      // Layout principal da aplicação
import InputError from '@/Components/InputError.vue';  // Exibição de erros de validação
import InputLabel from '@/Components/InputLabel.vue';  // Rótulo para campos de formulário
import PrimaryButton from '@/Components/PrimaryButton.vue'; // Botão principal de ação
import TextInput from '@/Components/TextInput.vue';    // Campo de entrada de texto/email/senha
import { Head, useForm } from '@inertiajs/vue3';     // Funções do Inertia.js para controle de página e formulários

// Define as props que esta página pode receber, como o status de sessão (ex: mensagem de sucesso).
defineProps({
    status: {
        type: String,
    },
});

// Inicializa o formulário com o campo de e-mail.
const form = useForm({
    email: '',
});

// Função para submeter o formulário de solicitação de redefinição de senha.
const submit = () => {
    // Envia a requisição POST para a rota padrão do Breeze para envio de link de redefinição.
    form.post('/forgot-password');
};
</script>

<template>
    <AppLayout>
        <!-- Define o título da página na aba do navegador. -->
        <Head title="Esqueci minha senha" />

        <!-- Slot para o título na seção principal do AppLayout. -->
        <template #title>
            <h1 class="text-roxo2 dark:text-roxo2-hover font-extrabold tracking-tight text-4xl lg:text-5xl drop-shadow-md">
                Recuperação de Senha
            </h1>
        </template>

        <!-- Contêiner centralizado para o formulário, com um design de cartão dividido. -->
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-5xl flex flex-col lg:flex-row bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <!-- Seção de informações com gradiente de fundo. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center bg-gradient-to-br from-laranja1 via-laranja2 to-roxo2 text-white p-10 py-12 lg:py-0">
                    <div class="text-center space-y-6">
                        <h2 class="text-4xl lg:text-5xl font-bold mb-4 drop-shadow-md">Recupere Seu Acesso</h2>
                        <p class="text-lg lg:text-xl text-prata1 opacity-90">
                            Não se preocupe! Informe seu e-mail e enviaremos um link para você redefinir sua senha.
                        </p>
                        <div class="mt-8">
                            <!-- Imagem do logo no painel de informações. -->
                            <img src="/images/logo/prof_ranieri - fundo branco.png" alt="Logo Ranieri" class="h-28 w-auto mx-auto drop-shadow-lg filter brightness-110" />
                        </div>
                    </div>
                </div>

                <!-- Seção do formulário de recuperação de senha. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-12 lg:p-16">
                    <div class="w-full max-w-md">
                        <!-- Exibe mensagens de status (ex: "Link de redefinição enviado!"). -->
                        <div v-if="status" class="mb-6 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm font-medium border border-green-200 dark:border-green-800">
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Campo: Endereço de E-mail -->
                            <div>
                                <InputLabel for="email" value="Endereço de E-mail" class="text-gray-800 dark:text-gray-200" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-roxo1 focus:ring-roxo1 rounded-md shadow-sm"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                                <InputError class="mt-2 text-sm text-red-600 dark:text-red-400" :message="form.errors.email" />
                            </div>

                            <!-- Botão de submissão do formulário. Desabilitado durante o processamento. -->
                            <div class="mt-6 flex justify-end">
                                <PrimaryButton
                                    class="px-6 py-3 bg-roxo1 hover:bg-roxo1-hover text-prata1 font-semibold rounded-lg shadow-md transition-all duration-300 ease-in-out transform hover:scale-105"
                                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Enviar Link para Redefinição
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
