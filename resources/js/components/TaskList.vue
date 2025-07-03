<template>
    <div class="w-full">
        <table>
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Agenda</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(task, i) in taskList" :key="i">
                    <td>{{ task.descricao }}</td>
                    <td>{{ task.agenda_inicio }}</td>
                    <td>{{ task.status }}</td>
                    <td>
                        <a class="btn-floating waves-effect waves-light green" @click="edit(task.id)" ><i class="material-icons">edit</i></a>
                        <a class="btn-floating waves-effect waves-light red" @click="destroy(task.id)" ><i class="material-icons">delete</i></a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <task-edit-modal :taskData="selectedTask"></task-edit-modal>
        </div>
    </div>
</template>



<script>
export default {
    name: 'task-list',

    // props: {

    // },

    data() {
        return {
            taskList: [],
            selectedTask: {
                id: null,
                descricao: null,
                agenda_inicio: null,
                status: null
            },
            modal: null
        };
    },

    methods: {
        async getList() {

            try {
                const response= await fetch('/task/list')
                const data= await response.json();
                this.taskList= data;
                console.log("consultou dados: ", data);
            } catch(error) {
                console.error("Erro ao listar tarefas: ", error);
            }
            
        },

        async edit(id) {
            const params= new URLSearchParams({id})
            const response= await fetch('/task/id?' + params.toString());
            const data= await response.json()
            this.selectedTask= data
            console.log("modal: ", this.modal)
            this.modal.open()
            console.log("edit: ", data);
        },

        async destroy(id) {
            const params= new URLSearchParams({id})
            const response= await fetch('/task/destroy' + params.toString());
            console.log("destroy: ", await response.json());
        },

        // async selectTask() {

        // }
    },

    mounted() {
        this.getList()
        const elems= document.getElementById('taskEdit')
        this.modal= M.Modal.init(elems, {})
    }
};
</script>

<style scoped>

</style>