<template>

    <v-card class="pa-4 bg-lime-lighten-2">
        <v-textarea rows="1" label="Nova Tarefa" variant="underlined" v-model="descricao"></v-textarea>
        <v-card-actions>

            <v-row>
                <v-col cols="2" align-self="center">
                    <v-text-field label="Agenda" variant="underlined" v-model="formattedDate" v-mask="'##/##/####'">

                        <v-menu v-model="datePickerView" activator="parent" :close-on-content-click="false" id="dropdown-date">
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

                
                <v-col cols="2" align-self="center" justify="center" >
                    <!-- <v-btn variant="tonal" class="w-100" @click="teste()">Emit</v-btn> -->
                </v-col>

                <v-col cols="2" align-self="center" justify="center" >
                    <v-btn variant="tonal" class="w-100" @click="save()">Salvar</v-btn>
                </v-col>

                


            </v-row>

        </v-card-actions>
    </v-card>

</template>

<script>
import { format } from "date-fns"

export default {
    name: 'TaskNew',

    data() {
        return {
            descricao: null,
            agenda_inicio: null,
            agenda_data: null,
            agenda_hora: null,
            agenda_fim: null,
            responsavel: null,
            categoria: null,
            tags: null,
            status: "A fazer",
            categorias: ["AZUL", "AMARELO", "VERMELHO"],
            selectedDate: null,
            formattedDate: "",
            datePickerView: false,
            horaList: [
                "06:00",
                "06:30",
                "07:00",
                "07:30",
                "08:00",
                "08:30",
                "09:00",
                "09:30",
                "10:00",
                "10:30",
                "11:00",
                "11:30",
                "12:00",
                "12:30",
                "13:00",
                "13:30",
                "14:00",
                "14:30",
                "15:00",
                "15:30",
                "16:00",
                "16:30",
                "17:00",
                "17:30",
                "18:00",
                "18:30",
                "19:00",
                "19:30",
                "20:00",
                "20:30",
                "21:00",
                "21:30",
                "22:00",
                "22:30",
                "23:00",
                "23:30",
                "00:00",
                "00:30",
                "01:00",
                "01:30",
                "02:00",
                "02:30",
                "03:00",
                "03:30",
                "04:00",
                "04:30",
                "05:00",
                "05:30",
            ]
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
            //event.preventDefault()
            console.log("formattedDate: ", this.formattedDate)
            if (this.formattedDate) {
                const [d, M, y]= (this.formattedDate).split('/')
                const [h, m]= (this.agenda_hora || "00:00").split(':')
                const dataHora= new Date(y, M, d, h, m)
                this.agenda_inicio= format(dataHora, 'yyyy-MM-dd HH:mm:ss')
            }


            const taskData = {
                descricao: this.descricao,
                agenda_inicio: this.agenda_inicio,
                agenda_fim: this.agenda_fim,
                responsavel: this.responsavel,
                categoria: this.categoria,
                tags: this.tags,
                status: this.status
            }

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
</style>