<template>

    <!-- NOVA TAREFA -->
	<v-row>
		<v-col>
			<v-sheet class="mx-auto w-50">
				<task-new @callRefreshTaskList="refreshTaskList()" ></task-new>
			</v-sheet>
		</v-col>
	</v-row>
    

    <!-- CAMPO DE PESQUISA / FILTRO -->
    <!-- <v-row>
        <v-col>
			<v-sheet class="mx-auto w-50 px-4">
				<task-filter/>
			</v-sheet>
		</v-col>
    </v-row> -->

    <!-- LISTA DE TAREFAS -->
    <v-row>
        <v-col>
			<v-sheet class="mx-auto w-50">
        		<task-list ref="TaskList" @callEditModal="openEditModal($event)"></task-list>
        	</v-sheet>
		</v-col>
    </v-row>

    <v-row>
        <v-col>
            <v-sheet>
                <task-edit-modal @callRefreshTaskList="refreshTaskList()" ref="TaskEditModal" class="w-50" :taskData="taskData" ></task-edit-modal>
            </v-sheet>
        </v-col>
    </v-row>
    
</template>


<script>
import TaskList from './TaskList.vue'
import TaskEditModal from './TaskEditModal.vue'

export default {
    components: {
        TaskList,
        TaskEditModal
    },

    data() {
        return {
            taskData: null
        }
    },

    methods: {
        refreshTaskList() {
            //console.log("ouviu e chamou refreshTaskList no componente Pai")
            this.$refs.TaskList.getList()
        },

        openEditModal(param) {
            this.taskData= param
            this.$refs.TaskEditModal.modalView= true
            console.log("parametro descricao recebido: ", param)
        }
    }
}

</script>