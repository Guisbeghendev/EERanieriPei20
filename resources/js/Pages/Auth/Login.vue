<!-- resources/js/Pages/Auth/Login.vue -->
<!--
    Página de Login no Sistema.
    Permite que usuários existentes acessem suas contas.
    Inclui opções para "Lembrar-me" e redefinição de senha.
-->
<script setup>
import Checkbox from '@/Components/Checkbox.vue';       // Componente de checkbox
import AppLayout from '@/Layouts/AppLayout.vue';       // Layout principal da aplicação
import InputError from '@/Components/InputError.vue';   // Exibição de erros de validação
import InputLabel from '@/Components/InputLabel.vue';   // Rótulo para campos de formulário
import PrimaryButton from '@/Components/PrimaryButton.vue'; // Botão principal de ação
import TextInput from '@/Components/TextInput.vue';     // Campo de entrada de texto/email/senha
import { Head, Link, useForm } from '@inertiajs/vue3';  // Funções do Inertia.js para controle de página e formulários

// Define as props que esta página pode receber, como canResetPassword e status de sessão.
defineProps({
    canResetPassword: {
        type: Boolean,
        default: false, // Adicionado um valor default para segurança, caso a prop não seja passada.
    },
    status: {
        type: String,   // Mensagens de status da sessão (ex: "Sua senha foi redefinida!").
    },
});

// Inicializa o formulário de login com os campos necessários.
const form = useForm({
    email: '',
    password: '',
    remember: false, // Controla se o usuário deve ser lembrado (cookie de sessão mais longo).
});

// Função para submeter o formulário de login.
const submit = () => {
    // Envia a requisição POST para a rota '/login'.
    // A Inertia.js lida automaticamente com o token CSRF.
    form.post('/login', {
        // Callback executado após a conclusão da requisição.
        // Reseta o campo de senha para segurança.
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AppLayout>
        <!-- Define o título da página na aba do navegador. -->
        <Head title="Login" />

        <!-- Slot para o título na seção principal do AppLayout. -->
        <template #title>
            <h1 class="text-roxo2 dark:text-roxo2-hover font-extrabold tracking-tight text-4xl lg:text-5xl drop-shadow-md">
                Login no Sistema
            </h1>
        </template>

        <!-- Contêiner centralizado para o formulário de login, com um design de cartão dividido. -->
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-5xl flex flex-col lg:flex-row bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <!-- Seção de boas-vindas e logo, com gradiente de fundo. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center bg-gradient-to-br from-laranja1 via-laranja2 to-roxo2 text-white p-10 py-12 lg:py-0">
                    <div class="text-center space-y-6">
                        <h2 class="text-4xl lg:text-5xl font-bold mb-4 drop-shadow-md">Bem-vindo(a) de volta!</h2>
                        <p class="text-lg lg:text-xl text-prata1 opacity-90">
                            Acesse sua conta para continuar gerenciando o conteúdo.
                        </p>
                        <div class="mt-8">
                            <!--
                                Imagem do logo no painel de boas-vindas.
                                Atenção ao nome do arquivo com espaços.
                            -->
                            <img src="/images/logo/prof_ranieri - fundo branco.png" alt="Logo Ranieri" class="h-28 w-auto mx-auto drop-shadow-lg filter brightness-110" />
                        </div>
                    </div>
                </div>

                <!-- Seção do formulário de login. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-12 lg:p-16">
                    <div class="w-full max-w-md space-y-6">
                        <!-- Exibe mensagens de status (ex: sucesso na redefinição de senha). -->
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
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-roxo1 focus:ring-roxo1 rounded-md shadow-sm"
                                />
                                <InputError class="mt-2 text-sm text-red-600 dark:text-red-400" :message="form.errors.email" />
                            </div>

                            <!-- Campo: Senha -->
                            <div>
                                <InputLabel for="password" value="Senha" class="text-gray-800 dark:text-gray-200" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-roxo1 focus:ring-roxo1 rounded-md shadow-sm"
                                />
                                <InputError class="mt-2 text-sm text-red-600 dark:text-red-400" :message="form.errors.password" />
                            </div>

                            <!-- Checkbox: Lembrar-me -->
                            <div class="block">
                                <label class="flex items-center">
                                    <Checkbox name="remember" v-model:checked="form.remember" />
                                    <span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Lembrar-me</span>
                                </label>
                            </div>

                            <!-- Botões de ação e links. -->
                            <div class="flex items-center justify-between mt-6">
                                <!-- Link para redefinição de senha (visível se canResetPassword for true). -->
                                <Link
                                    v-if="canResetPassword"
                                    href="/forgot-password"
                                    class="text-sm text-gray-600 dark:text-gray-300 underline hover:text-roxo2 dark:hover:text-laranja2 transition-colors duration-200"
                                >
                                    Esqueceu sua senha?
                                </Link>

                                <!-- Botão de submissão do formulário. Desabilitado durante o processamento. -->
                                <PrimaryButton
                                    class="ms-4 px-6 py-3 bg-roxo1 hover:bg-roxo1-hover text-prata1 font-semibold rounded-lg shadow-md transition-all duration-300 ease-in-out transform hover:scale-105"
                                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Entrar
                                </PrimaryButton>
                            </div>
                            <!-- Link para a página de registro (se o usuário ainda não tiver conta). -->
                            <div class="mt-4 text-center">
                                <Link
                                    href="/register"
                                    class="text-sm text-gray-600 dark:text-gray-300 underline hover:text-roxo2 dark:hover:text-laranja2 transition-colors duration-200"
                                >
                                    Ainda não tem uma conta? Cadastre-se!
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
