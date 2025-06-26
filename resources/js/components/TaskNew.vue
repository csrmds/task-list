<template>
    <div class="col s12 m7">
        <h2 class="header">Horizontal Card</h2>
        <div class="card horizontal">
            <div class="card-stacked">
                <div class="card-content">
                    <p>I am a very simple card. I am good at containing small bits of information.</p>
                </div>
                <div class="card-action">
                    <a href="#">This is a link</a>
                </div>
            </div>
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
