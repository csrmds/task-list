<template>
    <!-- Modal Trigger -->
    <a class="waves-effect waves-light btn modal-trigger" href="#taskEdit">Modal</a>

    <!-- Modal Structure -->
    <div id="taskEdit" class="modal">
        <div class="modal-content">
            <div class="row">
                <div class="col s12">
                    <textarea name="descricao" class="materialize-textarea" v-model="taskData.descricao"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s3">
                    <select>
                        <option value="amarelo">Amarelo</option>
                        <option value="azul">Azul</option>
                        <option value="vermelho">Vermelho</option>
                    </select>
                    <label>Categoria</label>
                </div>
                <div class="input-field col s2">
                    <input type="text" class="datepicker" v-model="agenda_data">
                    <label>Data</label>
                </div>
                <div class="input-field col s2">
                    <input type="text" id="autocomplete-hora" class="autocomplete" v-model="agenda_hora">
                    <label for="autocomplete-hora">Hora</label>
                </div>
                <div class="input-field col s2">
                    <select v-model="taskData.status">
                        <option v-for="item in status" :key="item" :value="item">
                            {{ item }}
                        </option>
                    </select>
                    <label for="autocomplete-hora">Status</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a class="waves-effect waves-green btn-flat">{{ currentStatus }}</a>
            <a href="#!" class="waves-effect waves-green btn-flat" @click="teste()">Teste</a>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
        </div>
    </div>
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
            categorias: ["Azul", "Amarelo", "Vermelho"],
            status: ["A fazer", "Em progresso", "Feito"],
            agenda_data: format(this.taskData.agenda_inicio, "dd/MM/yyyy"),
            agenda_hora: format(this.taskData.agenda_inicio, "HH:mm"),
            currentStatus: this.taskData.status
        }
    },

    methods: {
        async updateCurrentStatus() {
            console.log("update currentStatus")
            console.log("taskData: ", this.taskData.status)
            this.currentStatus= this.taskData.status
            console.log("currentStatus: ", this.currentStatus)
        },

        teste() {
            console.log("currentStatus: ", this.currentStatus)
            console.log("agenda_data: ", this.agenda_data)
            console.log("agenda_hora: ", this.agenda_hora)
        }
    },

    mounted() {
        var elems = document.querySelectorAll('select');
        var selects = M.FormSelect.init(elems, {});
        var elems = document.querySelectorAll('.datepicker');
        var datePicker = M.Datepicker.init(elems, {format: "dd/mm/yyyy", autoClose: true});
        
        const timeData = {
            "00:00": null,
            "00:30": null,
            "01:00": null,
            "01:30": null,
            "02:00": null,
            "02:30": null,
            "03:00": null,
            "03:30": null,
            "04:00": null,
            "04:30": null,
            "05:00": null,
            "05:30": null,
            "06:00": null,
            "06:30": null,
            "07:00": null,
            "07:30": null,
            "08:00": null,
            "08:30": null,
            "09:00": null,
            "09:30": null,
            "10:00": null,
            "10:30": null,
            "11:00": null,
            "11:30": null,
            "12:00": null,
            "12:30": null,
            "13:00": null,
            "13:30": null,
            "14:00": null,
            "14:30": null,
            "15:00": null,
            "15:30": null,
            "16:00": null,
            "16:30": null,
            "17:00": null,
            "17:30": null,
            "18:00": null,
            "18:30": null,
            "19:00": null,
            "19:30": null,
            "20:00": null,
            "20:30": null,
            "21:00": null,
            "21:30": null,
            "22:00": null,
            "22:30": null,
            "23:00": null,
            "23:30": null,
        }

        elems = document.querySelectorAll('.autocomplete');
        var timeAutocomplete = M.Autocomplete.init(elems, {data: timeData, limit: 4});
    },

    watch: {
        // 'taskData.status'(newVal) {
        //     console.log("taskData watch...")
        //     this.$nextTick(()=> {
        //         //var elems = ;
        //         M.FormSelect.init(document.querySelectorAll('select'), {});
        //     })
        // },

        'taskData.agenda_inicio'(newVal) {
            console.log('taskData agenda watch', newVal)
            this.$nextTick(()=> {
                //const elems = document.querySelectorAll('.datepicker');
                M.Datepicker.init(document.querySelectorAll('.datepicker'), {format: "dd/mm/yyyy", autoClose: true});
            })
        }
    }
}
</script>


<style>
.modal-content {
    min-height: 400px;
}

.datepicker-date-display {
    display: none !important;
}

.datepicker-modal {
    max-width: 330px;
}

</style>