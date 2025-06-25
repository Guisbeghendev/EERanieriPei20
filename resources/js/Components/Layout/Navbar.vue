<!-- resources/js/Components/Layout/Navbar.vue -->
<!--
    Componente da barra de navegação principal.
    Gerencia links de navegação, botões de login/registro e o menu de usuário logado
    com base no status de autenticação. Inclui responsividade para mobile.
-->
<template>
    <div>
        <!-- Primeira barra de navegação (superior), contém login/registro/avatar. -->
        <nav class="bg-laranja2 dark:bg-gray-900 dark:border-gray-700 shadow-md">
            <div class="max-w-screen-xl mx-auto px-4 py-3 flex justify-between items-center">

                <!-- Logo para mobile (visível apenas em telas pequenas) -->
                <div class="block md:hidden w-1/3">
                    <!-- Mantendo o nome original do arquivo conforme sua instrução. -->
                    <img src="/images/logo/prof_ranieri - fundo branco.png" alt="Logo" class="h-14 drop-shadow-sm" />
                </div>

                <!-- Espaço vazio para alinhar o conteúdo em telas maiores -->
                <div class="hidden md:block w-1/3"></div>

                <!-- Seção de login/registro/avatar -->
                <div class="flex items-center space-x-4 rtl:space-x-reverse w-1/3 justify-end">
                    <!-- Links de Login/Registro para usuários não autenticados -->
                    <template v-if="!$page.props.auth?.user">
                        <Link href="/login" class="inline-block px-5 py-2 text-lg text-black bg-transparent rounded-full hover:bg-roxo1 hover:text-prata1 dark:text-blue-500 transition-all duration-300 ease-in-out font-semibold">
                            Login
                        </Link>
                        <Link href="/register" class="inline-block px-5 py-2 text-lg text-black bg-transparent rounded-full hover:bg-roxo1 hover:text-prata1 dark:text-blue-500 transition-all duration-300 ease-in-out font-semibold">
                            Register
                        </Link>
                    </template>

                    <!-- Seção de usuário logado (avatar, nome e dropdown) -->
                    <template v-else>
                        <div ref="dropdownRef" class="relative flex items-center space-x-3 cursor-pointer select-none group">
                            <!-- Avatar do usuário (se existir) ou avatar padrão -->
                            <img
                                v-if="$page.props.auth?.user.avatar?.url"
                                :src="$page.props.auth?.user.avatar.url"
                                alt="Avatar do Usuário"
                                class="w-12 h-12 rounded-full object-cover shadow-md ring-2 ring-prata1 ring-offset-2 ring-offset-laranja2 group-hover:scale-105 transition-transform duration-200"
                                @click.stop="toggleDropdown"
                            />
                            <img
                                v-else
                                src="[https://www.gravatar.com/avatar/?d=mp&f=y](https://www.gravatar.com/avatar/?d=mp&f=y)"
                                alt="Avatar Padrão"
                                class="w-12 h-12 rounded-full object-cover shadow-md ring-2 ring-prata1 ring-offset-2 ring-offset-laranja2 group-hover:scale-105 transition-transform duration-200"
                                @click.stop="toggleDropdown"
                            />
                            <!-- Nome do usuário logado -->
                            <span class="text-gray-900 dark:text-white text-lg font-semibold group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-200">
                                {{ $page.props.auth?.user.name }}
                            </span>

                            <!-- Dropdown de menu para usuários logados -->
                            <div v-if="dropdownOpen" class="absolute right-0 mt-16 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 transform opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-200 ease-out origin-top-right">
                                <ul class="py-2 text-base text-gray-700 dark:text-gray-200">
                                    <li>
                                        <Link href="/profile" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
                                            Perfil
                                        </Link>
                                    </li>
                                    <li>
                                        <Link :href="dashboardRoute" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
                                            Dashboard
                                        </Link>
                                    </li>
                                    <li>
                                        <!-- Botão de Logout que envia uma requisição POST para a rota /logout -->
                                        <button @click="$inertia.post('/logout')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white transition-colors duration-200">
                                            Logout
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Segunda barra de navegação (inferior), contém os links principais do site. -->
        <nav class="bg-roxo1 dark:bg-gray-700 dark:border-gray-600 shadow-lg">
            <div class="max-w-screen-xl mx-auto px-4 py-3 flex items-center justify-between">
                <!--
                    Lista de links de navegação.
                    A visibilidade é controlada por 'menuOpen' para responsividade (mobile).
                -->
                <ul :class="menuOpen ? 'block w-full md:flex md:space-x-10 rtl:space-x-reverse' : 'hidden w-full md:flex md:space-x-10 rtl:space-x-reverse'" class="font-medium text-lg text-gray-900 dark:text-white">
                    <li>
                        <Link href="/" class="inline-block py-2 px-5 rounded-full text-prata1 hover:bg-laranja2 md:hover:text-prata1 dark:hover:text-blue-500 transition-all duration-300 ease-in-out">
                            Home
                        </Link>
                    </li>
                    <!-- Link para o Dashboard, visível apenas para usuários autenticados -->
                    <li v-if="$page.props.auth?.user">
                        <Link :href="dashboardRoute" class="inline-block py-2 px-5 rounded-full text-prata1 hover:bg-laranja2 md:hover:text-prata1 dark:hover:text-blue-500 transition-all duration-300 ease-in-out">
                            Dashboard
                        </Link>
                    </li>
                    <li>
                        <Link href="/sobre-a-escola" class="inline-block py-2 px-5 rounded-full text-prata1 hover:bg-laranja2 md:hover:text-prata1 dark:hover:text-blue-500 transition-all duration-300 ease-in-out">
                            Sobre a Escola
                        </Link>
                    </li>
                    <li>
                        <Link href="/coral-ranieri" class="inline-block py-2 px-5 rounded-full text-prata1 hover:bg-laranja2 md:hover:text-prata1 dark:hover:text-blue-500 transition-all duration-300 ease-in-out">
                            Coral Ranieri
                        </Link>
                    </li>
                    <li>
                        <Link href="/gremio" class="inline-block py-2 px-5 rounded-full text-prata1 hover:bg-laranja2 md:hover:text-prata1 dark:hover:text-blue-500 transition-all duration-300 ease-in-out">
                            Grêmio
                        </Link>
                    </li>
                    <li>
                        <Link href="/brincando-dialogando" class="inline-block py-2 px-5 rounded-full text-prata1 hover:bg-laranja2 md:hover:text-prata1 dark:hover:text-blue-500 transition-all duration-300 ease-in-out">
                            Brincando Dialogando
                        </Link>
                    </li>
                    <li>
                        <Link href="/simoninhanacozinha" class="inline-block py-2 px-5 rounded-full text-prata1 hover:bg-laranja2 md:hover:text-prata1 dark:hover:text-blue-500 transition-all duration-300 ease-in-out">
                            Simoninha na Cozinha
                        </Link>
                    </li>
                    <li v-if="$page.props.auth?.user">
                        <Link href="/galleries" class="inline-block py-2 px-5 rounded-full text-prata1 hover:bg-laranja2 md:hover:text-prata1 dark:hover:text-blue-500 transition-all duration-300 ease-in-out">
                            Galerias
                        </Link>
                    </li>
                </ul>

                <button
                    @click="menuOpen = !menuOpen"
                    type="button"
                    class="inline-flex items-center justify-center p-3 w-12 h-12 text-prata1 rounded-lg md:hidden
                           bg-roxo2 hover:bg-roxo1-hover focus:outline-none focus:ring-2 focus:ring-prata1
                           dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-400
                           transition-all duration-200 ease-in-out transform active:scale-95"
                    aria-controls="navbar-default"
                    :aria-expanded="menuOpen.toString()">
                    <span class="sr-only">Open main menu</span>
                    <svg v-if="!menuOpen" class="w-8 h-8" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg v-else class="w-8 h-8" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const menuOpen = ref(false);
const dropdownOpen = ref(false);
const dropdownRef = ref(null);

const page = usePage();

const dashboardRoute = computed(() => {
    const user = page.props.auth?.user;
    if (user && user.roles) {
        if (user.roles.some(role => role.name === 'admin')) {
            return '/admin/dashboard';
        }
        if (user.roles.some(role => role.name === 'fotografo')) {
            return '/fotografo/dashboard';
        }
    }
    return '/dashboard';
});

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        dropdownOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>
