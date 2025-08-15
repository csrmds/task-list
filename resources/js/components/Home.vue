<template>

    <!-- NOVA TAREFA -->
	<v-row class="mt-14 d-flex justify-center">
		<v-col cols="12" sm="11" md="10" lg="8" xl="6">
            <task-new @callRefreshTaskList="refreshTaskList()" :userData="userData" ></task-new>
		</v-col>
	</v-row>

    <!-- LISTA DE TAREFAS -->
    <v-row class="d-flex justify-center">
        <v-col cols="12" sm="11" md="10" lg="8" xl="6">
            <task-list ref="TaskList" @callEditModal="openEditModal($event)" :userData="userData"></task-list>
		</v-col>
    </v-row>

    <v-row class="d-flex justify-center">
        <v-col cols="12" sm="11" md="10" lg="8" xl="6">
            <task-edit-modal @callRefreshTaskList="refreshTaskList()" ref="TaskEditModal" class="w-100 w-sm-75 w-md-75 w-lg-50" :taskData="taskData" :userData="userData" ></task-edit-modal>
        </v-col>
    </v-row>
    
</template>


<script>
import TaskList from './TaskList.vue'
import TaskEditModal from './TaskEditModal.vue'

export default {
    props: {
        userData: Object
    },

    components: {
        TaskList,
        TaskEditModal,
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
        }
    }
}

</script>