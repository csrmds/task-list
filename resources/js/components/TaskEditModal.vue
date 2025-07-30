<template>
    <v-dialog v-model="modalView">
        <v-card class="bg-green-lighten-4">
            <v-card-title class="d-flex justify-space-between align-center">
                <div class="text-h6 text-medium-emphasis ps-2">Edição de tarefa</div>
                <v-btn icon="mdi-close" variant="text" @click="modalView= false"></v-btn>
            </v-card-title>


            <v-row class="mx-3">
                <v-col>
                    <v-textarea rows="1" variant="underlined" v-model="resumo"></v-textarea>
                </v-col>
            </v-row>



            <v-card-actions class="ma-4">
                <v-row>
                    <v-col cols="2" align-self="center">

                        <v-text-field label="Agenda" variant="underlined" v-model="formattedDate"
                            v-mask="'##/##/####'"></v-text-field>

                        <v-menu v-model="datePickerView" activator="parent" :close-on-content-click="false"
                            id="dropdown-date">
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
                        <v-combobox label="Horário" v-model="agenda_hora" :items="horaList" variant="underlined"
                            v-mask="'##:##'" autocomplete="off">
                        </v-combobox>
                    </v-col>

                    <v-col cols="2" align-self="center">
                        <v-select label="Status" :items="['A fazer', 'Em progresso', 'Concluido']" v-model="status"
                            variant="underlined">
                        </v-select>
                    </v-col>

                    <v-col cols="2" class="d-flex flex-column vertical-center">
                        <div class="justify-center">
                            Google Calendar
                        </div>
                        <div class="d-flex ga-2 flex-row justify-center">
                            <img :src="calendarIcon" width="24">
                            <v-checkbox v-model="googleCalendar" color="primary" id="v-checkbox-calendar" />
                        </div>
                    </v-col>

                    <v-col cols="2" align-self="center" class="d-flex justify-center ga-2 ">
                        <!-- <p>ID: {{ google_calendar_id }}</p> -->
                    </v-col>

                    <v-col cols="2" align-self="center" class="d-flex justify-center ga-2 ">
                        <v-btn variant="tonal" class="w-100" @click="save()">Salvar</v-btn>
                    </v-col>

                </v-row>

            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { format } from "date-fns"
import iconEnable from '@/assets/gcalendar.svg'
import iconDisabled from '@/assets/gcalendar_disabled.svg'

