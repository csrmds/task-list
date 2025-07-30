<template>

    <v-card class="pa-4 bg-green-lighten-4">
        <v-row>
            <v-col>
                <v-textarea rows="1" label="Nova Tarefa" variant="underlined" v-model="resumo"></v-textarea>
            </v-col>
            <v-col cols="2" class="vertical-center">
                <v-btn variant="tonal" class="w-100" @click="save()">Salvar</v-btn>
            </v-col>
        </v-row>


        <v-row>
            <v-col cols="2" align-self="center">
                <v-text-field label="Data" variant="underlined" v-model="formattedDate" v-mask="'##/##/####'" autocomplete="off">

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

                </v-text-field>
            </v-col>

            <v-col cols="2" align-self="center">
                <v-combobox label="Hora" v-model="agenda_hora" :items="horaList" variant="underlined" v-mask="'##:##'" autocomplete="off" />
            </v-col>

            <!-- <v-col cols="2" align-self="center">
                <v-combobox label="Categoria" :items="categorias" v-model="categoria" variant="underlined">
                </v-combobox>
            </v-col> -->

            <v-col cols="2" align-self="center">
                <v-select label="Status" :items="['A fazer', 'Em progresso', 'Concluido']" v-model="status"
                    variant="underlined">
                </v-select>
            </v-col>

            <v-col cols="2" class="d-flex flex-column" >
                <label>Google Calendar</label>
                <div class="d-flex ga-1 flex-row justify-center" id="calendarDiv">
                    <img :src="calendarIcon" width="24" >
                    <v-checkbox v-model="googleCalendar" color="primary" id="v-checkbox-calendar" />
                </div>
            </v-col>

            <!-- <v-col cols="2" align-self="center" justify="center">
                <v-btn variant="tonal" class="w-100" @click="detalhesView = !detalhesView">Mais Info</v-btn>
            </v-col> -->

        </v-row>

        <v-row>
            <v-col>
                <v-expand-transition>
                    <v-textarea rows="2" label="Detalhes" v-show="detalhesView" v-model="descricao"></v-textarea>
                </v-expand-transition>

            </v-col>
        </v-row>




    </v-card>

</template>

<script>
import { format } from "date-fns"
import axios from "axios"
import iconEnable from '@/assets/gcalendar.svg'
import iconDisabled from '@/assets/gcalendar_disabled.svg'

export default {
    name: 'TaskNew',

    data() {
        return {
            resumo: null,
            descricao: null,
            agenda_inicio: null,
            agenda_data: null,
            agenda_hora: null,
            agenda_fim: null,
            responsavel: null,
            categoria: null,
            tags: null,
            status: "A fazer",
            google_calendar_id: null,
            google_calendar_link: null,
            user_id: null,
            categorias: ["AZUL", "AMARELO", "VERMELHO"],
            selectedDate: null,
            formattedDate: "",
            datePickerView: false,
            detalhesView: false,
            googleCalendar: true,
            iconEnable,
            iconDisabled,
            calendarIcon: this.googleCalendar,
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
            ],
        };
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
            this.resumo= null,
            this.descricao= null,
            this.agenda_inicio= null,
            this.agenda_data= null,
            this.agenda_hora= null,
            this.agenda_fim= null,
            this.responsavel= null,
            this.categoria= null,
            this.status= "A fazer",
            this.formattedDate= null,
            this.google_calendar_id= null,
            this.google_calendar_link= null,
            this.user_id= null
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


        async save() {
            console.log("iniciou execução do save...")

            if (this.googleCalendar) {
                console.log("chamou createGoogleEvent..")
                const googleEvent= await this.createGoogleEvent()
                console.log("googleEvent response: ", googleEvent)
                if (!googleEvent.success) return false

                this.google_calendar_id= googleEvent.data.id,
                this.google_calendar_link= googleEvent.data.htmlLink
            }
            
            console.log("continuou execução do save...")
            //console.log("response googleEvent fora do if: ", googleEvent)

            if (this.formattedDate) {
                //console.log("formattedDate: ", this.formattedDate)
                const [d, M, y]= (this.formattedDate).split('/')
                const [h, m, s]= (this.agenda_hora || "00:00:03").split(':')
                const dataHora= new Date(y, M-1, d, h, m)
                this.agenda_inicio= format(dataHora, 'yyyy-MM-dd HH:mm:ss')
            }


            const taskData = {
                resumo: this.resumo,
                descricao: this.descricao,
                agenda_inicio: this.agenda_inicio,
                agenda_fim: this.agenda_fim,
                responsavel: this.responsavel,
                categoria: this.categoria,
                tags: this.tags,
                status: this.status,
                google_calendar_id: this.google_calendar_id,
                google_calendar_link: this.google_calendar_link,
                user_id: this.user_id
            }

            console.log("taskData antes do /task/store: ", taskData)

            try {
                const response = await fetch('/task/store', {
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

            } catch (error) {
                console.error("Erro ao salvar uma tarefa: ", error)
            }
        }
    },

    computed: {
        calendarIcon() {
            return this.googleCalendar ? this.iconEnable : this.iconDisabled
        }
    }
};
</script>

<style>
.v-picker-title {
    display: none;
}

.v-picker__header {
    display: none;
}

#v-checkbox-calendar-messages {
    display: none !important;
}

</style>