<template>

    <!-- <v-table>
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
    </v-table> -->

    <v-data-table 
        :items="taskList" 
        :headers="dataTableHeaders" 
        :items-per-page="dataTableItemsPerPage"
        hide-default-footer>
        <template v-slot:item="{ item }">
            <tr>
                <td>{{ item.descricao }}</td>
                <td class="text-center">{{ formatDate(item.agenda_inicio) }}</td>
                <td class="text-center">{{ item.status }}</td>
                <td class="text-center">{{ item.categoria }}</td>
                <td class="d-flex ga-2 align-center">
                    <v-btn 
                        icon="mdi-file-edit-outline" 
                        variant="elevated" 
                        size="default" 
                        density="comfortable"
                        class="bg-teal-lighten-2"
                        @click="callEditModal(item)">
                    </v-btn>
                    <v-btn 
                        icon="mdi-delete-outline" 
                        variant="elevated" 
                        size="default" 
                        density="comfortable"
                        class="bg-red-lighten-2"
                        @click="destroy(item.id)">
                    </v-btn>
                </td>
            </tr>
        </template>
    </v-data-table>

</template>



<script>
import { format, parse, intlFormatDistance, formatRelative, intervalToDuration } from 'date-fns'
import { id, ptBR } from 'date-fns/locale'

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
                { title: "Ações", align: "center"}
            ],
            dataTableItemsPerPage: 20
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
            
            try {
                const dateTime= parse(param, "yyyy-MM-dd HH:mm:ss", new Date())
                const interval= intervalToDuration({start: Date.now(), end: dateTime})
                console.log("data: ", format(dateTime, "dd/MM/yy"), "intl: ", intlFormatDistance(dateTime, Date.now()))
                console.log("interval dias/horas: ", interval)

                if ((interval.days < 3  && interval.days > 0 ) || !interval.days && interval.hours > 0) {
                    return intlFormatDistance(dateTime, Date.now())+" às "+ format(dateTime, "HH:mm")
                } else {
                    return format(dateTime, "dd/MM/yy")+" às "+ format(dateTime, "HH:mm")
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


        // const dateTime= parse("20-10-1990 20:30", "dd-MM-yyyy HH:mm:ss", new Date())
        // console.log("Date: ", date)
        // console.log("Valid: ", isValid(date))
        // console.log(date +" -> "+typeof(date))
        // console.log(date.getHours())
    }
};
</script>

<style scoped>

</style>