export default {
    name: 'TaskEditModal',
    props: {
        taskData: {}
    },
    data() {
        return {
            id: null,
            resumo: null,
            descricao: null,
            agenda_inicio: null,
            agenda_data: null,
            agenda_hora: null,
            agenda_fim: null,
            responsavel: null,
            tags: null,
            status: null,
            google_calendar_id: null,
            google_calendar_link: null,
            user_id: null,
            status: ["A fazer", "Em progresso", "Feito"],
            googleCalendar: false,
            iconEnable,
            iconDisabled,
            calendarIcon: this.googleCalendar,
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
            this.id= null,
            this.resumo= null,
            this.descricao= null,
            this.agenda_inicio= null,
            this.agenda_data= null,
            this.agenda_hora= null,
            this.agenda_fim= null,
            this.responsavel= null,
            this.status= "A fazer",
            this.formattedDate= null
        },

        callRefreshTaskList() {
            this.$emit('callRefreshTaskList')
        },

        async createGoogleEvent() {

            try {
                let dataStart= null
                let dataEnd= null

                if (this.formattedDate) {
                    const [d, M, y]= (this.formattedDate).split('/')
                    const [h, m, s]= (this.agenda_hora || "00:00:03").split(':')
                    dataStart= new Date(y, M-1, d, h, m)
                    //console.log('dataStart: ', dataStart)
                    dataEnd= new Date(y, M-1, d, h, m)
                    dataEnd.setHours(dataEnd.getHours()+1)
                    //console.log('dataEnd: ', dataEnd)
                }

                const eventData= {
                    taskId: this.id,
                    summary: this.resumo,
                    description: this.descricao,
                    start: {
                        dateTime: dataStart,
                        timeZone: 'America/Sao_Paulo'
                    },
                    end: {
                        dateTime: dataEnd,
                        timeZone: 'America/Sao_Paulo'
                    }
                }

                const response= await axios.post('/gcalendar/createevent', {eventData: eventData})

                console.log('createGoogleEvent response.data: ', response.data)

                return response.data
                
            } catch (error) {
                return {
                    success: false,
                    message: 'Erro ao criar evento',
                    error: error
                }
            }
  
        },

        async updateGoogleEvent() {

            try {
                let dataStart= null
                let dataEnd= null

                if (this.formattedDate) {
                    const [d, M, y]= (this.formattedDate).split('/')
                    const [h, m, s]= (this.agenda_hora || "00:00:03").split(':')
                    dataStart= new Date(y, M-1, d, h, m)
                    //console.log('dataStart: ', dataStart)
                    dataEnd= new Date(y, M-1, d, h, m)
                    dataEnd.setHours(dataEnd.getHours()+1)
                    //console.log('dataEnd: ', dataEnd)
                }

                const eventData= {
                    id: this.google_calendar_id,
                    summary: this.resumo,
                    description: this.descricao,
                    start: {
                        dateTime: dataStart,
                        timeZone: 'America/Sao_Paulo'
                    },
                    end: {
                        dateTime: dataEnd,
                        timeZone: 'America/Sao_Paulo'
                    }
                }

                const response= await axios.post('/gcalendar/updateevent', {eventData: eventData})

                console.log('updateGoogleEvent response.data: ', response.data)

                return response.data

            } catch(error) {
                return {
                    success: false,
                    message: 'Erro ao atualizar evento',
                    error: error
                }
            }

        },

        
        async deleteGoogleEvent() {
            console.log('deleteGoogleEvent google_calendar_id: ', this.google_calendar_id)

            try {
                const eventData= {
                    id: this.google_calendar_id
                }

                const response= await axios.post('/gcalendar/deleteevent', {eventData: eventData})
                console.log('deleteGoogleEvent response.data', response.data)

                return response.data
            } catch(error) {
                return {
                    success: false,
                    message: 'Erro ao deletar evento',
                    error: error
                }
            }

        },


        async save() {

            if (this.googleCalendar && this.google_calendar_id) {
                this.updateGoogleEvent()
            } else if (this.googleCalendar && !this.google_calendar_id) {
                this.createGoogleEvent()
            } else if (!this.googleCalendar && this.google_calendar_id) {
                this.deleteGoogleEvent()
            }

            console.log("formattedDate: ", this.formattedDate)
            if (this.formattedDate) {
                const [d, M, y]= (this.formattedDate).split('/')
                const [h, m]= (this.agenda_hora || "00:00").split(':')
                const dataHora= new Date(y, M-1, d, h, m)
                this.agenda_inicio= format(dataHora, 'yyyy-MM-dd HH:mm:ss')
            }

            const taskData = {
                id: this.id,
                resumo: this.resumo,
                descricao: this.descricao,
                agenda_inicio: this.agenda_inicio,
                agenda_fim: this.agenda_fim,
                responsavel: this.responsavel,
                categoria: null,
                tags: this.tags,
                status: this.status,
                google_calendar_id: this.google_calendar_id,
                google_calendar_link: this.google_calendar_link,
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
    
    computed: {
        calendarIcon() {
            return this.googleCalendar ? this.iconEnable : this.iconDisabled
        }
    },

    watch: {
        taskData: {
            handler(newVal) {
                this.id= newVal?.id,
                this.resumo= newVal?.resumo || '',
                this.descricao= newVal?.descricao || '',
                this.formattedDate= format(newVal?.agenda_inicio, 'dd/MM/yyyy') || '',
                this.agenda_hora= format(newVal?.agenda_inicio, 'HH:mm') || '',
                this.status= newVal?.status || 'A fazer',
                this.tags= newVal?.tags || '',
                this.agenda_fim= newVal?.agenda_fim || null,
                this.google_calendar_id= newVal?.google_calendar_id || null,
                this.google_calendar_link= newVal?.google_calendar_link || null,
                this.googleCalendar= this.google_calendar_id ? true : false
            }
        }
    }


}
</script>


<style>
#v-checkbox-calendar-messages {
    display: none !important;
}

</style>