<template>
  <v-dialog v-model="modalView">
    <v-card class="bg-green-lighten-4">
      <v-card-title class="d-flex justify-space-between align-center">
        <div class="text-h6 text-medium-emphasis ps-2">
          Edição de tarefa
        </div>
        <v-btn
          icon="mdi-close"
          variant="text"
          @click="modalView= false"
        />
      </v-card-title>


      <v-row class="mx-3">
        <v-col>
          <v-textarea 
            v-model="form.resumo" 
            rows="1" 
            variant="underlined"
            :error="!!errors.resumo"
            :error-messages="errors.resumo"
          />
        </v-col>
      </v-row>



      <v-card-actions class="ma-4">
        <v-row>
          <v-col
            cols="2"
            align-self="center"
          >
            <v-text-field 
              v-model="form.agenda_data" 
              v-mask="'##/##/####'" 
              label="Data"
              variant="underlined"
              :error="!!errors.agenda_data"
              :error-messages="errors.agenda_data"
              autocomplete="off"
            >
              <v-menu
                id="dropdown-date"
                v-model="datePickerView"
                activator="parent"
                :close-on-content-click="false"
              >
                <v-card>
                  <v-date-picker
                    v-model="selectedDate"
                    autocomplete="off"
                  />

                  <v-card-actions>
                    <v-btn
                      text
                      color="primary"
                      @click="confirmDate"
                    >
                      OK
                    </v-btn>
                    <v-btn
                      text
                      @click="cancelDate"
                    >
                      Cancelar
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-menu>
            </v-text-field>
          </v-col>

          <v-col
            cols="2"
            align-self="center"
          >
            <v-combobox
              v-model="agenda_hora"
              v-mask="'##:##'"
              label="Horário"
              :items="horaList"
              variant="underlined"
              autocomplete="off"
            />
          </v-col>

          <v-col
            cols="2"
            align-self="center"
          >
            <v-select
              v-model="status"
              label="Status"
              :items="['A fazer', 'Em progresso', 'Concluido']"
              variant="underlined"
            />
          </v-col>

          <v-col
            cols="2"
            class="d-flex flex-column vertical-center"
          >
            <div v-if="isGoogleAccount">
              <label>Google Calendar</label>
              <div class="d-flex ga-1 flex-row justify-center">
                <img
                  :src="calendarIcon"
                  width="24"
                >
                <v-checkbox
                  id="v-checkbox-calendar"
                  v-model="googleCalendar"
                  color="primary"
                />
              </div>
            </div>
          </v-col>

          <v-col
            cols="2"
            align-self="center"
            class="d-flex justify-center ga-2 "
          >
            <!-- <p>ID: {{ google_calendar_id }}</p> -->
          </v-col>

          <v-col
            cols="2"
            align-self="center"
            class="d-flex justify-center ga-2 "
          >
            <v-btn
              v-if="!loaderView"
              variant="tonal"
              class="w-100"
              @click="save()"
            >
              Salvar
            </v-btn>
            <v-progress-circular
              v-else
              loaderView
              color="green"
              indeterminate
            />
          </v-col>
        </v-row>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { format } from "date-fns"
import axios from "axios"
import iconEnable from '@/assets/gcalendar.svg'
import iconDisabled from '@/assets/gcalendar_disabled.svg'
import * as yup from 'yup'
import { reactive } from 'vue'

