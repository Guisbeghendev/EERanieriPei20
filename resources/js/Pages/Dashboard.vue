<!-- resources/js/Pages/Dashboard.vue -->
<!--
    Página de Dashboard para usuários autenticados.
    Exibe um painel personalizado com links para dashboards específicos de papel (Admin, Fotógrafo)
    e uma lista das últimas galerias publicadas nos grupos aos quais o usuário pertence.
-->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'; // Importa o layout principal da aplicação
import { Head, Link } from '@inertiajs/vue3';    // Importa Head para título e Link para navegação Inertia

// Define as propriedades (props) que esta página recebe do backend.
const props = defineProps({
    auth: Object, // Objeto de autenticação contendo dados do usuário e seus papéis.
    userGroupsWithLatestGalleries: Array, // Lista de grupos do usuário com as últimas galerias.
});

/**
 * Verifica se o usuário autenticado possui um determinado papel (role).
 * @param {string} roleName O nome do papel a ser verificado (ex: 'admin', 'fotografo').
 * @returns {boolean} True se o usuário tiver o papel, false caso contrário.
 */
const userHasRole = (roleName) => {
    // Garante que o objeto 'auth' e 'user' existem.
    if (!props.auth || !props.auth.user) {
        return false;
    }
    // Verifica se o usuário tem a propriedade 'roles' e se algum dos papéis corresponde ao roleName.
    return props.auth.user.roles && props.auth.user.roles.some(role => role.name === roleName);
};

/**
 * Retorna a URL da primeira imagem de uma galeria para ser usada como miniatura.
 * Se a galeria não tiver imagens, retorna uma imagem placeholder.
 * @param {Object} gallery O objeto da galeria.
 * @returns {string} A URL da imagem ou do placeholder.
 */
const getFirstImageUrl = (gallery) => {
    // Verifica se a galeria tem imagens e se a primeira imagem tem um 'path_original'.
    if (gallery.images && gallery.images.length > 0 && gallery.images[0].path_original) {
        // Retorna o caminho completo da imagem armazenada no storage.
        return `/storage/${gallery.images[0].path_original}`;
    }
    // Retorna o caminho para uma imagem placeholder caso não haja imagens.
    return '/images/placeholder_gallery.jpg';
};
</script>

<template>
    <AppLayout>
        <!-- Define o título da página na aba do navegador. -->
        <Head title="Dashboard" />

        <!-- Slot para o título na seção principal do AppLayout. -->
        <template #title>
            <h1 class="text-roxo2 dark:text-roxo2-hover font-extrabold tracking-tight text-4xl lg:text-5xl drop-shadow-md">
                Meu Painel
            </h1>
        </template>

        <!--
            Container principal do dashboard.
            Define espaçamento, padding responsivo e largura máxima para centralizar o conteúdo
            em telas grandes, permitindo um layout de 3 colunas.
        -->
        <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-screen-xl mx-auto">
            <!--
                Grid para os cartões de dashboard.
                - grid-cols-1: 1 coluna em telas pequenas.
                - sm:grid-cols-2: 2 colunas em telas médias.
                - lg:grid-cols-3: 3 colunas em telas grandes (a partir de 1024px de largura).
                - gap-6: Espaçamento entre os itens do grid.
            -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Cartão: Painel Administrativo (visível apenas para usuários com o papel 'admin') -->
                <div v-if="userHasRole('admin')" class="p-6 bg-green-100 rounded-lg shadow hover:shadow-lg transition cursor-pointer">
                    <h3 class="text-green-700 font-semibold mb-2">Painel Administrativo</h3>
                    <p class="text-gray-600">Acesse as configurações e gestão do sistema.</p>
                    <!-- Link para o dashboard administrativo. Usando URL literal. -->
                    <Link href="/admin/dashboard" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Acessar Admin
                    </Link>
                </div>

                <!-- Cartão: Painel do Fotógrafo (visível apenas para usuários com o papel 'fotografo') -->
                <div v-if="userHasRole('fotografo')" class="p-6 bg-yellow-100 rounded-lg shadow hover:shadow-lg transition cursor-pointer">
                    <h3 class="text-yellow-700 font-semibold mb-2">Painel do Fotógrafo</h3>
                    <p class="text-gray-600">Gerencie suas galerias e acervo fotográfico.</p>
                    <!-- Link para o dashboard do fotógrafo. Usando URL literal. -->
                    <Link href="/fotografo/dashboard" class="mt-4 inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Acessar Painel
                    </Link>
                </div>

                <!-- Seção: Últimas Galerias Publicadas nos Seus Grupos -->
                <!-- Este contêiner ocupa todas as 3 colunas em telas grandes. -->
                <div class="mt-8 p-6 bg-purple-100 rounded-lg shadow lg:col-span-3">
                    <h2 class="text-purple-700 font-bold text-xl mb-4">Últimas Galerias Publicadas nos Seus Grupos</h2>

                    <!-- Mensagem se não houver galerias. -->
                    <div v-if="userGroupsWithLatestGalleries.length === 0" class="text-gray-600">
                        <p>Você não possui acesso a grupos com galerias recentes ou nenhuma galeria foi publicada ainda nos seus grupos.</p>
                    </div>

                    <!-- Itera sobre cada grupo que o usuário pertence. -->
                    <div v-for="group in userGroupsWithLatestGalleries" :key="group.id" class="mb-6 last:mb-0">
                        <h3 class="text-purple-600 font-semibold text-lg mb-3">{{ group.name }}</h3>
                        <!-- Grid para as galerias dentro de cada grupo.
                            - grid-cols-1: 1 galeria por linha em mobile.
                            - sm:grid-cols-2: 2 galerias por linha em telas médias.
                            - lg:grid-cols-3: 3 galerias por linha em telas grandes.
                            - gap-4: Espaçamento entre as galerias.
                        -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Itera sobre cada galeria dentro do grupo. -->
                            <div v-for="gallery in group.galleries" :key="gallery.id" class="bg-white rounded-lg shadow overflow-hidden flex flex-col hover:shadow-md transition">
                                <!-- Link para a página de detalhes da galeria.
                                     Construindo a URL literal com o ID da galeria. -->
                                <Link :href="`/galleries/${gallery.id}`">
                                    <!-- Imagem da galeria.
                                         - w-full: Ocupa a largura total da coluna.
                                         - h-responsiva: Altura que se adapta a diferentes tamanhos de tela.
                                         - object-cover: Garante que a imagem cubra a área sem distorcer. -->
                                    <img :src="getFirstImageUrl(gallery)" :alt="gallery.title"
                                         class="w-full h-48 sm:h-56 md:h-64 lg:h-72 xl:h-80 2xl:h-96 object-cover">
                                    <div class="p-4">
                                        <h4 class="font-bold text-md text-gray-800 truncate">{{ gallery.title }}</h4>
                                        <p class="text-gray-600 text-sm mt-1">
                                            Data: {{ new Date(gallery.event_date).toLocaleDateString('pt-BR') }}
                                        </p>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
