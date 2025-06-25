<!-- resources/js/Components/Layout/Header.vue -->
<!--
    Componente de cabeçalho do layout.
    Exibe o logo principal da escola, visível apenas em telas maiores.
-->
<template>
    <div>
        <!--
            Contêiner do cabeçalho com estilos Tailwind CSS.
            Centraliza o texto/imagem, adiciona padding, cor de fundo,
            cantos arredondados na parte inferior, sombra e transições suaves.
        -->
        <div class="text-center p-4 bg-prata1 rounded-b-lg shadow-md transition-all duration-300 ease-in-out">
            <!--
                Imagem do logo da escola.
                Usamos 'mx-auto' para centralizar e 'drop-shadow-md' para um efeito visual.
                Mantendo o nome original do arquivo conforme sua instrução.
            -->
            <img src="/images/logo/prof_ranieri.png" alt="Logo Ranieri" class="h-24 mx-auto drop-shadow-md">
        </div>
    </div>
</template>

<script setup>
// Não há lógica JavaScript complexa necessária para este componente simples.
</script>

<style scoped>
/* Estilos específicos para este componente, se necessário. */
</style>