export default {
    name: 'TaskEditModal',
    props: {
        taskData: {
          type: Object,
          default: ()=> ({})
        },
        userData: {
          type: Object,
          default: ()=> ({})
        }
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
            // calendarIcon: this.googleCalendar,
            selectedDate: null,
            formattedDate: null,
            datePickerView: false,
            modalView: false,
            loaderView: false,
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
            form: reactive({
                resumo: '',
                agenda_data: '',
            }),
            errors: reactive({
                resumo: '',
                agenda_data: '',
            }),
            schema: yup.object({
                resumo: yup.string().required('Esse campo precisa ser preenchido'),
            }),
            schemaCalendar: yup.object({
                resumo: yup.string().required('Esse campo precisa ser preenchido'),
                agenda_data: yup.string().required('Defina uma data para a tarefa')
            })
        }
    },
    
    computed: {
        calendarIcon() {
            return this.googleCalendar ? this.iconEnable : this.iconDisabled
        },

        isGoogleAccount() {
            if (this.userData.google_id) {
                return true
            } else {
                return false
            }
        }
    },

    watch: {
        taskData: {
            handler(newVal) {
                console.log("watch newVal: ", newVal)
                this.id= newVal?.id,
                this.form.resumo= newVal?.resumo || '',
                this.descricao= newVal?.descricao || '';
                if (newVal.agenda_inicio) { 
                    this.form.agenda_data= format(newVal?.agenda_inicio, 'dd/MM/yyyy') 
                    this.agenda_hora= format(newVal?.agenda_inicio, 'HH:mm')
                } else {
                    this.form.agenda_data= '',
                    this.agenda_hora= ''
                };
                this.status= newVal?.status || 'A fazer',
                this.tags= newVal?.tags || '',
                this.agenda_fim= newVal?.agenda_fim || null,
                this.google_calendar_id= newVal?.google_calendar_id || null,
                this.google_calendar_link= newVal?.google_calendar_link || null,
                this.googleCalendar= this.google_calendar_id ? true : false
            }
        }
    },

    mounted() {
        
    },

    methods: {
        confirmDate() {
            this.form.agenda_data = format(this.selectedDate, "dd/MM/yyyy") 
            this.datePickerView= !this.datePickerView
        },

        cancelDate() {
            this.form.agenda_data= null
            this.agenda_inicio= null
            this.agenda_hora= null
            this.datePickerView= false
        },

        cleanFields() {
            this.id= null,
            this.form.resumo= null,
            this.descricao= null,
            this.agenda_inicio= null,
            this.form.agenda_data= null,
            this.agenda_hora= null,
            this.agenda_fim= null,
            this.responsavel= null,
            this.status= "A fazer",
            this.formattedDate= null,
            this.loaderView= false
        },

        callRefreshTaskList() {
            this.$emit('callRefreshTaskList')
        },

        async createGoogleEvent() {

            try {
                this.errors.resumo= ''
                this.errors.agenda_data= ''
                let dataStart= null
                let dataEnd= null

                await this.schemaCalendar.validate(this.form, {abortEarly: false})

                if (this.form.agenda_data) {
                    const [d, M, y]= (this.form.agenda_data).split('/')
                    const [h, m, s]= (this.agenda_hora || "00:00:03").split(':')
                    dataStart= new Date(y, M-1, d, h, m)
                    //console.log('dataStart: ', dataStart)
                    dataEnd= new Date(y, M-1, d, h, m)
                    dataEnd.setHours(dataEnd.getHours()+1)
                    //console.log('dataEnd: ', dataEnd)
                }

                const eventData= {
                    taskId: this.id,
                    summary: this.form.resumo,
                    description: this.descricao,
                    start: {
                        dateTime: dataStart,
                        timeZone: 'America/Sao_Paulo'
                    },
                    end: {
                        dateTime: dataEnd,
                        timeZone: 'America/Sao_Paulo'
                    },
                }

                const response= await axios.post('/gcalendar/createevent', {eventData: eventData})

                console.log('createGoogleEvent response.data: ', response.data)

                return response.data
                
            } catch (error) {
                this.loaderView= false
                if (error.inner) {
                    error.inner.forEach(e=> {
                        console.log('path / message: ', e.path, ': ', e.message)
                        this.errors[e.path]= e.message
                    })
                }
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

                if (this.form.agenda_data) {
                    const [d, M, y]= (this.form.agenda_data).split('/')
                    const [h, m, s]= (this.agenda_hora || "00:00:03").split(':')
                    dataStart= new Date(y, M-1, d, h, m)
                    //console.log('dataStart: ', dataStart)
                    dataEnd= new Date(y, M-1, d, h, m)
                    dataEnd.setHours(dataEnd.getHours()+1)
                    //console.log('dataEnd: ', dataEnd)
                }

                const eventData= {
                    id: this.google_calendar_id,
                    summary: this.form.resumo,
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
                this.loaderView= false
                if (error.inner) {
                    error.inner.forEach(e=> {
                        console.log('path / message: ', e.path, ': ', e.message)
                        this.errors[e.path]= e.message
                    })
                }
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
                    google_calendar_id: this.google_calendar_id
                }

                const response= await axios.post('/gcalendar/deleteevent', {eventData: eventData})
                console.log('deleteGoogleEvent response.data', response.data)

                return response.data
            } catch(error) {
                this.loaderView= false
                return {
                    success: false,
                    message: 'Erro ao deletar evento',
                    error: error
                }
            }

        },


        async save() {
            this.errors.resumo=''

            if (this.googleCalendar && this.google_calendar_id) {
                this.updateGoogleEvent()
            } else if (this.googleCalendar && !this.google_calendar_id) {
                this.createGoogleEvent()
            } else if (!this.googleCalendar && this.google_calendar_id) {
                this.deleteGoogleEvent()
            }

            //console.log("formattedDate: ", this.form.agenda_data)
            if (this.form.agenda_data) {
                const [d, M, y]= (this.form.agenda_data).split('/')
                const [h, m]= (this.agenda_hora || "00:00").split(':')
                const dataHora= new Date(y, M-1, d, h, m)
                this.agenda_inicio= format(dataHora, 'yyyy-MM-dd HH:mm:ss')
            }

            const taskData = {
                id: this.id,
                resumo: this.form.resumo,
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
                await this.schema.validate(this.form, {abortEarly: false})
                this.loaderView= true

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
                this.loaderView= false
                if (error.inner) {
                    error.inner.forEach(e=> {
                        console.log('path / message: ', e.path, ': ', e.message)
                        this.errors[e.path]= e.message
                    })
                }
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