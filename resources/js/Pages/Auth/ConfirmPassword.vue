<!-- resources/js/Pages/Auth/ConfirmPassword.vue -->
<!--
    Página de Confirmação de Senha.
    Esta página é exibida quando o middleware 'password.confirm' é ativado,
    solicitando que o usuário confirme sua senha antes de acessar áreas sensíveis
    do sistema (ex: edição de perfil, configurações de segurança).
-->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';      // Layout principal da aplicação
import InputError from '@/Components/InputError.vue';  // Exibição de erros de validação
import InputLabel from '@/Components/InputLabel.vue';  // Rótulo para campos de formulário
import PrimaryButton from '@/Components/PrimaryButton.vue'; // Botão principal de ação
import TextInput from '@/Components/TextInput.vue';    // Campo de entrada de texto/email/senha
import { Head, useForm } from '@inertiajs/vue3';     // Funções do Inertia.js para controle de página e formulários

// Inicializa o formulário com o campo de senha.
const form = useForm({
    password: '',
});

// Função para submeter o formulário de confirmação de senha.
const submit = () => {
    // Envia a requisição POST para a rota padrão do Breeze para confirmação de senha.
    // A rota '/user/confirm-password' é usada quando o usuário tenta acessar uma área protegida.
    form.post('/user/confirm-password', {
        // Callback executado após a conclusão da requisição (sucesso ou falha).
        // Reseta o campo de senha.
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <AppLayout>
        <!-- Define o título da página na aba do navegador. -->
        <Head title="Confirmar Senha" />

        <!-- Slot para o título na seção principal do AppLayout. -->
        <template #title>
            <h1 class="text-roxo2 dark:text-roxo2-hover font-extrabold tracking-tight text-4xl lg:text-5xl drop-shadow-md">
                Confirmação de Senha
            </h1>
        </template>

        <!-- Contêiner centralizado para o formulário, com um design de cartão dividido. -->
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-5xl flex flex-col lg:flex-row bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <!-- Seção de informações com gradiente de fundo. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center bg-gradient-to-br from-laranja1 via-laranja2 to-roxo2 text-white p-10 py-12 lg:py-0">
                    <div class="text-center space-y-6">
                        <h2 class="text-4xl lg:text-5xl font-bold mb-4 drop-shadow-md">Área Segura</h2>
                        <p class="text-lg lg:text-xl text-prata1 opacity-90">
                            Para sua segurança, por favor, confirme sua senha antes de continuar.
                        </p>
                        <div class="mt-8">
                            <!-- Imagem do logo no painel de informações. -->
                            <img src="/images/logo/prof_ranieri - fundo branco.png" alt="Logo Ranieri" class="h-28 w-auto mx-auto drop-shadow-lg filter brightness-110" />
                        </div>
                    </div>
                </div>

                <!-- Seção do formulário de confirmação. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-12 lg:p-16">
                    <div class="w-full max-w-md">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Campo: Senha -->
                            <div>
                                <InputLabel for="password" value="Senha" class="text-gray-800 dark:text-gray-200" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-roxo1 focus:ring-roxo1 rounded-md shadow-sm"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    autofocus
                                />
                                <InputError class="mt-2 text-sm text-red-600 dark:text-red-400" :message="form.errors.password" />
                            </div>

                            <!-- Botão de submissão do formulário. Desabilitado durante o processamento. -->
                            <div class="mt-6 flex justify-end">
                                <PrimaryButton
                                    class="px-6 py-3 bg-roxo1 hover:bg-roxo1-hover text-prata1 font-semibold rounded-lg shadow-md transition-all duration-300 ease-in-out transform hover:scale-105"
                                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Confirmar
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
