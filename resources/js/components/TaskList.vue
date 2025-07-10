<template>

    <v-table>
        <thead>
            <tr>
                <th class="font-weight-bold">Descrição</th>
                <th class="text-center font-weight-bold">Agenda</th>
                <th class="text-center font-weight-bold">Status</th>
                <th class="text-center font-weight-bold">Categoria</th>
                <th class="text-center font-weight-bold">
                    Ação
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(task, i) in taskList" :key="i">
                <td>{{ task.descricao }}</td>
                <td class="text-center">{{ formatDate(task.agenda_inicio)  }}</td>
                <td class="text-center">{{ task.status }}</td>
                <td class="text-center">{{ task.categoria }}</td>
                <td class="d-flex ga-2 align-center">
                    <v-btn 
                        icon="mdi-file-edit-outline" 
                        variant="elevated" 
                        size="default" 
                        density="comfortable"
                        class="bg-teal-lighten-2"
                        @click="callEditModal(task)">
                    </v-btn>
                    <v-btn 
                        icon="mdi-delete-outline" 
                        variant="elevated" 
                        size="default" 
                        density="comfortable"
                        class="bg-red-lighten-2"
                        @click="destroy(task.id)">
                    </v-btn>
                </td>
            </tr>
        </tbody>
    </v-table>

</template>



<script>
import { format } from 'date-fns';

export default {
    name: 'task-list',

    data() {
        return {
            taskList: [],
            selectedTask: {
                id: null,
                descricao: null,
                agenda_inicio: null,
                status: null
            },
        };
    },

    methods: {
        async getList() {

            try {
                const response= await fetch('/task/list')
                const data= await response.json();
                this.taskList= data;
                //console.log("consultou dados: ", data);
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
            // this.modal.open()
            console.log("edit: ", data);
        },

        async destroy(id) {
            const params= new URLSearchParams({id})
            const response= await fetch('/task/destroy?' + params.toString());
            const data= await response.json()
            console.log("resposta: ", data)
            this.getList()
        },

        formatDate(param) {
            const [date, hour]= param.split(" ")
            return format(date, 'dd/MM/yy') +" às "+format(param, 'HH:mm')
        },

        callEditModal(param) {
            console.log("callEditModal param: ", param)
            this.$emit('callEditModal', param)
        }
    },

    mounted() {
        this.getList()
    }
};
</script>

<style scoped>

</style>