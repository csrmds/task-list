<template>
  <!-- NOVA TAREFA -->
  <v-row class="mt-14 d-flex justify-center">
    <v-col
      cols="12"
      sm="11"
      md="10"
      lg="8"
      xl="6"
    >
      <task-new
        :user-data="userData"
        @call-refresh-task-list="refreshTaskList()"
      />
    </v-col>
  </v-row>

  <!-- LISTA DE TAREFAS -->
  <v-row class="d-flex justify-center">
    <v-col
      cols="12"
      sm="11"
      md="10"
      lg="8"
      xl="6"
    >
      <task-list
        ref="TaskList"
        :user-data="userData"
        @call-edit-modal="openEditModal($event)"
      />
    </v-col>
  </v-row>

  <v-row class="d-flex justify-center">
    <v-col
      cols="12"
      sm="11"
      md="10"
      lg="8"
      xl="6"
    >
      <task-edit-modal
        ref="TaskEditModal"
        class="w-100 w-sm-75 w-md-75 w-lg-50"
        :task-data="taskData"
        :user-data="userData"
        @call-refresh-task-list="refreshTaskList()"
      />
    </v-col>
  </v-row>
</template>


<script>
import TaskList from './TaskList.vue'
import TaskEditModal from './TaskEditModal.vue'

export default {

    components: {
        TaskList,
        TaskEditModal,
    },
    props: {
        userData: {
          type: Object,
          default: ()=> ({})
        }
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