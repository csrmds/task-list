<template>
    
        <div class="card horizontal">
            <div class="card-stacked">
                <div class="card-content">
                    <div class="row input-field">
                        <textarea id="textarea1" class="materialize-textarea"></textarea>
                        <label for="textarea1">Nova tarefa</label>
                    </div>
                    <div class="row">
                        <div class="col s5 bg-blue-300">
                            <a class="waves-effect waves-light btn">Salvar</a>
                            <a class="waves-effect waves-light btn"><i class="material-icons">date_range</i></a>
                            <a class="waves-effect waves-light btn"><i class="material-icons">flag</i></a>
                        </div>
                    </div>
                    
                </div>
                <!-- <div class="card-action">
                    
                </div> -->
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

<style scoped>

</style>