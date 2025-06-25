<!-- resources/js/Pages/Auth/ResetPassword.vue -->
<!--
    Página de Redefinição de Senha.
    Esta página é exibida quando um usuário clica no link de redefinição de senha
    enviado para seu e-mail, permitindo-lhe definir uma nova senha.
-->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';      // Layout principal da aplicação
import InputError from '@/Components/InputError.vue';  // Exibição de erros de validação
import InputLabel from '@/Components/InputLabel.vue';  // Rótulo para campos de formulário
import PrimaryButton from '@/Components/PrimaryButton.vue'; // Botão principal de ação
import TextInput from '@/Components/TextInput.vue';    // Campo de entrada de texto/email/senha
import { Head, useForm } from '@inertiajs/vue3';     // Funções do Inertia.js para controle de página e formulários

// Define as props que esta página pode receber: email do usuário e o token de redefinição.
const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

// Inicializa o formulário com o token, email, e os campos para a nova senha e confirmação.
const form = useForm({
    token: props.token,             // O token de redefinição, passado como prop.
    email: props.email,             // O e-mail do usuário, passado como prop.
    password: '',                   // Campo para a nova senha.
    password_confirmation: '',      // Campo para a confirmação da nova senha.
});

// Função para submeter o formulário de redefinição de senha.
const submit = () => {
    // Envia a requisição POST para a rota padrão do Breeze para armazenar a nova senha.
    // A rota '/reset-password' é onde o backend processa a redefinição.
    form.post('/reset-password', {
        // Callback executado após a conclusão da requisição.
        // Reseta os campos de senha para segurança.
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AppLayout>
        <!-- Define o título da página na aba do navegador. -->
        <Head title="Nova Senha" />

        <!-- Slot para o título na seção principal do AppLayout. -->
        <template #title>
            <h1 class="text-roxo2 dark:text-roxo2-hover font-extrabold tracking-tight text-4xl lg:text-5xl drop-shadow-md">
                Redefinir Sua Senha
            </h1>
        </template>

        <!-- Contêiner centralizado para o formulário, com um design de cartão dividido. -->
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-5xl flex flex-col lg:flex-row bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <!-- Seção de informações com gradiente de fundo. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center bg-gradient-to-br from-laranja1 via-laranja2 to-roxo2 text-white p-10 py-12 lg:py-0">
                    <div class="text-center space-y-6">
                        <h2 class="text-4xl lg:text-5xl font-bold mb-4 drop-shadow-md">Crie uma Nova Senha</h2>
                        <p class="text-lg lg:text-xl text-prata1 opacity-90">
                            Sua segurança é importante! Digite e confirme sua nova senha para acessar sua conta.
                        </p>
                        <div class="mt-8">
                            <!-- Imagem do logo no painel de informações. -->
                            <img src="/images/logo/prof_ranieri - fundo branco.png" alt="Logo Ranieri" class="h-28 w-auto mx-auto drop-shadow-lg filter brightness-110" />
                        </div>
                    </div>
                </div>

                <!-- Seção do formulário de redefinição. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-12 lg:p-16">
                    <div class="w-full max-w-md">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Campo: Endereço de E-mail (preenchido automaticamente via prop) -->
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

                            <!-- Campo: Nova Senha -->
                            <div>
                                <InputLabel for="password" value="Nova Senha" class="text-gray-800 dark:text-gray-200" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-roxo1 focus:ring-roxo1 rounded-md shadow-sm"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-2 text-sm text-red-600 dark:text-red-400" :message="form.errors.password" />
                            </div>

                            <!-- Campo: Confirmar Nova Senha -->
                            <div>
                                <InputLabel for="password_confirmation" value="Confirmar Nova Senha" class="text-gray-800 dark:text-gray-200" />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-roxo1 focus:ring-roxo1 rounded-md shadow-sm"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-2 text-sm text-red-600 dark:text-red-400" :message="form.errors.password_confirmation" />
                            </div>

                            <!-- Botão de submissão do formulário. Desabilitado durante o processamento. -->
                            <div class="mt-6 flex justify-end">
                                <PrimaryButton
                                    class="px-6 py-3 bg-roxo1 hover:bg-roxo1-hover text-prata1 font-semibold rounded-lg shadow-md transition-all duration-300 ease-in-out transform hover:scale-105"
                                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Redefinir Senha
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
