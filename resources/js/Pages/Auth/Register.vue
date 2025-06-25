<!-- resources/js/Pages/Auth/Register.vue -->
<!--
    Página de Registro de Novo Usuário.
    Permite que novos usuários criem uma conta no sistema.
    Utiliza o layout da aplicação e componentes reutilizáveis para formulários.
-->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';      // Layout principal da aplicação
import InputError from '@/Components/InputError.vue';  // Exibição de erros de validação
import InputLabel from '@/Components/InputLabel.vue';  // Rótulo para campos de formulário
import PrimaryButton from '@/Components/PrimaryButton.vue'; // Botão principal de ação
import TextInput from '@/Components/TextInput.vue';    // Campo de entrada de texto/email/senha
import Checkbox from '@/Components/Checkbox.vue';      // Componente de checkbox (necessário para 'remember' ou outros booleanos)
import { Head, Link, useForm } from '@inertiajs/vue3'; // Funções do Inertia.js para controle de página e formulários

// Inicializa o formulário com os campos necessários.
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// Função para submeter o formulário de registro.
const submit = () => {
    // Envia a requisição POST para a rota '/register'.
    // A Inertia.js lida automaticamente com o token CSRF se a meta tag estiver no app.blade.php.
    form.post('/register', {
        // Callback executado após a conclusão da requisição (sucesso ou falha).
        // Reseta os campos de senha para segurança.
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AppLayout>
        <!-- Define o título da página na aba do navegador. -->
        <Head title="Registro/Cadastro" />

        <!-- Slot para o título na seção principal do AppLayout. -->
        <template #title>
            <h1 class="text-roxo2 dark:text-roxo2-hover font-extrabold tracking-tight text-4xl lg:text-5xl drop-shadow-md">
                Cadastro de Novo Usuário
            </h1>
        </template>

        <!-- Contêiner centralizado para o formulário de registro, com um design de cartão dividido. -->
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-5xl flex flex-col lg:flex-row bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <!-- Seção de boas-vindas e logo, com gradiente de fundo. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center bg-gradient-to-br from-laranja1 via-laranja2 to-roxo2 text-white p-10 py-12 lg:py-0">
                    <div class="text-center space-y-6">
                        <h2 class="text-4xl lg:text-5xl font-bold mb-4 drop-shadow-md">Bem-vindo(a)!</h2>
                        <p class="text-lg lg:text-xl text-prata1 opacity-90">
                            Crie sua conta para acessar o sistema e desfrutar de todos os recursos exclusivos.
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

                <!-- Seção do formulário de registro. -->
                <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-12 lg:p-16">
                    <div class="w-full max-w-md space-y-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Campo: Nome Completo -->
                            <div>
                                <InputLabel for="name" value="Nome Completo" class="text-gray-800 dark:text-gray-200" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-roxo1 focus:ring-roxo1 rounded-md shadow-sm"
                                />
                                <InputError class="mt-2 text-sm text-red-600 dark:text-red-400" :message="form.errors.name" />
                            </div>

                            <!-- Campo: Endereço de E-mail -->
                            <div>
                                <InputLabel for="email" value="Endereço de E-mail" class="text-gray-800 dark:text-gray-200" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
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
                                    autocomplete="new-password"
                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-roxo1 focus:ring-roxo1 rounded-md shadow-sm"
                                />
                                <InputError class="mt-2 text-sm text-red-600 dark:text-red-400" :message="form.errors.password" />
                            </div>

                            <!-- Campo: Confirmar Senha -->
                            <div>
                                <InputLabel for="password_confirmation" value="Confirmar Senha" class="text-gray-800 dark:text-gray-200" />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-roxo1 focus:ring-roxo1 rounded-md shadow-sm"
                                />
                                <InputError class="mt-2 text-sm text-red-600 dark:text-red-400" :message="form.errors.password_confirmation" />
                            </div>

                            <!-- Botões de ação e link para login. -->
                            <div class="flex items-center justify-between mt-6">
                                <!-- Link para a página de login. -->
                                <Link
                                    href="/login"
                                    class="text-sm text-gray-600 dark:text-gray-300 underline hover:text-roxo2 dark:hover:text-laranja2 transition-colors duration-200"
                                >
                                    Já tem uma conta? Faça login aqui!
                                </Link>

                                <!-- Botão de submissão do formulário. Desabilitado durante o processamento. -->
                                <PrimaryButton
                                    class="ms-4 px-6 py-3 bg-roxo1 hover:bg-roxo1-hover text-prata1 font-semibold rounded-lg shadow-md transition-all duration-300 ease-in-out transform hover:scale-105"
                                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Cadastrar
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
