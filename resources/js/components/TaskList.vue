<template>


    <v-text-field v-model="search" label="Filtro" variant="underlined" hide-details single-line
        class="px-4 bg-teal-lighten-5" />


    <v-data-table :items="taskList" :headers="dataTableHeaders" :items-per-page="dataTableItemsPerPage" :search="search"
        hide-default-footer
        class="bg-teal-lighten-5">
        <template v-slot:item="{ item }">
            <tr>
                <td>{{ item.resumo  }}</td>
                <td class="text-center">
                    {{ formatDate(item.agenda_inicio) }}
                    <v-btn class="bg-teal-lighten-5" icon readonly flat>
                        <img v-if="item.google_calendar_id" :src="iconEnable" width="24">
                        <!-- <img v-else="item.google_calendar_id" :src="iconDisabled" width="24" > -->
                    </v-btn>
                </td>
                <td :class="{
                        'text-center': true,
                        'bg-yellow-lighten-4': item.status === 'A fazer',
                        'bg-green-lighten-4': item.status === 'Concluido',
                        'bg-blue-lighten-4': item.status === 'Em progresso'
                    }">
                    {{ item.status }}
                </td>
                <td class="d-flex ga-2 align-center justify-center">
                    
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
import { format, parse } from 'date-fns'
import iconEnable from '@/assets/gcalendar.svg'
import iconDisabled from '@/assets/gcalendar_disabled.svg'

export default {
    name: 'task-list',

    data() {
        return {
            taskList: [],
            selectedTask: {
                id: null,
                resumo: null,
                descricao: null,
                agenda_inicio: null,
                status: null
            },
            dataTableHeaders: [
                { title: "Tarefa", align: "start", key: "resumo" },
                { title: "Agenda", align: "center", key: "agenda_inicio" },
                { title: "Status", align: "center", key: "status" },
                { title: "Ações", align: "center" }
            ],
            dataTableItemsPerPage: 20,
            search: null,
            deleteConfirmView: false,
            statusColor: [ 
                {status: "a fazer", color: "lime-lighten-3"},
                {status: "em progresso", color: "blue-lighten-3"},
                {status: "concluido", color: "green-lighten-3"},
            ],
            iconEnable,
            iconDisabled,
            calendarIcon: true
        };
    },

    methods: {
        async getList() {

            try {
                const response = await axios.get('/task/list')
                response.data.success ? this.taskList= response.data.data : false
            } catch (error) {
                console.error("Erro ao listar tarefas: ", error);
            }

        },

        async edit(id) {
            const params = new URLSearchParams({ id })
            const response = await fetch('/task/id?' + params.toString());
            const data = await response.json()
            this.selectedTask = data

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
                //const interval = intervalToDuration({ start: Date.now(), end: dateTime })
                

                return format(dateTime, "dd/MM/yy") + " às " + format(dateTime, "HH:mm")

            } catch {
                return "-"
            }
        },

        callEditModal(param) {
            //console.log("TaskList $emit callEditModal param: ", param)
            this.$emit('callEditModal', param)
        }
    },

    mounted() {
        this.getList()
    },

    computed: {
        calendarIcon() {
            return this.googleCalendar ? this.iconEnable : this.iconDisabled
        }
    }
};
</script>

<style scoped></style>