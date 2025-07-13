<template>


    <v-text-field v-model="search" label="Filtro" variant="underlined" hide-details single-line></v-text-field>


    <v-data-table :items="taskList" :headers="dataTableHeaders" :items-per-page="dataTableItemsPerPage" :search="search"
        hide-default-footer>
        <template v-slot:item="{ item }">
            <tr>
                <td>{{ item.descricao }}</td>
                <td class="text-center">{{ formatDate(item.agenda_inicio) }}</td>
                <td :class="{
                        'text-center': true,
                        'bg-yellow-lighten-4': item.status === 'A fazer',
                        'bg-green-lighten-4': item.status === 'Concluido',
                        'bg-blue-lighten-4': item.status === 'Em progresso'
                    }">
                    {{ item.status }}
                </td>
                <td class="text-center">{{ item.categoria }}</td>
                <td class="d-flex ga-2 align-center">
                    <v-btn icon="mdi-file-edit-outline" variant="elevated" size="default" density="comfortable"
                        class="bg-teal-lighten-2" @click="callEditModal(item)">
                    </v-btn>
                    <v-btn icon="mdi-delete-outline" variant="elevated" size="default" density="comfortable"
                        class="bg-red-lighten-2" @click="()=> {
                            deleteConfirmView= true
                            selectedTask= item
                        }">
                    </v-btn>
                </td>
            </tr>
        </template>
    </v-data-table>

    <v-dialog 
        v-model="deleteConfirmView"
        max-width="500">
        
        <v-card>
            <v-card-text>
                <h4>Deseja deletar essa tarefa?</h4> 
                <p class="mt-4 text-subtitle-1">{{ selectedTask.descricao }}</p>
                <p class="mb-4">{{ formatDate(selectedTask.agenda_inicio)  }}</p>
            </v-card-text>
            
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="destroy(selectedTask.id)" >Sim</v-btn>
                <v-btn @click="deleteConfirmView= false" >Não</v-btn>
            </v-card-actions>
        </v-card>
        
    </v-dialog>



</template>



<script>
import { format, parse, intlFormatDistance, formatRelative, intervalToDuration } from 'date-fns'

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
            dataTableHeaders: [
                { title: "Descricao", align: "start", key: "descricao" },
                { title: "Agenda", align: "center", key: "agenda_inicio" },
                { title: "Status", align: "center", key: "status" },
                { title: "Categoria", align: "center", key: "categoria" },
                { title: "Ações", align: "center" }
            ],
            dataTableItemsPerPage: 20,
            search: null,
            deleteConfirmView: false,
            statusColor: [ 
                {status: "a fazer", color: "lime-lighten-3"},
                {status: "em progresso", color: "blue-lighten-3"},
                {status: "concluido", color: "green-lighten-3"},
            ]
        };
    },

    methods: {
        async getList() {

            try {
                const response = await fetch('/task/list')
                const data = await response.json();
                this.taskList = data;
                //console.log("consultou dados: ", data);
            } catch (error) {
                console.error("Erro ao listar tarefas: ", error);
            }

        },

        async edit(id) {
            const params = new URLSearchParams({ id })
            const response = await fetch('/task/id?' + params.toString());
            const data = await response.json()
            this.selectedTask = data
            console.log("modal: ", this.modal)
            // this.modal.open()
            console.log("edit: ", data);
        },

        async destroy(id) {
            const params = new URLSearchParams({ id })
            const response = await fetch('/task/destroy?' + params.toString());
            await response.json()
            this.deleteConfirmView= false
            //console.log("resposta: ", data)
            this.cleanSelectedTask()
            this.getList()
        },

        cleanSelectedTask() {
            this.selectedTask.id= null
            this.selectedTask.descricao= null
            this.selectedTask.agenda_inicio= null
            this.selectedTask.status= null
        },

        formatDate(param) {

            try {
                const dateTime = parse(param, "yyyy-MM-dd HH:mm:ss", new Date())
                const interval = intervalToDuration({ start: Date.now(), end: dateTime })
                //console.log("data: ", format(dateTime, "dd/MM/yy"), "intl: ", intlFormatDistance(dateTime, Date.now()))
                //console.log("interval dias/horas: ", interval)

                if (interval.days == 1 || (!interval.days && interval.hours > 0)) {
                    return intlFormatDistance(dateTime, Date.now()) + " às " + format(dateTime, "HH:mm")
                } else {
                    return format(dateTime, "dd/MM/yy") + " às " + format(dateTime, "HH:mm")
                }

            } catch {
                return "-"
            }
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

<style scoped></style>