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
    <v-row>
        <v-col>
			<v-sheet class="mx-auto w-50 px-4">
				<task-filter/>
			</v-sheet>
		</v-col>
    </v-row>

    <!-- LISTA DE TAREFAS -->
    <v-row>
        <v-col>
			<v-sheet class="mx-auto w-50">
        		<task-list ref="TaskList"></task-list>
        	</v-sheet>
		</v-col>
    </v-row>
    
</template>


<script>
import TaskList from './TaskList.vue'

export default {
    components: {
        TaskList
    },

    data() {
        return {
            escuta: "algum texto"
        }
    },

    methods: {
        testePai(param) {
            console.log("evento escutado: ", param)
        },

        refreshTaskList() {
            console.log("ouviu e chamou refreshTaskList no componente Pai")
            this.$refs.TaskList.getList()
        }
    }
}

</script>