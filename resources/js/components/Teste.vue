<template>
    <div>
        <p>apenas um componente</p>
        <p>{{ mensagem }}</p>
    </div>
</template>



<script>
export default {
    name: "Componente de Teste",
    data() {
        return {
            mensagem: "mensagem do componente sem o script setup"
        }
    }
}
</script>