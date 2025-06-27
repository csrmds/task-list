<template>

    <div class="w-full">
        <div class="row input-field">
            <input id="busca" class="materialize-input"/>
            <label for="busca">Busca</label>
        </div>
    </div>

</template>

<script>
export default {
    name: 'TaskNew',
    data() {
        return {
            mensagem: 'Esse é um componente Vue!',
        };
    },
};
</script>

<style scoped></style>