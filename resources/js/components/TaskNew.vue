<template>
    
        <div class="card horizontal">
            <div class="card-stacked">
                <div class="card-content">
                    
                    <div class="row input-field">
                        <textarea 
                            id="textarea1" 
                            class="materialize-textarea"
                            v-model="descricao"
                            ></textarea>
                        <label for="textarea1">Nova tarefa</label>
                    </div>
                    
                    <div class="row">
                        <div class="col s5 bg-blue-300">
                            <a class="waves-effect waves-light btn" @click="save()" >Salvar</a>
                            <a class="waves-effect waves-light btn"><i class="material-icons">date_range</i></a>
                            <a class="waves-effect waves-light btn"><i class="material-icons">flag</i></a>
                        </div>
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
            descricao: "",
            agenda_inicio: "",
            agenda_fim: null,
            responsavel: null,
            categoria: "AZUL",
            tags: null,
            status: "A fazer",
        };
    },

    methods: {
        async save() {
            //event.preventDefault()
            const taskData= {
                descricao: this.descricao, 
                agenda_inicio: this.agenda_inicio,
                agenda_fim: this.agenda_fim,
                responsavel: this.responsavel,
                categoria: this.categoria, 
                tags: this.tags,
                status: this.status
            }
            try {
                const response= await fetch('/task/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({taskData: taskData})
                })

                console.log("response: ", await response.json())
                
            } catch(error) {
                console.error("Erro ao salvar uma tarefa: ", error)
            }
        }
    }
};
</script>

<style scoped>

</style>