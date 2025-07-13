<template>
    <v-dialog v-model="modalView" >
        <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
                <div class="text-h6 text-medium-emphasis ps-2" >Edição de tarefa</div>
                <v-btn icon="mdi-close" variant="text" @click="modalView= false"></v-btn>
            </v-card-title>
            

            <v-row class="mx-3">
                <v-col>
                    <v-textarea rows="1" variant="underlined" v-model="descricao"></v-textarea>
                </v-col>
            </v-row>

            
            
            <v-card-actions class="ma-4">
                <v-row >
                    <v-col cols="2" align-self="center">
                        
                        <v-text-field label="Agenda" variant="underlined" v-model="formattedDate" v-mask="'##/##/####'"></v-text-field>

                        <v-menu v-model="datePickerView" activator="parent" :close-on-content-click="false" id="dropdown-date">
                            <v-card>
                                <v-date-picker v-model="selectedDate" autocomplete="off"></v-date-picker>

                                <v-card-actions>
                                    <v-btn text color="primary" @click="confirmDate">OK</v-btn>
                                    <v-btn text @click="cancelDate">Cancelar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-menu>
                        
                    </v-col>
                    
                    <v-col cols="2" align-self="center">
                        <v-combobox 
                            label="Horário"
                            v-model="agenda_hora"
                            :items="horaList"
                            variant="underlined"
                            v-mask="'##:##'"
                            autocomplete="off">
                        </v-combobox>
                    </v-col>
                    
                    <v-col cols="2" align-self="center">
                        <v-combobox 
                            label="Categoria"
                            :items="categorias"
                            v-model="categoria"
                            variant="underlined">
                        </v-combobox>
                    </v-col>
                    
                    <v-col cols="2" align-self="center">
                        <v-select 
                            label="Status"
                            :items="['A fazer', 'Em progresso', 'Concluido']"
                            v-model="status"
                            variant="underlined">
                        </v-select>
                    </v-col>
                    
                    <v-col cols="2" align-self="center" class="d-flex " >
                        
                    </v-col>
                    
                    <v-col cols="2" align-self="center" class="d-flex justify-center ga-2 " >
                        <v-btn variant="tonal" class="w-100" @click="save()" >Salvar</v-btn>
                    </v-col>
                    
                </v-row>

            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { format } from "date-fns"

export default {
    name: 'TaskEditModal',
    props: {
        taskData: {}
    },
    data() {
        return {
            id: null,
            descricao: null,
            agenda_inicio: null,
            agenda_data: null,
            agenda_hora: null,
            agenda_fim: null,
            responsavel: null,
            categoria: null,
            tags: null,
            status: null,
            categorias: ["Azul", "Amarelo", "Vermelho"],
            status: ["A fazer", "Em progresso", "Feito"],
            selectedDate: null,
            formattedDate: null,
            datePickerView: false,
            modalView: false,
            horaList: [
                "06:00",
                "07:00",
                "08:00",
                "09:00",
                "10:00",
                "11:00",
                "12:00",
                "13:00",
                "14:00",
                "15:00",
                "16:00",
                "17:00",
                "18:00",
                "19:00",
                "20:00",
                "21:00",
                "22:00",
                "23:00",
                "00:00",
                "01:00",
                "02:00",
                "03:00",
                "04:00",
                "05:00",
            ]
        }
    },

    methods: {
        confirmDate() {
            this.formattedDate = format(this.selectedDate, "dd/MM/yyyy") 
            this.datePickerView= !this.datePickerView
        },

        cancelDate() {
            this.formattedDate= null
            this.agenda_inicio= null
            this.agenda_hora= null
            this.datePickerView= false
        },

        cleanFields() {
            this.descricao= null,
            this.agenda_inicio= null,
            this.agenda_data= null,
            this.agenda_hora= null,
            this.agenda_fim= null,
            this.responsavel= null,
            this.categoria= null,
            this.status= "A fazer",
            this.formattedDate= null
        },

        callRefreshTaskList() {
            this.$emit('callRefreshTaskList')
        },

        async save() {
            console.log("formattedDate: ", this.formattedDate)
            if (this.formattedDate) {
                const [d, M, y]= (this.formattedDate).split('/')
                const [h, m]= (this.agenda_hora || "00:00").split(':')
                const dataHora= new Date(y, M-1, d, h, m)
                this.agenda_inicio= format(dataHora, 'yyyy-MM-dd HH:mm:ss')
            }

            const taskData = {
                id: this.id,
                descricao: this.descricao,
                agenda_inicio: this.agenda_inicio,
                agenda_fim: this.agenda_fim,
                responsavel: this.responsavel,
                categoria: this.categoria,
                tags: this.tags,
                status: this.status
            }

            try {
                const response = await fetch('/task/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ taskData: taskData })
                })

                console.log("response: ", await response.json())
                this.cleanFields()
                this.callRefreshTaskList()
                this.modalView= false

            } catch (error) {
                console.error("Erro ao salvar uma tarefa: ", error)
            }
        }
        
    },

    mounted() {

    },

    watch: {
        taskData: {
            handler(newVal) {
                this.id= newVal?.id,
                this.descricao= newVal?.descricao || '',
                this.formattedDate= format(newVal?.agenda_inicio, 'dd/MM/yyyy') || '',
                this.agenda_hora= format(newVal?.agenda_inicio, 'HH:mm') || '',
                this.categoria= newVal?.categoria || '',
                this.status= newVal?.status || 'A fazer',
                this.tags= newVal?.tags || '',
                this.agenda_fim= newVal?.agenda_fim || null
            }
        }
    }


}
</script>


<style>


</style>